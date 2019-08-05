<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Channel::class)
            ->allowedIncludes(['user', 'streams'])
            ->jsonPaginate();

        return ChannelResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($channel)
    {
        $item = QueryBuilder::for(Channel::class)
            ->allowedIncludes(['user', 'streams'])
            ->findOrFail($channel);

        return new ChannelResource($item);
    }

    /**
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

        $obj = new Channel();
        $obj->fill($input);
        $obj->save();

        return response()->json([
            'success' => true,
            'message'=> trans('api/channel.success_created')
        ], 200);
    }

    /**
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
