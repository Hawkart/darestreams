<?php

namespace App\Http\Controllers\Api;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Models\Stream;
use App\Http\Resources\StreamResource;
use App\Http\Requests\StreamRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Cache;

/**
 * @group Streams
 */
class StreamController extends Controller
{
    /**
     * StreamController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'setFinished']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: game, tasks, tags, channel, user. Example: game,tasks
     * @queryParam sort string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id. For desc use '-' prefix. Example: -quantity_donators
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Stream::class)
            ->defaultSort('-quantity_donators')
            ->allowedSorts('quantity_donators', 'quantity_donations', 'amount_donations' ,'id')
            ->allowedIncludes(['game', 'tasks', 'tags', 'channel', 'user'])
            ->jsonPaginate();

        return StreamResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: game, tasks, tags, channel, user. Example: game,tasks
     *
     * @param  int  $stream
     * @return \Illuminate\Http\Response
     */
    public function show($stream)
    {
        $item = QueryBuilder::for(Stream::class)
            ->allowedIncludes(['game', 'tasks', 'channel', 'tags', 'user'])
            ->findOrFail($stream);

        return new StreamResource($item);
    }

    /**
     * Create new stream.
     * @authenticated
     * @bodyParam channel_id integer required Select channel.
     * @bodyParam title string required Title of stream.
     * @bodyParam link string required Link on the stream.
     * @bodyParam start_at datetime required Datetime of starting stream.
     * @bodyParam allow_task_before_stream boolean Allow to create task before stream starts.
     * @bodyParam allow_task_when_stream boolean Allow to create task while stream is active.
     * @bodyParam min_amount_task_before_stream decimal Min amount to create task before stream starts.
     * @bodyParam min_amount_task_when_stream decimal Min amount to create task while stream is active.
     * @bodyParam min_amount_donate_task_before_stream decimal Min donate for task before stream starts.
     * @bodyParam min_amount_donate_task_when_stream decimal Min donate for task while stream is active.
     * @bodyParam tags Additional tags to stream.
     *
     * @param StreamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StreamRequest $request)
    {
        $user = auth()->user();

        if(!$user->channel || $user->channel->id!=$request->get('channel_id'))
            return response()->json(['error' => trans('api/stream.failed_no_channel')], 422);

        //Cannot create new before exists not finished
        if($user->streams()->whereIn('status', [StreamStatus::Created, StreamStatus::Active])->count()>0)
            return response()->json(['error' => trans('api/stream.you_still_have_active_streams')], 422);

        $input = $request->all();
        $channel = Channel::findOrFail($request->get('channel_id'));
        $input['game_id'] = $channel->game_id;

        $stream = new Stream();
        $stream->fill($input);
        $stream->save();

        //Todo: Add tags code.

        StreamResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new StreamResource($stream),
            'message'=> trans('api/stream.success_created')
        ], 200);
    }

    /**
     * Update stream.
     *
     * Update before starting of the stream.
     *
     * @authenticated
     *
     * @bodyParam title string Title of stream.
     * @bodyParam link string Link on the stream.
     * @bodyParam start_at datetime Datetime of starting stream.
     * @bodyParam status integer Status of stream.
     * @bodyParam allow_task_before_stream boolean Allow to create task before stream starts.
     * @bodyParam allow_task_when_stream boolean Allow to create task while stream is active.
     * @bodyParam min_amount_task_before_stream decimal Min amount to create task before stream starts.
     * @bodyParam min_amount_task_when_stream decimal Min amount to create task while stream is active.
     * @bodyParam min_amount_donate_task_before_stream decimal Min donate for task before stream starts.
     * @bodyParam min_amount_donate_task_when_stream decimal Min donate for task while stream is active.
     * @bodyParam tags Additional tags to stream.
     *
     * @param Stream $stream
     * @param StreamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Stream $stream, StreamRequest $request)
    {
        $user = auth()->user();
        $status = $request->has('status') ? $request->get('status') : -1;

        if(!$user->channel || ($user->channel->id != $stream->channel_id))
            return response()->json(['error' => trans('api/stream.failed_channel')], 422);

        //try to change to another status
        if($status>-1 && $status!=StreamStatus::FinishedWaitPay)
            return response()->json(['error' => trans('api/stream.cannot_update_status_stream')], 422);

        //Set finished
        if($stream->status==StreamStatus::Active && $status==StreamStatus::FinishedWaitPay)
        {
            try {
                $stream = DB::transaction(function () use ($stream) {

                    $stream->update([
                        'status' => StreamStatus::FinishedWaitPay,
                        'ended_at' => Carbon::now('UTC')
                    ]);

                    //set all task to status can vote
                    $tasks = $stream->tasks;    //Todo: All task?
                    if(count($tasks)>0)
                    {
                        foreach($tasks as $task)
                            $task->update(['status' => TaskStatus::AllowVote]);
                    }

                    return $stream;
                });
            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }
        }else{

            if($stream->status==StreamStatus::Created)
            {
                $allowed = ['link', 'start_at', 'title', 'tags',
                    'allow_task_before_stream', 'allow_task_when_stream', 'min_amount_task_before_stream',
                    'min_amount_task_when_stream', 'min_amount_donate_task_before_stream', 'min_amount_donate_task_when_stream'
                ];
            }else if($stream->status==StreamStatus::Active){
                $allowed = ['allow_task_before_stream', 'allow_task_when_stream', 'min_amount_task_before_stream',
                    'min_amount_task_when_stream', 'min_amount_donate_task_before_stream', 'min_amount_donate_task_when_stream'];
            }else{
                return response()->json(['error' => trans('api/stream.cannot_update_stream_already_finished')], 422);
            }

            $stream->update($request->only($allowed));
        }

        StreamResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new StreamResource($stream),
            'message'=> trans('api/stream.success_updated')
        ], 200);
    }

    /**
     * Get stream's chat info.
     *
     * @queryParam include string String of connections: messages, participants. Example: messages
     *
     * @param Stream $stream
     * @return ThreadResource
     */
    public function thread(Stream $stream)
    {
        $item = QueryBuilder::for($stream->threads()->getQuery())
            ->allowedIncludes(['messages', 'participants'])
            ->firstOrFail();

        return new ThreadResource($item);
    }

    /**
     * Get top streams
     * @queryParam limit Integer. Limit of top channels. Default: 10.
     * @queryParam skip Integer. Offset of top channels. Default: 0.
     *
     * @queryParam include string String of connections: user, tasks, tags, game. Example: user,tasks
     */
    public function top(Request $request)
    {
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $skip = $request->has('skip') ? $request->get('skip') : 0;

        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $cache_key = Str::slug('topStreams'.$queryString);

        $tags = ['index', 'topStreams'];

        $cacheTags = Cache::tags($tags);
        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            $list = DB::table('channels as ch')
                ->select('st.id', 'ch.views')
                ->leftJoin('streams as st', 'ch.id', '=', 'st.channel_id')
                ->groupBy('st.id', 'ch.views')
                ->orderByDesc('views')
                ->whereNotNull('st.id')
                ->offset($skip)
                ->limit($limit)
                ->get();

            $ids = $list->pluck('id')->toArray();
            $oids = implode(',', $ids);

            $items = QueryBuilder::for(Stream::class)
                ->whereIn('id', $ids)
                ->orderByRaw(DB::raw("FIELD(id, $oids)"))
                ->allowedIncludes(['game', 'tasks', 'tags', 'channel', 'user'])
                ->where('status', StreamStatus::Active)
                ->jsonPaginate();

            $cacheTags->put($cache_key, $items, 1800);
        }

        return StreamResource::collection($items);
    }
}
