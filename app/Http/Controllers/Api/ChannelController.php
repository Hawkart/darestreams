<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;

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
     * @queryParam include string String of connections: user, streams, tags. Example: user,streams
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
            ->allowedIncludes(['user', 'streams', 'tags'])
            ->jsonPaginate();

        return ChannelResource::collection($items);
    }

    /**
     * Detail channel's info.
     *
     * @queryParam include string String of connections: user, streams, tags. Example: user,streams
     *
     * @param  int  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($channel)
    {
        $item = QueryBuilder::for(Channel::class)
            ->allowedIncludes(['user', 'streams', 'tags'])
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
}
