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
            ->allowedIncludes(['game', 'streams', 'user'])
            ->jsonPaginate();

        return StreamResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = QueryBuilder::for(Stream::class)
            ->allowedIncludes(['game', 'streams', 'user'])
            ->findOrFail($id);

        return new StreamResource($item);
    }

    /**
     * @param StreamRequest $request
     */
    public function store(StreamRequest $request)
    {

    }

    /**
     * @param $id
     * @param StreamRequest $request
     */
    public function update($id, StreamRequest $request)
    {

    }
}
