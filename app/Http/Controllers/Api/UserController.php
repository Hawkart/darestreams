<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Exceptions\VerifyEmailException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Storage;
use Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['me']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(User::class)
            ->allowedIncludes(['tasks', 'streams']) //'oauthProviders', 'account',
            ->jsonPaginate();

        return UserResource::collection($items);
    }

    /**
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request)
    {
        $user = auth()->user();//$request->user();
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = QueryBuilder::for(User::class)
            ->allowedIncludes(['tasks','streams'])
            ->findOrFail($id);

        return new UserResource($item);
    }

    /**
     * @param $id
     * @param Request $request
     */
    public function update($id, UserRequest $request)
    {
        $user = auth()->user();

        if ($user->id != $id)
            return response()->json(['error' => trans('api/user.failed_user_not_current')], 403);

        $allowedFields = ['first_name', 'last_name', 'middle_name', 'nickname'];
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
     * Update user's avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function updateAvatar(Request $request)
    {
        $user = auth()->user();
        $params = $request->all();

        if($user->avatar)
        {
            $path = public_path() . '/storage/' . $user->avatar;

            if(file_exists($path) && !in_array($user->avatar, ['default/avatar_team.jpg', 'default/avatar_user.jpg', 'users/default.png']))
            {
                unlink($path);
            }
        }

        $path = Storage::disk('public')->putFile(
            'avatars', $request->file('files')
        );

        /**
         * Crop & resize using client crop data
         */

        if($request->has('toCropImgH'))
        {
            $crop = [
                'h' => (int)$params["toCropImgH"],
                'w' => (int)$params["toCropImgW"],
                'x' => (int)$params["toCropImgX"],
                'y' => (int)$params["toCropImgY"]
            ];
        }else{
            $crop = [
                'h' => (int)$params["h"],
                'w' => (int)$params["w"],
                'x' => (int)$params["x"],
                'y' => (int)$params["y"]
            ];
        }

        $img = Image::make('storage/'.$path);
        $img->crop($crop['h'], $crop['w'], $crop['x'], $crop['y']);
        $img->resize(120, 120);
        $img->save('storage/'.$path);
        $img->destroy();

        $user->avatar = $path;
        $user->update();

        UserResource::withoutWrapping();

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('api/user.avatar_updated')
        ]);
    }

    /**
     * Update overlay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updateOverlay(Request $request)
    {
        $user = auth()->user();
        if($user->overlay)
        {
            $path = public_path() . '/storage/' . $user->overlay;
            if(file_exists($path) && !in_array($user->overlay, ['default/overlay_game.jpg', 'default/overlay_team.jpg', 'default/overlay_user.jpg']))
                unlink($path);
        }

        $path = Storage::disk('public')->putFile(
            'avatars', $request->file('files')
        );
        $user->overlay = $path;
        $user->update();

        UserResource::withoutWrapping();

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('api/user.overlay_updated')
        ]);
    }

    /**
     * @param $id
     * @param UserPasswordUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword($id, UserPasswordUpdateRequest $request)
    {
        $user = auth()->user();

        if ($user->id != $id)
            return response()->json(['error' => trans('api/user.failed_user_not_current')], 403);

        if($result = $user->update([
            'password' => \Hash::make($request->get('password'))
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
}
