<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Transaction::class)
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->jsonPaginate();

        return TransactionResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = QueryBuilder::for(Transaction::class)
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->findOrFail($id);

        return new TransactionResource($item);
    }
}
