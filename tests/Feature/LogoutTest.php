<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogoutTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_try_to_logout()
    {
        $headers = $this->getHeadersWithUserToken();

        $this->json('get', '/api/users/me', [], $headers)
            ->assertStatus(200);

        $this->json('post', '/api/logout', [], $headers)
            ->assertStatus(200);

        $this->assertEquals(null, auth()->user());
    }

    /** @test */
    public function user_try_to_logout_without_token_because_already_logout_user()
    {
        $headers = $this->getHeadersWithUserToken();

        auth()->logout();

        $this->json('get', '/api/users/me', [], $headers)
            ->assertStatus(401);
    }

    /**
     * Login user and get header with token
     *
     * @return array
     */
    protected function getHeadersWithUserToken()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        return ['Authorization' => "Bearer $token"];
    }
}