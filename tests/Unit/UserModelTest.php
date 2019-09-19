<?php

namespace Tests\Unit;

use App\Events\TaskCreatedEvent;
use App\Events\UserCreatedEvent;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Game;
use App\Models\OAuthProvider;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function check_account_create_on_user_create()
    {
        $user = factory(User::class)->create();
        $user->refresh();

        $this->assertDatabaseHas('accounts', ['user_id' => $user->id]);
    }

    /** @test */
    public function user_has_only_one_account()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $account = factory(Account::class)->create(['user_id' => $user->id]);

        Event::assertDispatched(UserCreatedEvent::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        $this->assertDatabaseHas('accounts', ['user_id' => $user->id]);
        $this->assertEquals($user->account->id, $account->id);
    }

    /** @test */
    public function user_has_many_oauth_providers_different()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $user2 = factory(User::class)->create(['email' => 'user2@test.com']);
        $oauth = factory(OAuthProvider::class)->create(['provider' => 'twitch', 'provider_user_id' => 1]);
        $oauth2 = factory(OAuthProvider::class)->create(['provider' => 'twitch', 'provider_user_id' => 2]);
        $oauth3 = factory(OAuthProvider::class)->create(['provider' => 'facebook', 'provider_user_id' => 122]);

        $user->oauthProviders()->save($oauth);
        $user->oauthProviders()->save($oauth2);
        $user2->oauthProviders()->save($oauth3);

        $this->assertDatabaseHas('oauth_providers', [
            'user_id'    => $user->id,
            'provider_user_id' => $oauth->provider_user_id
        ]);

        $this->assertDatabaseHas('oauth_providers', [
            'user_id'    => $user->id,
            'provider_user_id' => $oauth2->provider_user_id
        ]);

        $this->assertDatabaseHas('oauth_providers', [
            'user_id'    => $user2->id,
            'provider_user_id' => $oauth3->provider_user_id
        ]);

        $count = OAuthProvider::where('user_id', $user->id)->count();
        $this->assertEquals($user->oauthProviders()->count(), $count);
    }

    /** @test */
    public function user_has_many_oauth_providers_not_unique_should_be_db_exception()
    {
        $user = factory(User::class)->create();
        $data = ['user_id' => $user->id, 'provider' => 'twitch', 'provider_user_id' => 1];

        factory(OAuthProvider::class)->create($data);

        $this->expectException("Exception");

        //not unique
        factory(OAuthProvider::class)->create($data);
    }

    /** @test */
    public function user_has_only_one_channel()
    {
        $user = factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('channels', ['user_id' => $user->id]);
        $this->assertEquals($user->channel->id, $channel->id);
    }

    /** @test */
    public function user_has_many_streams()
    {
        $users = factory(User::class, 5)->create();
        $game = factory(Game::class)->create();

        $user =  $users[0];
        $user2 =  $users[1];

        $channel = factory(Channel::class)->create(['user_id' => $user->id]);
        $channel2 = factory(Channel::class)->create(['user_id' => $user2->id]);

        $streams = factory(Stream::class, 5)->create(['channel_id' => $channel->id]);
        $streams2 = factory(Stream::class, 2)->create(['channel_id' => $channel2->id]);

        //check streams belongs user's channel
        $this->assertDatabaseHas('streams', ['channel_id' => $channel->id]);
        $this->assertDatabaseHas('streams', ['channel_id' => $channel2->id]);

        //check count user streams and streams created for user
        $this->assertEquals($user->streams()->count(), count($streams));
        $this->assertEquals($user2->streams()->count(), count($streams2));
    }


    /** @test */
    public function user_has_many_tasks()
    {
        $users = factory(User::class, 2)->create();
        $game = factory(Game::class)->create();

        foreach($users as $user)
        {
            factory(Channel::class)->create(['user_id' => $user->id]);
        }

        $streams = factory(Stream::class, 3)->create();
        $tasks = factory(Task::class, 4)->create();

        $users[0]->tasks()->save($tasks[1]);
        $users[0]->tasks()->save($tasks[3]);

        $this->assertDatabaseHas('tasks', [
            'user_id'    => $users[0]->id,
            'id' => $tasks[1]->id
        ]);

        $this->assertDatabaseHas('tasks', [
            'user_id'    => $users[0]->id,
            'id' => $tasks[3]->id
        ]);
    }

    /** @test */
    public function user_has_many_votes()
    {
        $user = factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create(['user_id' => $user->id]);
        factory(Stream::class, 2)->create();

        Event::fake();

        $task = factory(Task::class)->create(['user_id' => $user->id]);
        $user->tasks()->save($task);

        Event::assertDispatched(TaskCreatedEvent::class, function ($e) use ($task) {
            return $e->task->id === $task->id;
        });

        factory(Vote::class)->create(['task_id' => $task->id, 'user_id' => $user->id]);

        $this->assertDatabaseHas('votes', [
            'user_id'    => $user->id,
            'task_id' => $task->id
        ]);

        //db exception if not unique
        $this->expectException("Exception");
        factory(Vote::class)->create(['task_id' => $task->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function user_get_transactions_by_type()
    {
        Event::fake();

        $user = factory(User::class)->create(['email' => 'user1@test.com']);
        $user2 = factory(User::class)->create(['email' => 'user3@test.com']);

        factory(Account::class)->create(['user_id' => $user->id]);
        factory(Account::class)->create(['user_id' => $user2->id]);

        Event::assertDispatched(UserCreatedEvent::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        factory(Game::class)->create();
        factory(Channel::class)->create(['user_id' => $user2->id]);
        factory(Stream::class, 2)->create(['channel_id' => $user2->channel->id]);
        $task = factory(Task::class)->create();

        $transactions = factory(Transaction::class, 2)->create([
            'task_id' => $task->id,
            'account_sender_id' => $user->account->id,
            'account_receiver_id' => $user2->account->id,
        ]);

        $this->assertEquals($user->getTransactions('sent')->count(), count($transactions));
        $this->assertEquals($user2->getTransactions('received')->count(), count($transactions));
    }
}