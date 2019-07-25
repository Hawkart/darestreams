<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TransactionResource;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $transactions = $user->getTransactions();

        $items = QueryBuilder::for($transactions)
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
    public function show(User $user, Transaction $transaction)
    {
        if(!$user->getTransactions()->whereId($transaction->id)->exist())
            return response()->json(['error' => trans('api/users/transaction.not_belong_to_user')], 403);

        $transaction = QueryBuilder::for(Transaction::whereId($transaction->id))
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->first();

        return new TransactionResource($transaction);
    }
}
