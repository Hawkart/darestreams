<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\OAuthProvider;
use App\Http\Controllers\Controller;
use App\Exceptions\EmailTakenException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @group Auth
 */
class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        config([
            'services.github.redirect' => route('oauth.callback', 'github'),
        ]);
    }

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
        return [
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ];
    }

    /**
     * Obtain the user information from the provider.
     *
     * {driver} - social provider: facebook, twitch, youtube, steam, discord
     *
     * @param  string $driver
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $user = $this->findOrCreateUser($provider, $user);

        $this->guard()->setToken(
            $token = $this->guard()->login($user)
        );

        return view('oauth/callback', [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
        ]);
    }

    /**
     * @param  string $provider
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return \App\Models\User|false
     */
    protected function findOrCreateUser($provider, $user)
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $user->getId())
            ->first();

        if ($oauthProvider) {
            $oauthProvider->update([
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
            ]);

            return $oauthProvider->user;
        }

        if (User::where('email', $user->getEmail())->exists()) {
            throw new EmailTakenException;
        }

        return $this->createUser($provider, $user);
    }

    /**
     * @param  string $provider
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return \App\Models\User
     */
    protected function createUser($provider, $sUser)
    {
        $data = [
            'name' => $sUser->getName(),
            'email' => $sUser->getEmail(),
            'nickname' => $sUser->getNickname() ? $sUser->getNickname() : $sUser->getName(),
            'email_verified_at' => $sUser->getEmail() ? now() : null
        ];

        if(!empty($sUser->avatar))
            $data['avatar'] = $sUser->avatar;

        $user = User::create($data);

        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token' => $sUser->token,
            'refresh_token' => $sUser->refreshToken,
            'json' => $sUser->toArray()
        ]);

        return $user;
    }
}
