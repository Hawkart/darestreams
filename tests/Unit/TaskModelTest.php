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

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function check_listener_on_create_needs_create_vote_for_user_and_socket_stream_and_add_participant()
    {
        /*$user = factory(User::class)->create();
        factory(Game::class)->create();
        $this->actingAs($user);

        $channel = factory(Channel::class)->create();
        $stream = factory(Stream::class)->create();
        $stream->refresh();

        factory(Task::class)->create();

        $thread = $stream->threads[0];
        $ids = $thread->participantsUserIds();

        $this->assertContains($user->id, $ids);
        $this->assertEquals($stream->threads()->count(), 1);*/

        //test with event fake and without


        //check add vote
        //check socket if streamer creating task
        //check add to participants

        //With event fake and without
    }

    /** @test */
    public function check_listener_on_update_needs_socket_stream()
    {

    }

    /** @test */
    public function it_belongs_to_one_user()
    {

    }

    /** @test */
    public function it_belongs_to_one_stream()
    {

    }

    /** @test */
    public function it_has_many_votes()
    {

    }

    /** @test */
    public function it_has_many_transactions()
    {

    }
}