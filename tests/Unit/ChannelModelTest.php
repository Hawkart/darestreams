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

class ChannelModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_one_user()
    {
        $user = factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $channel->user()->associate($user);
        $channel->save();

        $this->assertDatabaseHas('channels', [
            'id' => $channel->id,
            'user_id' => $user->id
        ]);

        $this->assertEquals($channel->user->id, $user->id);
    }

    /** @test */
    public function it_belongs_to_one_game()
    {
        $user = factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $channel->game()->associate($game);
        $channel->save();

        $this->assertDatabaseHas('channels', [
            'id' => $channel->id,
            'game_id' => $game->id
        ]);

        $this->assertEquals($channel->game->id, $game->id);
    }

    /** @test */
    public function it_has_many_streams()
    {
        $user = factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $streams = factory(Stream::class, 4)->create();

        foreach($streams as $stream)
        {
            $channel->streams()->save($stream);
            $channel->save();
        }


        $this->assertDatabaseHas('streams', [
            'channel_id' => $channel->id
        ]);

        $this->assertEquals(count($channel->streams), count($streams));
    }
}