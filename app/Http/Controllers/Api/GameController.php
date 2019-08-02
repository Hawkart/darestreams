<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\GameResource;
use App\Models\Game;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = QueryBuilder::for(Game::class)
            ->allowedIncludes(['streams'])
            ->jsonPaginate();

        return GameResource::collection($games);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $game
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        $item = QueryBuilder::for(Game::class)
            ->allowedIncludes(['streams'])
            ->findOrFail($game);

        return new GameResource($item);
    }
}
