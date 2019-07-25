<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, User $user)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/notification.you_cannot_read_notification_of_this_user')], 403);

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
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/notification.you_cannot_read_notification_of_this_user')], 403);

        if(!$user->notifications()->whereId($notification->id)->exist())
            return response()->json(['error' => trans('api/users/notification.not_belong_to_user')], 403);

        $transaction = QueryBuilder::for(Notification::whereId($notification->id))
            ->allowedIncludes([])
            ->first();

        return new NotificationResource($transaction);
    }

    public function setRead($id)
    {

    }

    public function setReadAll($id)
    {

    }
}
