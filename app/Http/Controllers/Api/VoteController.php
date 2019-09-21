<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VoteRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\VoteResource;
use App\Models\Vote;

/**
 * @group Votes
 */
class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Vote::class)
            ->allowedIncludes(['user', 'task'])
            ->jsonPaginate();

        return VoteResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $vote
     * @return \Illuminate\Http\Response
     */
    public function show($vote)
    {
        $item = QueryBuilder::for(Vote::class)
            ->allowedIncludes(['user', 'task'])
            ->firstOrFail($vote);

        return new VoteResource($item);
    }
}
