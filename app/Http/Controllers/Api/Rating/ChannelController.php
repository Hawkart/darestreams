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
     * ChannelController constructor.
     */
    public function __construct()
    {
        //$this->middleware('auth:api')->only(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: history, latestHistory. Example: latestHistory
     * @queryParam sort string Sort items by fields: title, id. For desc use '-' prefix. Example: -id
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Channel::class)
            ->top()
            ->where('rating', '>', 0)
            ->defaultSort('-rating')
            ->allowedIncludes(['history', 'h'])
            ->jsonPaginate();

        return ChannelResource::collection($items);
    }
}