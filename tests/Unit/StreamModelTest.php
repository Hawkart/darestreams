<?php

namespace Tests\Unit;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Jobs\SyncStreamByTwitch;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Bus;

class StreamModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function check_listener_on_create_needs_create_chat_and_add_to_participants()
    {
        $user = factory(User::class)->create();
        factory(Game::class)->create();
        $this->actingAs($user);

        $channel = factory(Channel::class)->create();
        $stream = factory(Stream::class)->create();
        $stream->refresh();

        $thread = $stream->threads[0];
        $ids = $thread->participantsUserIds();

        $this->assertContains($user->id, $ids);
        $this->assertEquals($stream->threads()->count(), 1);
    }

    /** @test */
    public function check_listener_on_update_needs_if_status_finished_wait_pay_sync_stream_video_from_twitch()
    {
        Bus::fake();

        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Created]);
        $stream->refresh();

        $stream->update(['status' => StreamStatus::FinishedWaitPay]);

        Bus::assertDispatched(SyncStreamByTwitch::class, function ($job) use ($stream) {
            return $job->stream->id == $stream->id;
        });
    }

    /** @test */
    public function it_belongs_to_one_channel()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $stream = factory(Stream::class)->create();
        $stream->channel()->associate($channel);
        $stream->save();

        $this->assertDatabaseHas('streams', [
            'id' => $stream->id,
            'channel_id' => $channel->id
        ]);

        $this->assertEquals($stream->channel->id, $channel->id);
    }

    /** @test */
    public function it_belongs_to_one_user_through_channel()
    {
        $user = factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create();
        $stream->channel()->associate($channel);
        $stream->save();

        $this->assertEquals($stream->user->id, $user->id);
    }

    /** @test */
    public function it_belongs_to_one_game()
    {
        factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $stream = factory(Stream::class)->create();
        $stream->game()->associate($game);
        $stream->save();

        $this->assertDatabaseHas('streams', [
            'id' => $stream->id,
            'game_id' => $game->id
        ]);

        $this->assertEquals($stream->game->id, $game->id);
    }

    /** @test */
    public function it_has_many_tasks()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();

        $stream = factory(Stream::class)->create();
        $tasks = factory(Task::class, 3)->create();

        foreach($tasks as $task)
        {
            $stream->tasks()->save($task);
            $stream->save();
        }

        $this->assertEquals($stream->tasks()->count(), 3);
    }

    /** @test */
    public function it_has_many_completed_tasks()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();

        $stream = factory(Stream::class)->create();

        $task = factory(Task::class)->create(['status' => TaskStatus::VoteFinished]);
        $task1 = factory(Task::class)->create(['status' => TaskStatus::PayFinished]);
        $task2 = factory(Task::class)->create(['status' => TaskStatus::Active]);

        $stream->tasks()->save($task);
        $stream->tasks()->save($task1);
        $stream->tasks()->save($task2);
        $stream->save();

        $this->assertEquals($stream->tasksCompleted()->count(), 2);
    }
}