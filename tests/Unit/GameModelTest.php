<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function game_has_many_streams()
    {
        $users = factory(User::class, 5)->create();
        $games = factory(Game::class, 5)->create();
        $channel = factory(Channel::class)->create();
        $streams = factory(Stream::class, 10)->create();

        $game = $games[0];
        $game->streams()->save($streams[1]);
        $game->streams()->save($streams[3]);
        $game->save();

        $this->assertDatabaseHas('streams', [
            'game_id'    => $game->id,
            'id' => $streams[1]->id
        ]);

        $this->assertDatabaseHas('streams', [
            'game_id'    => $game->id,
            'id' => $streams[3]->id
        ]);
    }

    /** @test */
    public function game_has_many_channels()
    {
        $users = factory(User::class, 5)->create();
        $games = factory(Game::class, 5)->create();

        $channels = [];
        foreach($users as $user)
        {
            $channels[] = factory(Channel::class)->create(['user_id' => $user->id]);
        }

        $game = $games[0];
        $game->channels()->save($channels[1]);
        $game->channels()->save($channels[3]);
        $game->save();

        $this->assertDatabaseHas('channels', [
            'game_id'    => $game->id,
            'id' => $channels[1]->id
        ]);

        $this->assertDatabaseHas('channels', [
            'game_id'    => $game->id,
            'id' => $channels[3]->id
        ]);
    }
}