<?php

namespace App\Http\Controllers\Api\Rating;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Rating\GameHistoryResource;
use App\Models\Game;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Rating game history
 */
class GameHistoryController extends Controller
{
    /**
     * Display a detail of the resource.
     *
     * @queryParam include string String of connections: gameChannels. Example: gameChannels
     *
     * @return \Illuminate\Http\Response
     */
    public function show($history)
    {
        $item = QueryBuilder::for(Game::class)
            ->allowedIncludes(['gameChannels'])
            ->findOrFail($history);

        GameHistoryResource::withoutWrapping();

        return new GameHistoryResource($item);
    }
}