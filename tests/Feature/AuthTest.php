<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\OAuthController;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use App\Jobs\GetUserChannel;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_twitch()
    {
        $response = $this->call('GET', '/api/oauth/twitch');
        $this->assertContains('id.twitch.tv/oauth2/authorize', $response->getTargetUrl());
    }

    /** @test */
    public function it_retrieves_twitch_request_and_creates_a_new_user_correctly()
    {
        Bus::fake();

        $abstractUser = $this->mockSocialiteUser();

        /*
        //Problems because of stateless
        $mockSocialite->shouldReceive('driver')
            ->with('twitch')
            ->andReturn($provider);

        $this->get('/api/oauth/twitch/callback');*/

        $this->initOauthHandler('twitch', $abstractUser);

        $this->assertDatabaseHas('oauth_providers', [
            'provider_user_id' => 1234,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'foo@bar.com',
        ]);

        $user = User::where('email', 'foo@bar.com')->first();

        $this->assertEquals($user->id, auth()->user()->id);

        Bus::assertDispatched(GetUserChannel::class, function ($job) {
            return $job->id == 1234;
        });
    }

    /**
     * @param $provider
     * @param $userProvider
     */
    public function initOauthHandler($provider, $userProvider)
    {
        $oauth = new OAuthController();
        $user = $oauth->findOrCreateUser($provider, $userProvider);

        dispatch(new GetUserChannel($user, $userProvider->getId(), $provider));

        auth()->setToken($token = auth()->login($user))->user();
    }


    /**
     * @param string $email
     * @return \Mockery\LegacyMockInterface|\Mockery\MockInterface|Laravel\Socialite\Two\User
     */
    public function mockSocialiteUser($email = 'foo@bar.com')
    {
        $mockSocialite = \Mockery::mock('Laravel\Socialite\Contracts\Factory');
        $this->app['Laravel\Socialite\Contracts\Factory'] = $mockSocialite;

        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(1234)
            ->shouldReceive('getName')
            ->andReturn('Johny')
            ->shouldReceive('getNickname')
            ->andReturn('Johny')
            ->shouldReceive('getEmail')
            ->andReturn($email)
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        $provider = \Mockery::mock('Laravel\Socialite\Contract\Provider');
        $provider->shouldReceive('user')
            ->andReturn($abstractUser);

        return $abstractUser;
    }
}