<?php

namespace App\Http\Controllers\Api\Rating;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Rating\ChannelResource;
use App\Models\Rating\Channel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use DB;
use Cache;

/**
 * @group Rating Channels
 */
class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: history. Example: history
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cache_key = Str::slug('getChannelStat');
        $cacheTags = Cache::tags(['index', 'getChannelStat']);

        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            $items = Channel::top()
                ->where('rating', '>', 0)
                ->with(['game'])
                ->orderBy('rating', 'desc')
                ->get();

            $items->each(function($item) {
                $item->load('lastHistory');
            });

            $cacheTags->put($cache_key, $items, 60*60*2);   //2 hours
        }

        return ChannelResource::collection($items);
    }

    /**
     * Display a detail of the resource.
     *
     * @queryParam include string String of connections: history. Example: history
     *
     * @return \Illuminate\Http\Response
     */
    public function show($channel)
    {
        $item = QueryBuilder::for(Channel::class)
            ->with(['game'])
            ->allowedIncludes(['history'])
            ->findOrFail($channel);

        return new ChannelResource($item);
    }
}