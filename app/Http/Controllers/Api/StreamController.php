<?php

namespace App\Http\Controllers\Api;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedSort;
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
     * @queryParam include string String of connections: game, tasks, tasks.votes, tags, channel, user. Example: game,tasks
     * @queryParam sort string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id, start_at. For desc use '-' prefix. Example: -quantity_donators
     * @queryParam page array Use as page[number]=1&page[size]=2.
     * @queryParam game_id Integer. Filter streams by category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $statuses = [StreamStatus::Created, StreamStatus::Canceled];

        $items = QueryBuilder::for(Stream::class)->whereNotIn('status', $statuses);

        if($request->has('game_id'))
            $items = $items->where('game_id', $request->get('game_id'));

        $items = $items->defaultSort('-quantity_donators')
            ->allowedSorts('quantity_donators', 'quantity_donations', 'amount_donations' ,'id', 'start_at')
            ->allowedIncludes(['game', 'tasks', 'tags', 'channel', 'user'])
            ->jsonPaginate();

        return StreamResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: game, tasks, tasks.votes, tags, channel, user. Example: game,tasks
     *
     * @param  int  $stream
     * @return \Illuminate\Http\Response
     */
    public function show($stream)
    {
        $item = QueryBuilder::for(Stream::class)
            ->allowedIncludes(['game', 'tasks', 'tasks.votes', 'channel', 'tags', 'user'])
            ->findOrFail($stream);

        StreamResource::withoutWrapping();

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
        $input = $request->except('start_at_view');
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

        if($stream->checkAlreadyFinished())
            abort(response()->json(['message' => trans('api/stream.cannot_update_stream_already_finished')], 400));

        if(!$user->channel || !$user->ownerOfChannel($stream->channel_id))
            abort(response()->json(['message' => trans('api/stream.failed_channel')], 400));

        //Streamer can change status only from Active to FinishedWaitPay
        if(
            ($stream->status==StreamStatus::Active && $status==StreamStatus::FinishedWaitPay) ||
            (in_array($stream->status, [StreamStatus::Active, StreamStatus::Created]) && $status==StreamStatus::Canceled)
        )
        {
            if($status==StreamStatus::FinishedWaitPay)
            {
                $task_status = TaskStatus::AllowVote;
            }else{
                $task_status = TaskStatus::Canceled;
            }

            try {
                $stream = DB::transaction(function () use ($stream, $task_status, $status) {

                    $stream->update([
                        'status' => $status,
                        'ended_at' => Carbon::now('UTC')
                    ]);

                    //set all task to status can vote
                    $tasks = $stream->tasks;    //Todo: All task?
                    if(count($tasks)>0)
                    {
                        foreach($tasks as $task)
                        {
                            if($task->amount_donations==0 && $task->adv_task_id==0)
                            {
                                $task->update(['status' => TaskStatus::PayFinished]);
                            }else{
                                $task->update(['status' => $task_status]);
                            }
                        }
                    }

                    return $stream;
                });
            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }
        }else{

            if($stream->status==StreamStatus::Created)
            {
                $allowed = ['link', 'start_at', 'title', 'tags', 'game_id',
                    'allow_task_before_stream', 'allow_task_when_stream', 'min_amount_task_before_stream',
                    'min_amount_task_when_stream', 'min_amount_donate_task_before_stream', 'min_amount_donate_task_when_stream'
                ];
            }else if($stream->status==StreamStatus::Active){
                $allowed = ['allow_task_before_stream', 'allow_task_when_stream', 'min_amount_task_before_stream',
                    'min_amount_task_when_stream', 'min_amount_donate_task_before_stream', 'min_amount_donate_task_when_stream'];
            }else{
                $allowed = [];
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
     * @queryParam limit Integer. Limit of top channels. Default: 3.
     * @queryParam skip Integer. Offset of top channels. Default: 0.
     * @queryParam game_id Integer. Filter channels by category.
     *
     * @queryParam include string String of connections: user, tasks, tags, game. Example: user,tasks
     * @queryParam sort string Sort items by fields: amount_donations, views. For desc use '-' prefix. Example: -views
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     */
    public function top(Request $request)
    {
        $limit = $request->has('limit') ? $request->get('limit') : 3;
        $skip = $request->has('skip') ? $request->get('skip') : 0;

        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $cache_key = Str::slug('topStreams'.$queryString);

        $tags = ['index', 'topStreams'];
        if($request->has('game_id'))
            $tags[] = 'byGame';

        $cacheTags = Cache::tags($tags);
        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {
            $items = QueryBuilder::for(Stream::class)
                ->where('status', StreamStatus::Active);

            if($request->has('game_id'))
                $items = $items->whereHas('channel', function($q) use ($request){
                    $q->where('game_id', $request->get('game_id'));
                });

            $items = $items
                ->defaultSort('-start_at')
                ->allowedIncludes(['game', 'tasks', 'tags', 'channel', 'user'])
                ->allowedSorts('start_at', 'views', 'amount_donations')
                ->offset($skip)
                ->limit($limit)
                ->get();

            $cacheTags->put($cache_key, $items, 300);
        }

        return StreamResource::collection($items);
    }

    /**
     * Get closest streams
     * @queryParam limit Integer. Limit of top channels. Default: 3.
     * @queryParam skip Integer. Offset of top channels. Default: 0.
     * @queryParam game_id Integer. Filter channels by category.
     *
     * @queryParam include string String of connections: user, tasks, tags, game. Example: user,tasks
     * @queryParam sort string Sort items by fields: start_at, views. For desc use '-' prefix. Example: -views
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     */
    public function closest(Request $request)
    {
        $limit = $request->has('limit') ? $request->get('limit') : 3;
        $skip = $request->has('skip') ? $request->get('skip') : 0;

        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $cache_key = Str::slug('closestStreams'.$queryString);

        $tags = ['index', 'closestStreams'];
        if($request->has('game_id'))
            $tags[] = 'byGame';

        $cacheTags = Cache::tags($tags);
        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {
            $items = QueryBuilder::for(Stream::class)
                ->where('status', StreamStatus::Created);

            if($request->has('game_id'))
                $items = $items->whereHas('channel', function($q) use ($request){
                    $q->where('game_id', $request->get('game_id'));
                });

            $items = $items
                ->defaultSort('-start_at')
                ->allowedIncludes(['game', 'tasks', 'tags', 'channel', 'user'])
                ->allowedSorts('views', 'start_at')
                ->offset($skip)
                ->limit($limit)
                ->get();

            $cacheTags->put($cache_key, $items, 300);
        }

        return StreamResource::collection($items);
    }

    /**
     * Get list of statuses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statuses()
    {
        return response()->json(StreamStatus::getInstances(), 200);
    }
}