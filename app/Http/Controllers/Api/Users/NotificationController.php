<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Models\Notification;

/**
 * @group Users notifications
 */
class NotificationController extends Controller
{
    /**
     * NotificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get user's all notifications.
     * @authenticated
     *
     * {user} - user id integer
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, User $user)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/notification.you_cannot_read_notification_of_this_user')], 403);

        $query = $user->notifications()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes([])
            ->jsonPaginate();

        return NotificationResource::collection($items);
    }

    /**
     * Get user's unread notifications.
     * @authenticated
     *
     * {user} - user id integer
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function unread(Request $request, User $user)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/notification.you_cannot_read_notification_of_this_user')], 403);

        $query = $user->unreadNotifications()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes([])
            ->jsonPaginate();

        return NotificationResource::collection($items);
    }

    /**
     * Display user's notification.
     * @authenticated
     *
     * {user} - user id integer
     * {notification} - notification id integer
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

        $item = QueryBuilder::for(Notification::whereId($notification->id))
            ->allowedIncludes([])
            ->first();

        return new NotificationResource($item);
    }

    /**
     * Set read one user's notification.
     * @authenticated
     *
     * {user} - user id integer
     * {notification} - notification id integer
     *
     * @param User $user
     * @param Notification $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function setRead(User $user, Notification $notification)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/notification.you_cannot_read_notification_of_this_user')], 403);

        if(!$user->notifications()->where('id', $notification->id))
            return response()->json(['error' => trans('api/users/notification.not_belong_to_user')], 403);

        $notification->markAsRead();

        return response()->json([], 200);
    }

    /**
     * Set read all user's notifications.
     * @authenticated
     *
     * {user} - user id integer
     * {notification} - notification id integer
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function setReadAll(User $user)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/notification.you_cannot_read_notification_of_this_user')], 403);

        $user->unreadNotifications->markAsRead();
        return response()->json([], 200);
    }
}
