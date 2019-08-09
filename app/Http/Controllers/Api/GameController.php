<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\GameRequest;
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
     * GameController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['offer']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: streams,tags, channels. Example: tags,streams
     * @queryParam sort string Sort items by fields: title, popularity. For desc use '-' prefix. Example: -popularity
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = QueryBuilder::for(Game::class)
            ->defaultSort('-popularity')
            ->allowedSorts('title', 'popularity')
            ->allowedIncludes(['streams', 'tags', 'channels'])
            ->jsonPaginate();

        return GameResource::collection($games);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: streams,tags,channels. Example: tags,streams
     *
     * @param  int  $game
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        $item = QueryBuilder::for(Game::class)
            ->allowedIncludes(['streams', 'tags', 'channels'])
            ->findOrFail($game);

        return new GameResource($item);
    }

    /**
     * Offer new category.
     * @authenticated
     *
     * @bodyParam title string required Title of new category. Example: New category.
     *
     * @param GameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function offer(GameRequest $request)
    {
        if(Game::where('title', $request->get('title'))->exists())
            return response()->json(['error' => trans('api/game.failed_already_exists')], 422);

        //Todo: Send notification about offering to support email.

        return response()->json([
            'success' => true,
            'message'=> trans('api/game.offer_success_created')
        ], 200);
    }
}
