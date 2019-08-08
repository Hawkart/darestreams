<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\GameResource;
use App\Models\Game;

/**
 * @group Games
 */
class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: streams,tags. Example: tags,streams
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = QueryBuilder::for(Game::class)
            ->defaultSort('id')
            ->allowedSorts('title', 'id')
            ->allowedIncludes(['streams', 'tags'])
            ->jsonPaginate();

        return GameResource::collection($games);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: streams,tags. Example: tags,streams
     *
     * @param  int  $game
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        $item = QueryBuilder::for(Game::class)
            ->allowedIncludes(['streams', 'tags'])
            ->findOrFail($game);

        return new GameResource($item);
    }
}
