<?php

namespace Tests\Feature;

use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_it_by_slug_and_id()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['slug' => "super"]);

        $r = new ChannelResource($channel);
        $d = json_decode(json_encode($r->toResponse(app('request'))->getData()), true);

        $this->json('get', '/api/channels/'.$channel->id)
            ->assertStatus(200)
            ->assertJsonFragment($d);

        $this->json('get', '/api/channels/'.$channel->slug)
            ->assertStatus(200)
            ->assertJsonFragment($d);
    }

    /** @test */
    public function not_auth_user_cannot_update()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $this->json('PUT', '/api/channels/'.$channel->id, [])
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_but_not_ower_cannot_update()
    {
        $user = factory(User::class)->create();
        $owner = factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $token = auth()->login($user);

        $this->json('PUT', '/api/channels/'.$channel->id, ['description' => 'New desc.'], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['id'],
                'message'
            ]);
    }

    /** @test */
    public function update_when_sometimes_game_id_empty_or_not_in_database()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create([
            'user_id' => $user->id,
            'game_id' => $game->id
        ]);

        $this->json('PUT', '/api/channels/'.$channel->id, ['game_id' => ''], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['game_id'],
                'message'
            ]);

        $this->json('PUT', '/api/channels/'.$channel->id, ['game_id' => 100], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['game_id'],
                'message'
            ]);
    }

    /** @test */
    public function update_when_sometimes_desc()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $this->json('PUT', '/api/channels/'.$channel->id, ['description' => ''], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['description'],
                'message'
            ]);
    }

    /** @test */
    public function update_when_sometimes_logo_empty_or_wrong_format()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $this->json('PUT', '/api/channels/'.$channel->id, ['logo' => ''], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['logo'],
                'message'
            ]);

        Storage::fake('channels');

        $file = UploadedFile::fake()->image('document.pdf');

        $this->json('PUT', '/api/channels/'.$channel->id, ['logo' => $file], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['logo'],
                'message'
            ]);
    }

    /** @test */
    public function update_successful()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id, 'game_id' => $game->id]);

        Storage::fake('channels');
        $file = UploadedFile::fake()->image('logo.jpg');

        $data = [
            'game_id' => $game->id,
            'description' => 'New description',
            'logo' => $file,
        ];

        $this->json('PUT', '/api/channels/'.$channel->id, $data, ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('channels', ['id'=> $channel->id , 'description' => $data['description']]);
    }

    /** @test */
    public function get_top()
    {
        $users = factory(User::class, 3)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();
        $streams = factory(Stream::class, 2)->create(['channel_id' => $channel->id]);

        $this->json('GET', '/api/channels/top', [])->assertStatus(200);
    }

    /** @test */
    public function show_streams_of_channel_by_slug_and_id()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['slug' => "super"]);
        $streams = factory(Stream::class, 2)->create(['channel_id' => $channel->id]);

        //$r = StreamResource::collection($streams);
        //$d = json_decode(json_encode($r->toResponse(app('request'))->getData()), true);

        $this->json('get', '/api/channels/'.$channel->id."/streams")
            ->assertStatus(200);

        $this->json('get', '/api/channels/'.$channel->slug."/streams")
            ->assertStatus(200);
    }
}
