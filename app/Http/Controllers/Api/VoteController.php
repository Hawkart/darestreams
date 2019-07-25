<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VoteRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\VoteResource;
use App\Models\Vote;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = QueryBuilder::for(Vote::class)
            ->allowedIncludes(['user', 'task'])
            ->findOrFail($id);

        return new VoteResource($item);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(VoteRequest $request, $id)
    {

    }
}
