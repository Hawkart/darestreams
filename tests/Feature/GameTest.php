<?php

namespace Tests\Feature;

use App\Models\Game;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_list() {

        $games = factory(Game::class, 3)->create()->map(function ($game) {
            return $game->only(['id', 'title', 'popularity']);
        });

        $response = $this->getJson('/api/games?sort=id')
            ->assertStatus(200);

        $data = $response->json()['data'];

        $this->assertEquals(count($games), count($data));
        $this->assertEquals($games[0]['title'], $data[0]['title']);
    }

    /** @test */
    public function failed_view_list_wrong_sort_or_include() {

        $this->getJson('/api/games?sort=sid')
            ->assertStatus(400);

        $this->getJson('/api/games?include=sid')
            ->assertStatus(400);
    }

    /** @test */
    public function can_view_detail() {

        $game = factory(Game::class)->create();

        $this->getJson('/api/games/'.$game->id)
            ->assertStatus(200)
            ->assertJsonFragment(
                ['title' => $game->title]
            );
    }

    /** @test */
    /*public function view_top() {

        $games = factory(Game::class, 5)->create();

        $this->getJson('/api/games/top')
            ->assertStatus(200)
            ->assertJsonFragment(
                ['title' => $games[0]->title]
            );
    }*/
}