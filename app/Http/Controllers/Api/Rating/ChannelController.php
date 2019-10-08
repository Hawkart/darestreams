<?php

namespace App\Http\Controllers\Api\Rating;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Rating\ChannelResource;
use App\Models\Rating\Channel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

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
        /*$items = QueryBuilder::for(Channel::class)
            ->top()
            ->where('rating', '>', 0)
            ->defaultSort('-rating')
            ->allowedIncludes(['history'])
            ->with(['history' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(2);
            }])->jsonPaginate();*/


        $items = Channel::with(['history' => function ($query) {
                $query->latest()->limit(2);
            }])
            ->top()
            ->where('rating', '>', 0)
            ->orderBy('rating', 'desc')
            ->jsonPaginate();

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
            ->allowedIncludes(['history'])
            ->findOrFail($channel);

        return new ChannelResource($item);
    }
}