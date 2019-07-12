<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\OAuthProviderResource;
use App\Models\OAuthProvider;

class OAuthProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(OAuthProvider::class)
            ->allowedIncludes(['user'])
            ->jsonPaginate();

        return OAuthProviderResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = QueryBuilder::for(OAuthProvider::class)
            ->allowedIncludes(['user'])
            ->findOrFail($id);

        return new OAuthProviderResource($item);
    }
}
