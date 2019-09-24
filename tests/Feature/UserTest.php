<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
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

        $this->getJson(url('/api/users/'.$user->id.'/account'), ['Authorization' => 'Bearer ' . $token])
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
    Route::get('users/me', 'UserController@me'); - done
    Route::get('users/me/get-donates-by-date', 'UserController@getDonateGroupDates'); - done
    Route::get('users/me/get-donates-by-date/{date}/{stream}', 'UserController@getDonateGroupDatesByDateStream'); - done
    Route::get('users/me/get-donates-by-date/{date}', 'UserController@getDonateGroupDatesByDate'); - done

    Route::get('users/me/get-debit-withdraw-by-date', 'UserController@getDebitWithdrawGroupDates');
    Route::get('users/me/get-debit-withdraw-by-date/{date}', 'UserController@getDebitWithdrawGroupDatesByDate');

    Route::get('users/top', 'UserController@top');

    Route::apiResource('users', 'UserController')->only(['index', 'show', 'update']);
    Route::get('users/{user}/account', 'UserController@account');
    Route::get('users/{user}/channel', 'UserController@channel');
    Route::post('users/{user}/avatar', 'UserController@updateAvatar');
    Route::post('users/{user}/overlay', 'UserController@updateOverlay');
    Route::patch('users/{user}/password', 'UserController@updatePassword');
    Route::apiResource('users.oauthproviders', 'Users\OAuthProviderController')->only(['index', 'show']);

        //Followers
    Route::post('users/{user}/follow', 'UserController@follow');
    Route::patch('users/{user}/unfollow', 'UserController@unfollow');
    Route::get('users/{user}/followers', 'UserController@followers');
    Route::get('users/{user}/followings', 'UserController@followings');

        //Notifications
    Route::get('users/{user}/notifications/unread', 'Users\NotificationController@unread');
    Route::patch('users/{user}/notifications/set-read-all', 'Users\NotificationController@setReadAll');
    Route::apiResource('users.notifications', 'Users\NotificationController');
    Route::patch('users/{user}/notifications/{notification}/set-read', 'Users\NotificationController@setRead');
    */

}
