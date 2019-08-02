<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\AccountResource;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Account::class)
            ->allowedIncludes(['user'])
            ->jsonPaginate();

        return AccountResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $account
     * @return \Illuminate\Http\Response
     */
    public function show($account)
    {
        $item = QueryBuilder::for(Account::class)
            ->allowedIncludes(['user'])
            ->findOrFail($account);

        return new AccountResource($item);
    }
}
