<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Illuminate\Http\Request;
use App\Models\Stream;
use App\Http\Resources\StreamResource;
use App\Http\Requests\StreamRequest;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Stream::class)
            ->allowedIncludes(['game', 'streams', 'tags', 'channel'])
            ->jsonPaginate();

        return StreamResource::collection($items);
    }

    /**
     * Display the specified resource.
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

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams.success_created')
        ], 200);
    }

    /**
     * Update stream.
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

        $stream->update($request->only(['start_at', 'tags']));

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams.success_updated')
        ], 200);
    }
}
