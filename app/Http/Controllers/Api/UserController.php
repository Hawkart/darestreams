<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Requests\TaskTransactionRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\ChannelResource;
use App\Models\Account;
use App\Models\Transaction;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Exceptions\VerifyEmailException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Storage;
use Image;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Cache;

/**
 * @group Users
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')
            ->only(['me', 'update', 'updateAvatar', 'updateOverlay', 'updatePassword', 'follow', 'unfollow', 'account', 'donate']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: tasks, streams, channel. Example: tasks,channel
     * @queryParam sort string Sort items by fields: nickname, id. For desc use '-' prefix. Example: -nickname
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(User::class)
            ->defaultSort('id')
            ->allowedIncludes(['tasks', 'streams', 'channel']) //'oauthProviders', 'account',
            ->allowedSorts('nickname', 'id')
            ->jsonPaginate();

        return UserResource::collection($items);
    }

    /**
     * Get authorized user.
     * @authenticated
     *
     * @queryParam include string String of connections: tasks, streams, channel, account. Example: tasks,channel
     *
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request)
    {
        $item = QueryBuilder::for(User::class)
            ->allowedIncludes(['tasks','streams', 'channel', 'account'])
            ->findOrFail(auth()->user()->id);

        return new UserResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: tasks, streams, channel. Example: tasks,channel
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $item = QueryBuilder::for(User::class)
            ->allowedIncludes(['tasks','streams', 'channel'])
            ->findOrFail($user);

        return new UserResource($item);
    }

    /**
     * Update user fields.
     *
     * @authenticated
     *
     * @bodyParam name string required User's first name. Example: Archibald
     * @bodyParam last_name string User's last name.
     * @bodyParam middle_name string User's middle name.
     * @bodyParam email string required User's email. Example: example@example.ru
     *
     * @param User $user
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        if ($user->id != auth()->user()->id)
            return setErrorAfterValidation(['id' => trans('api/user.failed_user_not_current')]);

        $allowedFields = ['name', 'last_name', 'middle_name'];
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail())
            $allowedFields[] = 'email';

        $user->update($request->only($allowedFields));

        UserResource::withoutWrapping();

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('api/user.successfully_updated')
        ]);
    }

    /**
     * Update user's avatar
     *
     * @authenticated
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(User $user, Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($user->id != auth()->user()->id)
            return setErrorAfterValidation(['id' => trans('api/user.failed_user_not_current')]);

        if($user->avatar)
        {
            $path = public_path() . '/storage/' . $user->avatar;
            if(file_exists($path))
                unlink($path);
        }

        $avatarName = $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();
        $request->avatar->storeAs('public/avatars', $avatarName);
        $user->avatar = "avatars/".$avatarName;
        $user->save();

        UserResource::withoutWrapping();

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('api/user.avatar_updated')
        ]);
    }

    /**
     * Update user's overlay.
     *
     * @authenticated
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOverlay(User $user, Request $request)
    {
        if ($user->id != auth()->user()->id)
            return response()->json(['error' => trans('api/user.failed_user_not_current')], 403);

        $request->validate([
            'overlay' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($user->overlay)
        {
            $path = public_path() . '/storage/' . $user->overlay;
            if(file_exists($path))
                unlink($path);
        }

        $avatarName = $user->id.'_overlay'.time().'.'.request()->overlay->getClientOriginalExtension();
        $request->overlay->storeAs('public/avatars', $avatarName);
        $user->overlay = "avatars/".$avatarName;
        $user->save();

        UserResource::withoutWrapping();

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('api/user.overlay_updated')
        ]);
    }

    /**
     * Update user's password.
     *
     * @authenticated
     *
     * @bodyParam password string required User's password. Example: jadfohasd092
     *
     * @param User $user
     * @param UserPasswordUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(User $user, UserPasswordUpdateRequest $request)
    {
        if ($user->id != auth()->user()->id)
            return setErrorAfterValidation(['id' => trans('api/user.failed_user_not_current')]);

        if($result = $user->update([
            'password' => bcrypt($request->get('password'))
        ]))
        {
            UserResource::withoutWrapping();

            return response()->json([
                'data' => new UserResource($user),
                'message' => trans('api/user.password_updated')
            ]);
        }

        return response()->json([
            'error' => 'Something wrong'
        ], 422);
    }

    /**
     * Follow the user.
     * {user} - user id you want follow for.
     *
     * @authenticated
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(User $user)
    {
        if ($user->id == auth()->user()->id)
            return setErrorAfterValidation(['id' => trans('api/user.failed_user_cannot_follow_to_yourself')]);

        if(auth()->user()->isFollowing($user))
            return setErrorAfterValidation(['id' => trans('api/user.already_following')]);

        $user->followers()->attach(auth()->user()->id);

        return response()->json([
            'success' => true,
            'message'=> trans('api/user.success_new_following')
        ], 200);
    }

    /**
     * Unfollow the user.
     * {user} - user id you want unfollow.
     *
     * @authenticated
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow(User $user)
    {
        if ($user->id == auth()->user()->id)
            return setErrorAfterValidation(['id' => trans('api/user.failed_user_cannot_unfollow_to_yourself')]);

        if(!$user->isFollowedBy(auth()->user()))
           return setErrorAfterValidation(['id' => trans('api/user.failed_follow_user')]);

        $user->followers()->detach(auth()->user()->id);

        return response()->json([
            'success' => true,
            'message'=> trans('api/user.success_unfollow')
        ], 200);
    }

    /**
     * User's followers
     *
     * {user} - user id you want follow for.
     *
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function followers(User $user)
    {
        $items = QueryBuilder::for($user->followers()->getQuery())
            ->allowedIncludes(['tasks', 'streams'])
            ->jsonPaginate();

        return UserResource::collection($items);
    }

    /**
     * Users followings
     * {user} - user id integer.
     *
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function followings(User $user)
    {
        $items = QueryBuilder::for($user->followings()->getQuery())
            ->allowedIncludes(['tasks', 'streams'])
            ->jsonPaginate();

        return UserResource::collection($items);
    }

    /**
     * User's account
     * {user} - user id integer.
     *
     * @authenticated
     *
     * @param User $user
     * @return AccountResource
     */
    public function account(User $user)
    {
        if(auth()->user()->id!=$user->id)
            return setErrorAfterValidation(['id' => trans('api/user.failed_user_not_current')]);

        $query = $user->account()->getQuery();

        $item = QueryBuilder::for($query)
            ->allowedIncludes(['user', 'transactions'])
            ->firstOrFail();

        return new AccountResource($item);
    }

    /**
     * User's channel
     * {user} - user id integer.
     *
     * @param User $user
     * @return ChannelResource|\Illuminate\Http\JsonResponse
     */
    public function channel(User $user)
    {
        $query = $user->channel()->getQuery();

        $item = QueryBuilder::for($query)
            ->allowedIncludes(['user', 'streams', 'tags'])
            ->firstOrFail();

        return new ChannelResource($item);
    }


    /**
     * Get top donators
     *
     * @queryParam limit Integer. Limit of top channels. Default: 10.
     * @queryParam skip Integer. Offset of top channels. Default: 0.
     *
     * @queryParam include string String of connections: tasks, streams, channel. Example: tasks,channel
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     *
     */
    public function top(Request $request)
    {
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $skip = $request->has('skip') ? $request->get('skip') : 0;

        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $cache_key = Str::slug('topDonators'.$queryString);

        $tags = ['index', 'topDonators'];
        $cacheTags = Cache::tags($tags);

        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            //get streams finished amount donations for last 10 days
            $list = DB::table('transactions as tr')
                ->select('us.id as user_id', DB::raw("sum(tr.amount) as donates"))
                ->leftJoin('accounts as ac', 'ac.id', '=', 'tr.account_sender_id')
                ->rightJoin('users as us', 'us.id', '=', 'ac.user_id')
                ->groupBy('us.id')
                ->orderByDesc('donates')
                ->offset($skip)
                ->limit($limit)
                ->get();

            $data = $list->pluck('donates', 'user_id')->toArray();
            $ids = $list->pluck('user_id')->toArray();
            $oids = implode(',', $ids);

            $items = QueryBuilder::for(User::class)
                ->whereIn('id', $ids)
                ->orderByRaw(DB::raw("FIELD(id, $oids)"))
                ->allowedIncludes(['tasks', 'streams', 'channel'])
                ->jsonPaginate();

            foreach ($items as &$item)
                $item->donates = $data[$item->id];

            $cacheTags->put($cache_key, $items, 1800);
        }

        return UserResource::collection($items);
    }

    /**
     * Donate to user.
     *
     * @authenticated
     *
     * @param $userReceiver
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function donate(User $userReceiver, TaskTransactionRequest $request)
    {
        $user = auth()->user();
        $amount = $request->get('amount');

        //enough money
        if($amount <= $user->account->amount)
        {
            $data = [
                'amount' => $request->get('amount'),
                'account_sender_id' => $user->account->id,
                'account_receiver_id' => $userReceiver->account->id,
                'status' => TransactionStatus::Completed,
                'type' => TransactionType::Donation
            ];

            try {
                $transaction = DB::transaction(function () use ($data) {
                    return Transaction::create($data);
                });
            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }

            return response()->json([
                'success' => true,
                'message'=> trans('api/streams/tasks/transaction.success_created')
            ], 200);

        }else{
            abort(
                response()->json(['message' => trans('api/transaction.not_enough_money')], 402)
            );
        }
    }
}