<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\GetUserChannel;
use App\Models\User;
use App\Models\OAuthProvider;
use App\Http\Controllers\Controller;
use App\Exceptions\EmailTakenException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


/**
 * @group Auth
 */
class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect the user to the provider authentication page.
     *
     * {driver} - social provider: vkontakte,facebook, twitch. Example: twitch
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     *
     * @param  string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        $scopes = [];

        if($provider=='twitch')
            $scopes[] = 'channel_read';

        if($provider=='streamlabs')
            $scopes[] = 'alerts.create';

        return Socialite::driver($provider)->scopes($scopes)->stateless()->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * {driver} - social provider: facebook, twitch, youtube, steam, discord, streamlabs
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     *
     * @param  string $driver
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $userProvider = Socialite::driver($provider)->stateless()->user();
        $user = $this->findOrCreateUser($provider, $userProvider);

        if(!$user instanceof  User) return $user;

        if($provider=='twitch')
        {
            try {
                dispatch(new GetUserChannel($user, $userProvider->getId(), $provider));
            } catch (\Exception $e) {
                abort(403, 'Problem with social abstract user');
            }
        }

        $token = auth('api')->login($user);
        auth('api')->setToken($token)->user();
        $payload = auth('api')->payload();

        return response()->view('oauth.callback', [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $payload('exp')
        ], 200);
    }

    /**
     * @param $provider
     * @param $userProvider
     * @return User
     */
    public function findOrCreateUser($provider, $userProvider)
    {
        try {
            $oauthProvider = OAuthProvider::where('provider', $provider)
                ->where('provider_user_id', $userProvider->getId())
                ->first();
        } catch (\Exception $e) {
            abort(403, 'Problem with social abstract user');
        }

        if($provider=='streamlabs' && !$oauthProvider)
        {
            $json = json_decode($userProvider->json, true);
            if(!isset($json['twitch']))
            {
                abort(403, 'Sorry, You dont have twitch account in streamlabs.');
            }else{

                $user = User::whereHas('oauthProviders', function($q) use ($json) {
                    $q->where('provider', 'twitch')
                        ->where('provider_user_id', $json['twitch']['id']);
                })->first();
            }

            if(!$user)
                abort(403, 'Sorry, You dont have twitch account in streamlabs.');

            //connect social account
            try {
                $this->connect($user, $provider, $userProvider);
            } catch (\Exception $e) {
                abort(403, 'Some problem with creating social account. Please try again later.');
            }

            return $user;
        }


        if ($oauthProvider) {

            $user = $oauthProvider->user;

            if($user->fake)
            {
                $user->clearFakeData();
                $user = $user->updateThrowOauth($this->prepareData($userProvider));
            }

            $oauthProvider->update([
                'access_token' => $userProvider->token,
                'refresh_token' => $userProvider->refreshToken,
                'json' => isset($userProvider->json) ? $userProvider->json : []
            ]);

            return $user;
        }else{
            if(empty($userProvider->getEmail()))
                abort(403, 'Sorry, You cannot authorize without email');
        }

        if(!empty(auth('api')->user()))
        {
            $user = auth('api')->user();
        } else if (User::where('email', $userProvider->getEmail())->exists()) {
            //throw new EmailTakenException;
            $user = User::where('email', $userProvider->getEmail())->first();
        }else{
            try {
                $user = $this->createUser($userProvider);
            } catch (\Exception $e) {
                abort(403, 'An Error Occured, please retry later');
            }
        }

        //connect social account
        try {
            $this->connect($user, $provider, $userProvider);
        } catch (\Exception $e) {
            abort(403, 'Some problem with creating social account. Please try again later.');
        }

        return $user;
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return \App\Models\User
     */
    public function createUser($sUser)
    {
        $data = $this->prepareData($sUser);
        $user = DB::transaction(function () use ($data) {
            return User::create($data);
        });

        return $user;
    }

    /**
     * @param $sUser
     * @return array
     */
    protected function prepareData($sUser)
    {
        $data = [
            'name' => $sUser->getName(),
            'email' => $sUser->getEmail() ? $sUser->getEmail() : $this::generateEmail($sUser),
            'nickname' => $sUser->getNickname() ? $sUser->getNickname() : $sUser->getName(),
            'email_verified_at' => $sUser->getEmail() ? now() : null,
            'password' => bcrypt(Str::random(10)),
            'role_id' => null
        ];

        if(!empty($sUser->getAvatar()))
            $data['avatar'] = $sUser->getAvatar();

        return $data;
    }

    /**
     * @param $user
     * @param $provider
     * @param $sUser
     */
    protected function connect($user, $provider, $sUser)
    {
        DB::transaction(function () use ($user, $provider, $sUser) {
            $user->oauthProviders()->create([
                'provider' => $provider,
                'provider_user_id' => $sUser->getId(),
                'access_token' => $sUser->token,
                'refresh_token' => $sUser->refreshToken,
                'json' => isset($sUser->json) ? $sUser->json : []
            ]);
        });
    }

    /**
     * @param $providerUser
     * @return string
     */
    protected function generateEmail($providerUser)
    {
        $site = env('APP_URL', "darestreams.com");
        $site = str_replace(["http://", "https://"], "", $site);
        $name = $providerUser->getNickname() ? $providerUser->getNickname() : $providerUser->getName();
        $email = Str::slug($name)."@".$site;
        return $email;
    }
}