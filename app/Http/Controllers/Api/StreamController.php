<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Illuminate\Http\Request;
use App\Models\Stream;
use App\Http\Resources\StreamResource;
use App\Http\Requests\StreamRequest;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Stream::class)
            ->allowedIncludes(['game', 'streams', 'user', 'tags'])
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
            ->allowedIncludes(['game', 'streams', 'user', 'tags'])
            ->findOrFail($stream);

        return new StreamResource($item);
    }

    /**
     * @param StreamRequest $request
     */
    public function store(StreamRequest $request)
    {
        //Todo: check if user has channel
    }

    /**
     * @param $id
     * @param StreamRequest $request
     */
    public function update(Stream $stream, StreamRequest $request)
    {

    }
}
