<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TransactionResource;
use App\Models\User;

/**
 * @group Users transactions
 */
class TransactionController extends Controller
{
    /**
     * TransactionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get user's all transactions.
     * @authenticated
     *
     * {user} - user id integer
     * @queryParam include string String of connections: ['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task']. Example: task.
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/user.failed_user_not_current')], 403);

        $transactions = $user->getTransactions();

        $items = QueryBuilder::for($transactions)
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->jsonPaginate();

        return TransactionResource::collection($items);
    }

    /**
     * Get user's one transaction.
     * @authenticated
     *
     * {user} - user id integer
     * {transaction} - transaction id integer
     * @queryParam include string String of connections: ['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task']. Example: task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Transaction $transaction)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/user.failed_user_not_current')], 403);

        if(!$user->getTransactions()->whereId($transaction->id)->exist())
            return response()->json(['error' => trans('api/users/transaction.not_belong_to_user')], 403);

        $transaction = QueryBuilder::for(Transaction::whereId($transaction->id))
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->first();

        return new TransactionResource($transaction);
    }
}
