<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Channel;
use App\Models\Game;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use App\Events\UserCreatedEvent;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_auth_user_by_token()
    {
        $headers = $this->getHeadersWithUserToken();

        $this->json('get', '/api/users/me', [], $headers)
            ->assertStatus(200);
    }

    /** @test */
    public function get_user_without_token()
    {
        $this->json('get', '/api/users/me')
            ->assertStatus(401);
    }

    /** @test */
    public function create_account_on_new_user()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        $this->assertDatabaseHas('accounts', ['user_id' => $user->id]);

        $account = Account::where('user_id', $user->id)->first();

        $this->getJson(url('/api/users/'.$user->id.'/account'), ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $account->id,
                    'user_id'  => $user->id,
                    'amount' => 0,
                    'currency' => 'USD'
                ]
            ]);
    }

    /** @test */
    public function create_and_get_user_has_channel()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id, 'game_id'=> $game->id, 'logo' => $game->logo]);

        $this->assertDatabaseHas('channels', ['user_id' => $user->id, 'id' => $channel->id]);

        $this->json('get', '/api/users/'.$user->id.'/channel')
            ->assertStatus(200)
            ->assertJsonFragment(
                ['title' => $channel->title]
            );
    }

    /** @test */
    public function get_user_donate_group_dates_correctly()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        $payload = [];

        $this->json('get', '/api/users/me/get-donates-by-date', $payload, ['Authorization' => "Bearer $token"])
           ->assertStatus(200);
    }

    /**
     * Login user and get header with token
     *
     * @return array
     */
    protected function getHeadersWithUserToken()
    {
        Event::fake();

        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        Event::assertDispatched(UserCreatedEvent::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        return ['Authorization' => "Bearer $token"];
    }
}
