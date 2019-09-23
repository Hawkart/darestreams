<?php

namespace Tests\Unit;

use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function check_listener_on_create_needs_create_vote_for_user_and_socket_stream_and_add_participant()
    {
        Event::fake();

        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();

        $task = factory(Task::class)->create();

        Event::assertDispatched(TaskCreatedEvent::class, function ($e) use ($task) {
            return $e->task->id === $task->id;
        });
    }

    /** @test */
    public function check_listener_on_update_needs_socket_stream()
    {
        Event::fake();

        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();

        $task = factory(Task::class)->create();
        $task->update(['small_desc' => 'New title']);

        Event::assertDispatched(TaskUpdatedEvent::class, function ($e) use ($task) {
            return $e->task->id === $task->id;
        });
    }

    /** @test */
    public function it_belongs_to_one_user()
    {
        $user = factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();

        $task = factory(Task::class)->create();
        $task->user()->associate($user);
        $task->save();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id
        ]);

        $this->assertEquals($task->user->id, $user->id);
    }

    /** @test */
    public function it_belongs_to_one_stream()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        $stream = factory(Stream::class)->create();

        $task = factory(Task::class)->create();
        $task->stream()->associate($stream);
        $task->save();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'stream_id' => $stream->id
        ]);

        $this->assertEquals($task->stream->id, $stream->id);
    }

    /** @test */
    public function it_has_many_votes()
    {
        $users = factory(User::class, 2)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();

        $task1 = factory(Task::class)->create(['user_id' => $users[0]->id]);
        $task2 = factory(Task::class)->create(['user_id' => $users[1]->id]);

        $this->assertDatabaseHas('votes', [
            'task_id' => $task1->id,
            'user_id' => $users[0]->id
        ]);

        $this->assertDatabaseHas('votes', [
            'task_id' => $task2->id,
            'user_id' => $users[1]->id
        ]);

        $this->assertEquals(count($task1->votes), 1);
    }

    /** @test */
    public function it_has_many_transactions()
    {
        $user = factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();

        $task = factory(Task::class)->create();
        $transactions = factory(Transaction::class, 2)->create(['task_id' => 0, 'account_sender_id' => $user->account->id]);

        foreach($transactions as $transaction)
        {
            $task->transactions()->save($transaction);
            $task->save();
        }

        $this->assertEquals($task->transactions()->count(), 2);
    }
}