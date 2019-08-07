<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;

/**
 * @group Threads
 */
class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Thread::class)
            ->allowedIncludes(['messages', 'participants'])
            ->jsonPaginate();

        return ThreadResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($thread)
    {
        $item = QueryBuilder::for(Thread::class)
            ->allowedIncludes(['messages', 'participants'])
            ->findOrFail($thread);

        return new ThreadResource($item);
    }
}
