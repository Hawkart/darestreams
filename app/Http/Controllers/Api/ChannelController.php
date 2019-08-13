<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Carbon\Carbon;
use DB;

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
        $this->middleware('auth:api')->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: user, streams, tags, game. Example: user,streams
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Channel::class)
            ->defaultSort('id')
            ->allowedSorts('title', 'id')
            ->allowedIncludes(['user', 'streams', 'tags', 'game'])
            ->jsonPaginate();

        return ChannelResource::collection($items);
    }

    /**
     * Detail channel's info.
     *
     * @queryParam include string String of connections: user, streams, tags, game. Example: user,streams
     *
     * @param  int  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($channel)
    {
        $item = QueryBuilder::for(Channel::class)
            ->allowedIncludes(['user', 'streams', 'tags', 'game'])
            ->findOrFail($channel);

        return new ChannelResource($item);
    }

    /**
     * Create new channel for user.
     * @authenticated
     *
     * @bodyParam title string required Title of channel. Example: My new channel.
     * @bodyParam description string required Description of channel. Example: Long description.
     * @bodyParam logo file Logo for your channel. Possible formats: png, jpg.
     * @bodyParam game_id int Select category from games list.
     * @bodyParam link string Link on the stream.
     *
     * @param ChannelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ChannelRequest $request)
    {
        $user = auth()->user();
        $input = $request->all();

        if($user->channel)
            return response()->json(['error' => trans('api/channel.failed_already_have_channel')], 422);

        $input['user_id'] = $user->id;

        //Todo: Check logo exist and store it.

        $obj = new Channel();
        $obj->fill($input);
        $obj->save();

        return response()->json([
            'success' => true,
            'message'=> trans('api/channel.success_created')
        ], 200);
    }

    /**
     * Update info about channel.
     * @authenticated
     *
     * @bodyParam title string Title of channel. Example: My new channel.
     * @bodyParam description string Description of channel. Example: Long description.
     * @bodyParam logo file Logo for your channel. Possible formats: png, jpg.
     * @bodyParam game_id int Select category from games list.
     * @bodyParam link string Link on the stream.
     *
     * @param ChannelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ChannelRequest $request, Channel $channel)
    {
        $user = auth()->user();

        if(!isset($user->channel) || $user->channel->id!=$channel->id)
            return response()->json(['error' => trans('api/channel.failed_not_your_channel')], 422);

        $channel->fill($request->all());
        $channel->save();

        return response()->json([
            'success' => true,
            'message'=> trans('api/channel.success_updated')
        ], 200);
    }

    /**
     * Get top channels
     * @queryParam hours integer Check amount donations sum for last N hours. Default: 240.
     * @queryParam limit integer. Limit of top channels. Default: 10.
     */
    public function top(Request $request)
    {
        $hours = $request->has('hours') ? $request->get('hours') : 240;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $lastDays = Carbon::now()->subHours($hours);

        //get streams finished amount donations for last 10 days
        $sub = DB::table('streams')->select('ch.*', DB::raw("sum(amount_donations) as donates"))
            ->leftJoin('channels as ch', 'ch.id', '=', 'streams.channel_id')
            ->whereDate('start_at', '>=', DB::raw($lastDays->toDateString()))
            //->where('status', DB::raw(Stream::STATUS_FINISHED))
            ->groupBy('ch.id', 'ch.title', 'ch.game_id', 'ch.slug', 'ch.link', 'ch.user_id', 'ch.description', 'ch.created_at', 'ch.logo', 'ch.updated_at')
            ->orderByDesc('donates')
            ->limit($limit);

        $list = DB::table( DB::raw("({$sub->toSql()}) as t") )
            ->mergeBindings($sub)
            ->select('t.*')
            ->rightJoin('streams as st', function($join)
            {
                $join->on('st.channel_id', '=', 't.id')
                    ->where('st.start_at', '<', DB::raw('NOW()'))
                    ->whereNull('st.ended_at');
            })
            ->whereNotNull('t.id')
            ->get();
            //->toSql();

        $data = $list->pluck('donates', 'id')->toArray();
        $ids = $list->pluck('id')->toArray();
        $oids = implode(',', $ids);

        $items = QueryBuilder::for(Channel::class)
            ->whereIn('id', $ids)
            ->orderByRaw(DB::raw("FIELD(id, $oids)"))
            ->allowedSorts('title', 'id')
            ->allowedIncludes(['user', 'streams', 'tags', 'game'])
            ->jsonPaginate();

        foreach($items as &$item)
            $item->donates = $data[$item->id];

        return ChannelResource::collection($items);
    }
}
