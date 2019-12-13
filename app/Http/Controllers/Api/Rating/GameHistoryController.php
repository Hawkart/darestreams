<?php

namespace App\Http\Controllers\Api\Rating;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Rating\GameHistoryResource;
use App\Models\Rating\GameHistory;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Rating game history
 */
class GameHistoryController extends Controller
{
    /**
     * Display a detail of the resource.
     *
     * @queryParam include string String of connections: gameChannels, gameChannels.channel, game. Example: gameChannels
     *
     * @return \Illuminate\Http\Response
     */
    public function show($history)
    {
        $item = QueryBuilder::for(GameHistory::class)
            ->allowedIncludes(['gameChannels', 'gameChannels.channel', 'game'])
            ->findOrFail($history);

        GameHistoryResource::withoutWrapping();

        return new GameHistoryResource($item);
    }
}