<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ThreadResource;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Illuminate\Http\Request;
use App\Models\Stream;
use App\Http\Resources\StreamResource;
use App\Http\Requests\StreamRequest;
use App\Models\Thread;

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
        $this->middleware('auth:api')->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: game, streams, tags, channel. Example: game,streams
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
            ->allowedIncludes(['game', 'streams', 'tags', 'channel'])
            ->jsonPaginate();

        return StreamResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: game, streams, tags, channel. Example: game,streams
     *
     * @param  int  $stream
     * @return \Illuminate\Http\Response
     */
    public function show($stream)
    {
        $item = QueryBuilder::for(Stream::class)
            ->allowedIncludes(['game', 'streams', 'channel', 'tags'])
            ->findOrFail($stream);

        return new StreamResource($item);
    }

    /**
     * Create new stream.
     * @authenticated
     *
     * @bodyParam game_id int required Select category from games list.
     * @bodyParam link string required Link on the stream.
     * @bodyParam start_at datetime required Datetime of starting stream.
     * @bodyParam tags Additional tags to stream.
     *
     * @param StreamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StreamRequest $request)
    {
        $user = auth()->user();

        if(!$user->channel)
            return response()->json(['error' => trans('api/streams.failed_no_channel')], 422);

        $input = $request->all();
        $input['channel_id'] = $user->channel->id;

        $obj = new Stream();
        $obj->fill($input);
        $obj->save();

        //Todo: Add tags code.

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams.success_created')
        ], 200);
    }

    /**
     * Update stream.
     *
     * Update before starting of the stream.
     *
     * @authenticated
     *
     * @bodyParam game_id int Select category from games list.
     * @bodyParam link string Link on the stream.
     * @bodyParam start_at datetime required Datetime of starting stream.
     * @bodyParam tags Additional tags to stream.
     *
     * @param Stream $stream
     * @param StreamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Stream $stream, StreamRequest $request)
    {
        $user = auth()->user();

        if(!$user->channel || ($user->channel->id != $stream->channel_id))
            return response()->json(['error' => trans('api/streams.failed_channel')], 422);

        //Todo: Update if not active or finished

        $stream->update($request->only(['start_at', 'tags', 'game_id', 'link']));

        //Todo: Add tags code.

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams.success_updated')
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
}
