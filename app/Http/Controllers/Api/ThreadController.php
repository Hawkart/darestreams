<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
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
     * @queryParam include string String of connections: messages, participants. Example: messages
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Thread::class)
            ->defaultSort('-id')
            ->allowedSorts(['id', 'tile'])
            ->allowedIncludes(['messages', 'participants'])
            ->jsonPaginate();

        return ThreadResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: messages, participants. Example: messages
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
