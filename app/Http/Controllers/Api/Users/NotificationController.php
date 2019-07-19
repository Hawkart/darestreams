<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        //Todo: check user is auth user
        //Todo: filter by all|unread

        $query = $user->notifications()->getQuery();
        //unreadNotifications

        $items = QueryBuilder::for($query)
            ->allowedIncludes([])
            ->jsonPaginate();

        return NotificationResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Notification $notification)
    {
        //->getQuery();
        //Todo: check transaction belogs to user

        $transaction = QueryBuilder::for(Notification::whereId($notification->id))
            ->allowedIncludes([])
            ->first();

        return new NotificationResource($transaction);
    }
}




$user = App\User::find(1);

foreach ($user->unreadNotifications as $notification) {
echo $notification->type;
}
