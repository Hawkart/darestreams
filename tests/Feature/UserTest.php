<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Resources\TransactionResource;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\UserCreatedEvent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_auth_user_by_token()
    {
        Event::fake();

        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        Event::assertDispatched(UserCreatedEvent::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        $this->json('get', '/api/users/me', [], ['Authorization' => "Bearer $token"])
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

        $this->getJson(url('/api/users/me/account'), ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->account->id,
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

    /** @test */
    public function get_user_donate_group_dates__by_date_correctly()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        $payload = [];
        $date = '2019-11-16';

        $this->json('get', '/api/users/me/get-donates-by-date/'.$date, $payload, ['Authorization' => "Bearer $token"])
            ->assertStatus(200);
    }

    /** @test */
    public function get_user_donate_group_dates__by_date_and_stream_correctly()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);
        $stream = factory(Stream::class)->create();
        $task  = factory(Task::class)->create(['stream_id' => $stream->id]);
        $transaction =  factory(Transaction::class)->create(['account_sender_id' => $user->account->id, 'task_id' => $task->id, 'amount' => 0]);

        $payload = [];
        $date = '2019-11-16';

        $this->json('get', '/api/users/me/get-donates-by-date/'.$date."/".$stream->id, $payload, ['Authorization' => "Bearer $token"])
            ->assertStatus(200);
    }

    /** @test */
    public function get_debit_withdraw_group_dates_check_deposit()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);
        $user->account->update(['amount' => 1000]);

        $date = date('Y-m-d');
        factory(Transaction::class)->create([
            'account_sender_id' => null,
            'account_receiver_id' => $user->account->id,
            'task_id' => 0,
            'amount' => 100,
            'status' => TransactionStatus::Completed,
            'type' => TransactionType::Deposit
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $user->account->id,
            'amount' => 1100
        ]);

        $this->json('get', '/api/users/me/get-debit-withdraw-by-date', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment([
                [
                    "day" => $date,
                    "deposit" => "100",
                    "withdraw" => null
                ]
            ]);
    }

    /** @test */
    public function get_debit_withdraw_by_date_check_deposit()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);
        $user->account->update(['amount' => 1000]);

        $date = date('Y-m-d');
        $transaction = factory(Transaction::class)->create([
            'account_sender_id' => null,
            'account_receiver_id' => $user->account->id,
            'task_id' => 0,
            'amount' => 100,
            'status' => TransactionStatus::Completed,
            'type' => TransactionType::Deposit
        ]);

        $transaction->refresh();
        $r = new TransactionResource($transaction);
        $d = json_decode(json_encode($r->toResponse(app('request'))->getData()), true);
        unset($d['data']['account_receiver']);

        $this->json('get', '/api/users/me/get-debit-withdraw-by-date/'.$date, [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment([
                'data' => [ $d['data'] ]
            ]);
    }

    /** @test */
    public function get_debit_withdraw_groups_date_check_withdraw()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);
        $user->account->update(['amount' => 1000]);

        $date = date('Y-m-d');

        factory(Transaction::class)->create([
            'account_sender_id' => $user->account->id,
            'account_receiver_id' => null,
            'task_id' => 0,
            'amount' => 100,
            'status' => TransactionStatus::Completed,
            'type' => TransactionType::Withdraw
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $user->account->id,
            'amount' => 900
        ]);

        $this->json('get', '/api/users/me/get-debit-withdraw-by-date', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    "day" => $date,
                    "deposit" => null,
                    "withdraw" =>  "100"
                ]
            );
    }

    /** @test */
    public function get_debit_withdraw_by_date_check_withdraw()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);
        $user->account->update(['amount' => 1000]);

        $date = date('Y-m-d');

        $transaction = factory(Transaction::class)->create([
            'account_sender_id' => $user->account->id,
            'account_receiver_id' => null,
            'task_id' => 0,
            'amount' => 100,
            'status' => TransactionStatus::Completed,
            'type' => TransactionType::Withdraw
        ]);

        $transaction->refresh();
        $r = new TransactionResource($transaction);
        $d = json_decode(json_encode($r->toResponse(app('request'))->getData()), true);
        unset($d['data']['account_sender']);

        $this->json('get', '/api/users/me/get-debit-withdraw-by-date/'.$date, [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment([
                'data' => [ $d['data'] ]
            ]);
    }

    /** @test */
    public function get_debit_withdraw_by_date_check_withdraw_exceptions_amount_out_of_range()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        auth()->login($user);
        $user->account->update(['amount' => 1000]);

        //db exception if withdraw more than amount
        $this->expectException("Exception");

        factory(Transaction::class)->create([
            'account_sender_id' => $user->account->id,
            'account_receiver_id' => null,
            'task_id' => 0,
            'amount' => 1100,
            'status' => TransactionStatus::Completed,
            'type' => TransactionType::Withdraw
        ]);
    }

    /** @test */
    public function test_avatar_upload()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = auth()->login($user);

        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', '/api/users/me/avatar', ['avatar' => $file], ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $user->refresh();

        // Assert the file was stored...
        //Todo: check storing image in folder
        //Storage::disk('avatars')->assertExists(str_replace('avatars/', '', $user->avatar));
    }

    /** @test */
    public function get_top()
    {
        $users = factory(User::class, 5)->create();
        $this->json('GET', '/api/users/top', [])
            ->assertStatus(200);
    }

    /** @test */
    public function a_update_sometimes_fields()
    {
        $user = factory(User::class)->create(['email_verified_at' => Carbon::now()]);
        $token = auth()->login($user);

        $fields = ['name', 'last_name', 'first_name'];

        foreach($fields as $field)
        {
            $this->json('PUT', '/api/users/'.$user->id, [$field => ''], ['Authorization' => "Bearer $token"])
                ->assertStatus(422)
                ->assertJsonStructure([
                    'errors' => [$field],
                    'message'
                ]);
        }
    }

    /** @test */
    public function a_update_when_email_not_verified()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $token = auth()->login($user);

        $this->json('PUT', '/api/users/'.$user->id, ['email' => ''], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['email'],
                'message'
            ]);
    }

    /** @test */
    public function test_update()
    {
        $user = factory(User::class)->create(['email_verified_at' => Carbon::now()]);
        $token = auth()->login($user);

        $this->json('PUT', '/api/users/'.$user->id, ['name' => 'Archibald'], ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('users',['id'=> $user->id , 'name' => 'Archibald']);
    }

    /** @test */
    public function test_show()
    {
        $user = factory(User::class)->create(['name' => 'James']);

        $this->json('GET', '/api/users/'.$user->id)
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'id' => $user->id,
                    'name' => $user->name
                ]
            );
    }

    /** @test */
    public function test_follow()
    {
        $userA = factory(User::class)->create(['name' => 'James']);
        $token = auth()->login($userA);

        $userB = factory(User::class)->create(['name' => 'John']);

        $this->json('POST', '/api/users/'.$userB->id.'/follow', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment(['success' => true]);

        $this->json('GET', '/api/users/'.$userB->id.'/is-following', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment(['data' => true]);

        $this->assertTrue($userB->isFollowedBy($userA));
    }

    /** @test */
    public function test_unfollow()
    {
        $userA = factory(User::class)->create(['name' => 'James']);
        $token = auth()->login($userA);

        $userB = factory(User::class)->create(['name' => 'John']);

        $userB->followers()->attach($userA);

        $this->json('PATCH', '/api/users/'.$userB->id.'/unfollow', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonFragment(['success' => true]);

        $this->assertFalse($userB->isFollowedBy($userA));
    }

    /** @test */
    public function test_unfollow_failed_not_follow()
    {
        $userA = factory(User::class)->create(['name' => 'James']);
        $token = auth()->login($userA);

        $userB = factory(User::class)->create(['name' => 'John']);

        $this->json('PATCH', '/api/users/'.$userB->id.'/unfollow', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['id'],
                'message'
            ]);
    }

    /** @test */
    public function test_followers()
    {
        $userA = factory(User::class)->create(['name' => 'James']);
        $userB = factory(User::class)->create(['name' => 'John']);

        $userB->followers()->attach($userA);
        $token = auth()->login($userB);

        $this->json('GET', '/api/users/'.$userA->id.'/followers', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200);
    }

    /** @test */
    public function test_followings()
    {
        $userA = factory(User::class)->create(['name' => 'James']);
        $token = auth()->login($userA);
        $userB = factory(User::class)->create(['name' => 'John']);
        $userB->followers()->attach($userA);

        $this->json('GET', '/api/users/'.$userB->id.'/followings', [], ['Authorization' => "Bearer $token"])
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

    /*
    Route::post('users/me/avatar', 'UserController@updateAvatar'); - not finished
    Route::post('users/me/overlay', 'UserController@updateOverlay');

    //Route::patch('users/me/password', 'UserController@updatePassword');
    //oauthproviders - not done
    //Followers - done
    //Notifications - not done
    */

}
