<?php

namespace App\Http\Controllers\Api;

use App\Enums\StreamStatus;
use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\StreamResource;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;
use Cache;

/**
 * @group Channels
 */
class ChannelController extends Controller
{
    /**
     * ChannelController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: user, streams, tags, game. Example: user,streams
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     * @queryParam page array Use as page[number]=1&page[size]=2.
     * @queryParam filter array Can filter by title
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Channel::class)
            ->defaultSort('title')
            ->allowedFilters(['title'])
            ->allowedSorts('title', 'id')
            ->allowedIncludes(['user', 'streams', 'tags', 'game'])
            ->jsonPaginate();

        return ChannelResource::collection($items);
    }

    /**
     * Detail channel's info.
     *
     * Get by id or slug.
     *
     * @queryParam include string String of connections: user, streams, tags, game. Example: user,streams
     *
     * @param  int  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $item = QueryBuilder::for(Channel::class)
            ->allowedIncludes(['user', 'streams', 'tags', 'game']);

        if(is_numeric($slug)) {
            $item = $item->where('id', $slug)->firstOrFail();
        }else{
            $item = $item->where('slug', $slug)->firstOrFail();
        }

        ChannelResource::withoutWrapping();

        return new ChannelResource($item);
    }

    /**
     * Update info about channel.
     * @authenticated
     *
     * @bodyParam description string Description of channel. Example: Long description.
     * @bodyParam logo file Logo for your channel. Possible formats: png, jpg.
     * @bodyParam game_id int Select category from games list.
     *
     * @param ChannelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ChannelRequest $request, Channel $channel)
    {
        if(!auth()->user()->ownerOfChannel($channel->id))
            return setErrorAfterValidation(['id' => trans('api/channel.failed_not_your_channel')]);

        $channel->fill($request->only(['description', 'logo', 'game_id']));
        $channel->save();

        ChannelResource::withoutWrapping();

        return response()->json([
            'data' => new ChannelResource($channel),
            'message'=> trans('api/channel.success_updated')
        ], 200);
    }

    /**
     * Get top channels
     * @queryParam hours Integer Check amount donations sum for last N hours. Default: 240.
     * @queryParam limit Integer. Limit of top channels. Default: 10.
     * @queryParam skip Integer. Offset of top channels. Default: 0.
     * @queryParam game_id Integer. Filter channels by category.
     *
     * @queryParam include string String of connections: user, streams, tags, game. Example: user,streams
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     */
    public function top(Request $request)
    {
        $hours = $request->has('hours') ? $request->get('hours') : 240;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $skip = $request->has('skip') ? $request->get('skip') : 0;
        $lastDays = Carbon::now()->subHours($hours);

        //Calculate cache key
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $cache_key = Str::slug('topChannels'.$queryString);

        $tags = ['index', 'topChannels'];
        if($request->has('game_id'))
            $tags[] = 'byGame';

        $cacheTags = Cache::tags($tags);

        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            //get streams finished amount donations for last 10 days
            $sub = DB::table('streams')->select('ch.id', 'ch.title', 'ch.game_id', 'ch.views', 'ch.slug', 'ch.link', 'ch.user_id', 'ch.description', 'ch.created_at', 'ch.logo', 'ch.updated_at', DB::raw("sum(amount_donations) as donates"))
                ->leftJoin('channels as ch', 'ch.id', '=', 'streams.channel_id')
                ->whereDate('start_at', '>=', DB::raw($lastDays->toDateString()))
                ->groupBy('ch.id', 'ch.title', 'ch.game_id', 'ch.views', 'ch.slug', 'ch.link', 'ch.user_id', 'ch.description', 'ch.created_at', 'ch.logo', 'ch.updated_at')
                ->orderByDesc('donates');

            $list = DB::table(DB::raw("({$sub->toSql()}) as t"))
                ->mergeBindings($sub)
                ->select('t.*')
                ->rightJoin('streams as st', function ($join) {
                    $join->on('st.channel_id', '=', 't.id')
                        ->where('st.start_at', '<', DB::raw('NOW()'))
                        ->whereNull('st.ended_at');
                })
                ->whereNotNull('t.id')
                ->offset($skip)
                ->limit($limit)
                ->get();

            $data = $list->pluck('donates', 'id')->toArray();
            $ids = $list->pluck('id')->toArray();

            if(count($ids)>0)
            {
                $oids = implode(',', $ids);

                $items = QueryBuilder::for(Channel::class)
                    ->whereIn('id', $ids);

                if($request->has('game_id'))
                    $items = $items->where('game_id', $request->get('game_id'));

                $items = $items->orderByRaw(DB::raw("FIELD(id, $oids)"))
                    ->allowedSorts('title', 'id')
                    ->allowedIncludes(['user', 'streams', 'tags', 'game'])
                    ->jsonPaginate();

                foreach ($items as &$item)
                    $item->donates = $data[$item->id];

            }else{
                $items = [];
            }

            $cacheTags->put($cache_key, $items, 1800);
        }

        return count($items)>0 ? ChannelResource::collection($items) : ['data' => []];
    }

    /**
     * Get top donated channels
     * @queryParam hours Integer Check amount donations sum for last N hours. Default: 240.
     * @queryParam limit Integer. Limit of top channels. Default: 10.
     * @queryParam skip Integer. Offset of top channels. Default: 0.
     * @queryParam game_id Integer. Filter channels by category.
     *
     * @queryParam include string String of connections: user, streams, tags, game. Example: user,streams
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     */
    public function topDonated(Request $request)
    {
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $skip = $request->has('skip') ? $request->get('skip') : 0;

        //Calculate cache key
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $cache_key = Str::slug('topDonatedChannels'.$queryString);

        $tags = ['index', 'topDonatedChannels'];
        if($request->has('game_id'))
            $tags[] = 'byGame';

        $cacheTags = Cache::tags($tags);

        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            //get streams finished amount donations for last 10 days
            $sub = DB::table('streams')->select('ch.id', 'ch.title', 'ch.game_id', 'ch.views', 'ch.slug', 'ch.link', 'ch.user_id', 'ch.description', 'ch.created_at', 'ch.logo', 'ch.updated_at', DB::raw("sum(amount_donations) as donates"))
                ->leftJoin('channels as ch', 'ch.id', '=', 'streams.channel_id')
                ->where('status', '<>',StreamStatus::Canceled)
                ->groupBy('ch.id', 'ch.title', 'ch.game_id', 'ch.views', 'ch.slug', 'ch.link', 'ch.user_id', 'ch.description', 'ch.created_at', 'ch.logo', 'ch.updated_at')
                ->orderByDesc('donates');

            $list = DB::table(DB::raw("({$sub->toSql()}) as t"))
                ->mergeBindings($sub)
                ->select('t.*')
                ->rightJoin('streams as st', function ($join) {
                    $join->on('st.channel_id', '=', 't.id')
                        ->where('st.status', '<>',StreamStatus::Canceled);
                })
                ->whereNotNull('t.id')
                ->get();

            $data = $list->pluck('donates', 'id')->toArray();
            $ids = $list->pluck('id')->toArray();

            if(count($ids)>0)
            {
                $oids = implode(',', $ids);

                $items = QueryBuilder::for(Channel::class)
                    ->whereIn('id', $ids);

                if($request->has('game_id'))
                    $items = $items->where('game_id', $request->get('game_id'));

                $items = $items->orderByRaw(DB::raw("FIELD(id, $oids)"))
                    ->allowedSorts('title', 'id')
                    ->allowedIncludes(['user', 'streams', 'tags', 'game'])
                    ->offset($skip)
                    ->limit($limit)
                    ->jsonPaginate();

                foreach ($items as &$item)
                    $item->donates = $data[$item->id];

            }else{
                $items = [];
            }

            $cacheTags->put($cache_key, $items, 1800);
        }

        return count($items)>0 ? ChannelResource::collection($items) : ['data' => []];
    }

    /**
     * Get streams from channel
     *
     * {slug} - slug or id of channel.
     *
     * For any connection may add _count for counting. Example: tasks_completed_count
     *
     * @queryParam include string String of connections: game, tasks, tasks.votes, tags, channel, user, tasks_completed. Example: game,tasks
     * @queryParam sort string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id. For desc use '-' prefix. Example: -quantity_donators
     * @queryParam page array Use as page[number]=1&page[size]=2.
     * @queryParam filter array Allows filter only by status. Use as filter[status]=1,2.
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     * @return \Illuminate\Http\Response
     */
    public function streams($slug, Request $request)
    {
        if(is_numeric($slug)) {
            $channel = Channel::where('id', $slug)->firstOrFail();
        }else{
            $channel = Channel::where('slug', $slug)->firstOrFail();
        }

        $items = QueryBuilder::for(Stream::class)
            ->allowedFilters(['status'])
            ->where('channel_id', $channel->id)
            ->defaultSort('-start_at')
            ->allowedSorts('quantity_donators', 'quantity_donations', 'amount_donations' ,'id')
            ->allowedIncludes(['game', 'tasks', 'tags', 'channel', 'user', 'tasksCompleted'])
            ->jsonPaginate();

        return StreamResource::collection($items);
    }
}
