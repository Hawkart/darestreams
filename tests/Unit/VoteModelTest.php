<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class VoteModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_one_user()
    {
        Event::fake();

        $user = factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();
        $task = factory(Task::class)->create();

        $vote = factory(Vote::class)->create();
        $vote->user()->associate($user);
        $vote->save();

        $this->assertDatabaseHas('votes', [
            'id' => $vote->id,
            'user_id' => $user->id
        ]);

        $this->assertEquals($vote->user->id, $user->id);
    }

    /** @test */
    public function it_belongs_to_one_task()
    {
        Event::fake();

        $user = factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();
        $task = factory(Task::class)->create();

        $vote = factory(Vote::class)->create();
        $vote->task()->associate($task);
        $vote->save();

        $this->assertDatabaseHas('votes', [
            'id' => $vote->id,
            'task_id' => $task->id
        ]);

        $this->assertEquals($vote->task->id, $task->id);
    }
}