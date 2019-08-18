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

/**
 * @group Auth
 */
class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect the user to the provider authentication page.
     *
     * {driver} - social provider: facebook, twitch, youtube, steam, discord
     *
     * @param  string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        $scopes = [];

        if($provider=='twitch')
            $scopes[] = 'channel_read';

        return Socialite::driver($provider)->scopes($scopes)->stateless()->redirect();

        /*return [
            'url' => Socialite::driver($provider)->scopes($scopes)->stateless()->redirect()->getTargetUrl(),
        ];*/
    }

    /**
     * Obtain the user information from the provider.
     *
     * {driver} - social provider: facebook, twitch, youtube, steam, discord, streamlabs
     *
     * @param  string $driver
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $userProvider = Socialite::driver($provider)->stateless()->user();
        $user = $this->findOrCreateUser($provider, $userProvider);

        dispatch(new GetUserChannel($user, $userProvider->getId(), $provider));

        $this->guard()->setToken(
            $token = $this->guard()->login($user)
        );

        return response()->view('oauth.callback', [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->getPayload()->get('exp') - time()
        ], 200);//->header('X-Frame-Options', "ALLOW-FROM https://darestreams.com");
    }

    /**
     * @param $provider
     * @param $userProvider
     * @return User
     */
    protected function findOrCreateUser($provider, $userProvider)
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $userProvider->getId())
            ->first();

        if ($oauthProvider) {
            $oauthProvider->update([
                'access_token' => $userProvider->token,
                'refresh_token' => $userProvider->refreshToken,
                'json' => isset($userProvider->json) ? $userProvider->json : []
            ]);

            return $oauthProvider->user;
        }

        if(!empty(auth()->user()))
        {
            $user = auth()->user();
        } else if (User::where('email', $userProvider->getEmail())->exists()) {
            //throw new EmailTakenException;
            $user = User::where('email', $userProvider->getEmail())->first();
        }else{
            $user = $this->createUser($userProvider);
        }

        //connect social account
        $this->connect($user, $provider, $userProvider);

        return $user;
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return \App\Models\User
     */
    protected function createUser($sUser)
    {
        $data = [
            'name' => $sUser->getName(),
            'email' => $sUser->getEmail() ? $sUser->getEmail() : $this::generateEmail($sUser),
            'nickname' => $sUser->getNickname() ? $sUser->getNickname() : $sUser->getName(),
            'email_verified_at' => $sUser->getEmail() ? now() : null,
            'password' => bcrypt(Str::random(10))
        ];

        if(!empty($sUser->getAvatar()))
            $data['avatar'] = $sUser->getAvatar();

        $user = User::create($data);

        return $user;
    }

    /**
     * @param $user
     * @param $provider
     * @param $sUser
     */
    protected function connect($user, $provider, $sUser)
    {
        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token' => $sUser->token,
            'refresh_token' => $sUser->refreshToken,
            'json' => isset($sUser->json) ? $sUser->json : []
        ]);
    }

    /**
     * @param $providerUser
     * @return string
     */
    protected function generateEmail($providerUser)
    {
        $site = env('APP_URL', "api.darestreams.com");
        $site = str_replace(["http://", "https://"], "", $site);
        $name = $providerUser->getNickname() ? $providerUser->getNickname() : $providerUser->getName();
        $email = Str::slug($name)."@".$site;
        return $email;
    }
}
