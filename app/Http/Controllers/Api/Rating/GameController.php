<?php

namespace App\Http\Controllers\Api\Rating;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use DB;
use Cache;

/**
 * @group Rating games
 */
class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: lastHistory. Example: lastHistory
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cache_key = Str::slug('getGamesStat');
        $cacheTags = Cache::tags(['index', 'getGamesStat']);

        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            $items = Game::where('rating', '>', 0)
                ->orderBy('rating', 'desc')
                ->get();

            $items->each(function($item) {
                $item->load('lastHistory');
            });

            $cacheTags->put($cache_key, $items, 60*60*2);   //2 hours
        }

        return GameResource::collection($items);
    }

    /**
     * Display a detail of the resource.
     *
     * @queryParam include string String of connections: history. Example: history
     *
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        $item = QueryBuilder::for(Game::class)
            ->allowedIncludes(['history'])
            ->findOrFail($game);

        GameResource::withoutWrapping();

        return new GameResource($item);
    }
}