<?php

namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\OAuthProviderResource;
use App\Models\OAuthProvider;
use App\Models\User;

/**
 * @group Users oauth accounts
 */
class OAuthProviderController extends Controller
{
    /**
     * OAuthProviderController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/oauthprovider.you_cannot_read_oauth_of_this_user')], 403);

        $query = $user->oauthProviders()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes(['user'])
            ->jsonPaginate();

        return OAuthProviderResource::collection($items);
    }

    /**
     * @param User $user
     * @param OAuthProvider $oauthProvider
     * @return OAuthProviderResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user, OAuthProvider $oauthProvider)
    {
        if(auth()->user()->id!=$user->id)
            return response()->json(['error' => trans('api/users/oauthprovider.you_cannot_read_notification_of_this_user')], 403);

        if(!$user->oauthProviders()->whereId($oauthProvider->id)->exist())
            return response()->json(['error' => trans('api/users/oauthprovider.not_belong_to_user')], 403);

        $item = QueryBuilder::for(OAuthProvider::whereId($oauthProvider->id))
            ->allowedIncludes(['user'])
            ->first();

        return new OAuthProviderResource($item);
    }
}
