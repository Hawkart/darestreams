<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_redirects_to_twitch()
    {
        $response = $this->call('GET', '/api/oauth/twitch');

        $this->assertContains('id.twitch.tv/oauth2/authorize', $response->getTargetUrl());
    }

    /** @test */
    /*public function it_retrieves_twitch_request_and_creates_a_new_user()
    {
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn('Johny')
            ->shouldReceive('getEmail')
            ->andReturn('foo@bar.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        Socialite::shouldReceive('driver->fields->scopes->user')->andReturn($abstractUser);

        $this->browse(function ($browser) {
            $browser->visit('/api/oauth/twitch/callback');
        });

        $this->assertDatabaseHas('users', [
            'email' => 'foo@bar.com',
        ]);
    }*/
}