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
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes([])
            ->jsonPaginate();

        return NotificationResource::collection($items);
    }

    /**
     * Get user's unread notifications.
     * @authenticated
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function unread(Request $request)
    {
        $query = auth()->user()->unreadNotifications()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes([])
            ->jsonPaginate();

        return NotificationResource::collection($items);
    }

    /**
     * Display user's notification.
     * @authenticated
     *
     * {notification} - notification id integer
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        if(!auth()->user()->notifications()->whereId($notification->id)->exist())
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
     * {notification} - notification id integer
     *
     * @param Notification $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function setRead(Notification $notification)
    {
        if(!auth()->user()->notifications()->where('id', $notification->id))
            return response()->json(['error' => trans('api/users/notification.not_belong_to_user')], 403);

        $notification->markAsRead();

        return response()->json([], 200);
    }

    /**
     * Set read all user's notifications.
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setReadAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json([], 200);
    }
}
