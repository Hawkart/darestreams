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
            ->allowedIncludes(['accountSender', 'accountReceiver', 'task'])
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
        //->getQuery();
        //Todo: check transaction belogs to user

        $transaction = QueryBuilder::for(Transaction::whereId($transaction->id))
            ->allowedIncludes(['accountSender', 'accountReceiver', 'task'])
            ->first();

        return new TransactionResource($transaction);
    }
}

/*

<?php namespace App\Place;

use App\Place;
use App\Review;
use App\Http\Requests\AddReviewRequest;

class ReviewController {
    // place.review.store
    public function store(AddReviewRequest $request, Place $place, Review $review) {
        // fill any review-related data
        $review->fill($request->all());

        $review->person()->associate(\Auth::user());
        $review->place()->associate($place);

        $review->save();
    }
*/
