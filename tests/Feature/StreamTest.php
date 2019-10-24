<?php

namespace Tests\Feature;

use App\Enums\StreamStatus;
use App\Http\Resources\StreamResource;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StreamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function not_auth_user_cannot_create()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        $stream = factory(Stream::class)->make();

        $this->json('POST', '/api/streams', $stream->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_create_but_try_put_not_his_channel_should_failed()
    {
        $users = factory(User::class, 2)->create();
        factory(Game::class)->create();

        $channels = [];
        foreach($users as $user)
        {
            $channels[] = factory(Channel::class)->create(['user_id' => $user->id]);
        }

        $stream = factory(Stream::class)->make(['channel_id' => $channels[0]->id]);

        $token = auth()->login($users[1]);

        $this->storeAssertFieldFailed($stream->toArray(), $token, 'channel_id');
    }

    /** @test */
    public function try_create_but_channel_out_of_db_or_empty_failed()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $stream = factory(Stream::class)->make(['channel_id' => 10]);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'channel_id');

        $stream = factory(Stream::class)->make(['channel_id' => 0]);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'channel_id');

        $data = factory(Stream::class)->make()->toArray();
        unset($data['channel_id']);
        $this->storeAssertFieldFailed($data, $token, 'channel_id');
    }

    /** @test */
    public function try_create_but_channel_has_active_streams_should_failed()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        $channel = factory(Channel::class)->create();
        $streamActive = factory(Stream::class)->create(['status' => StreamStatus::Active, 'channel_id' => $channel->id]);

        $stream = factory(Stream::class)->make(['channel_id' => $channel->id]);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'channel_id');
    }

    /** @test */
    public function try_create_but_game_id_empty_or_not_in_database()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        factory(Channel::class)->create();

        $gamesValue = ['', 0, 100];
        foreach($gamesValue as $value)
        {
            $stream = factory(Stream::class)->make(['game_id' => $value]);
            $this->storeAssertFieldFailed($stream->toArray(), $token, 'game_id');
        }
    }

    /** @test */
    public function on_create_check_required_fields()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        factory(Channel::class)->create();

        $fields = ['channel_id', 'game_id', 'title', 'link', 'start_at'];

        foreach($fields as $field)
        {
            $stream = factory(Stream::class)->make([$field => '']);
            $this->storeAssertFieldFailed($stream->toArray(), $token, $field);

            $data = factory(Stream::class)->make([$field => ''])->toArray();
            unset($data[$field]);
            $this->storeAssertFieldFailed($data, $token, $field);
        }
    }

    /** @test */
    public function try_create_but_link_failed()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        factory(Channel::class)->create();

        $links = ['/sdf.tiu', 'sdfsd', ':/dsfd.tie'];
        foreach($links as $link)
        {
            $stream = factory(Stream::class)->make(['link' => $link]);
            $this->storeAssertFieldFailed($stream->toArray(), $token, 'link');
        }
    }

    /** @test */
    public function try_create_but_start_at_failed()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        factory(Channel::class)->create();

        $stream = factory(Stream::class)->make(['start_at' => '2015-05-12 22:10:00']);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'start_at');

        $this->expectException("Exception");
        $stream = factory(Stream::class)->make(['start_at' => '2013-57-13']);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'start_at');
    }

    /** @test */
    public function try_create_but_allow_stream_fields_create_failed()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        factory(Channel::class)->create();

        $stream = factory(Stream::class)->make([
            'allow_task_before_stream' => 0,
            'allow_task_when_stream' => 0
        ]);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'allow_task_before_stream');

        $data = factory(Stream::class)->make()->toArray();
        unset($data['allow_task_before_stream']);
        unset($data['allow_task_when_stream']);
        $this->storeAssertFieldFailed($data, $token, 'allow_task_before_stream');
    }

    /** @test */
    public function try_create_but_allow_stream_min_amount_failed()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        factory(Game::class)->create();
        factory(Channel::class)->create();

        $stream = factory(Stream::class)->make([
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => "",
            'min_amount_donate_task_before_stream' => -1,
        ]);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'min_amount_task_before_stream');

        $stream = factory(Stream::class)->make([
            'allow_task_when_stream' => 1,
            'min_amount_task_when_stream' => -1,
            'min_amount_donate_task_when_stream' => ""
        ]);
        $this->storeAssertFieldFailed($stream->toArray(), $token, 'min_amount_task_when_stream');
    }

    /** @test */
    public function auth_user_create_successfully()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->make([
            'channel_id' => $channel->id,
            'game_id' => $game->id,
            'title' => "First stream",
            'link' => "https://www.twitch.tv/darestreams_",
            'start_at' => Carbon::now('UTC')->addMinutes(15),
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 5,
            'min_amount_donate_task_before_stream' => 5
        ]);

        $this->json('POST', '/api/streams', $stream->toArray(), ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('streams', [
            'channel_id' => $channel->id,
            'title' => "First stream",
        ]);
    }


    /** @test */
    public function update_when_sometimes_fields()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Active, 'channel_id' => $channel->id]);
        $fields = ['game_id', 'title', 'link', 'start_at'];

        foreach($fields as $field)
        {
            $this->updateAssertFieldFailed('/api/streams/'.$stream->id, [$field => ''], $token, $field);
        }
    }

    /** @test */
    public function update_status_out_of_range_or_change_when_canceled_or_finished()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Canceled, 'channel_id' => $channel->id]);

        $statuses = [-1, 10, StreamStatus::Active];

        foreach($statuses as $status)
        {
            $this->updateAssertFieldFailed('/api/streams/'.$stream->id, ['status' => $status], $token, 'status');
        }
    }

    /** @test */
    public function user_update_stream_when_created_successfully()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Created, 'channel_id' => $channel->id]);

        $data = [
            'title' => "Updated stream",
            'link' => "https://www.twitch.tv/darestreams_",
            'start_at' => Carbon::now('UTC')->addMinutes(45),
        ];

        $this->json('PUT', '/api/streams/'.$stream->id, $data,  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('streams', [
            'id' => $stream->id,
            'title' => "Updated stream",
        ]);
    }

    /** @test */
    public function user_update_stream_when_active_successfully()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Active, 'channel_id' => $channel->id]);

        $data = [
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 5,
            'min_amount_donate_task_before_stream' => 5
        ];

        $this->json('PUT', '/api/streams/'.$stream->id, $data,  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('streams', [
            'id' => $stream->id,
            'min_amount_task_before_stream' => 5,
            'min_amount_donate_task_before_stream' => 5
        ]);
    }

    /** @test */
    public function user_update_stream_status_successfully()
    {
        $user = factory(User::class)->create();
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Active, 'channel_id' => $channel->id]);

        $this->json('PUT', '/api/streams/'.$stream->id, ['status' => StreamStatus::FinishedWaitPay],  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('streams', [
            'id' => $stream->id,
            'status' => StreamStatus::FinishedWaitPay
        ]);
    }

    /** @test */
    public function get_list_of_statuses()
    {
        $this->json('GET', '/api/streams/statuses')
            ->assertStatus(200);
    }

    /** @test */
    public function get_top()
    {
        $users = factory(User::class, 5)->create();
        factory(Game::class, 5)->create();

        foreach($users as $user)
            factory(Channel::class)->create(['user_id' => $user->id]);

        factory(Stream::class, 10)->create();

        $this->json('GET', '/api/streams/top')
            ->assertStatus(200);
    }

    /** @test */
    public function show_it_by_id()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create();
        $stream = factory(Stream::class)->create(['status' => StreamStatus::Active, 'channel_id' => $channel->id]);

        $stream->refresh();
        $r = new StreamResource($stream);
        $d = json_decode(json_encode($r->toResponse(app('request'))->getData()), true);
        $d = $d['data'];

        $this->json('get', '/api/streams/'.$stream->id)
            ->assertStatus(200)
            ->assertJsonFragment($d);
    }

    /**
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     */
    public function storeAssertFieldFailed($data, $token, $fields, $status = 422)
    {
        $this->json('POST', '/api/streams', $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status)
            ->assertJsonStructure([
                'errors' => [$fields],
                'message'
            ]);
    }

    /**
     * @param $url
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     */
    public function updateAssertFieldFailed($url, $data, $token, $fields, $status = 422)
    {
        $this->json('PUT', $url, $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status)
            ->assertJsonStructure([
                'errors' => [$fields],
                'message'
            ]);
    }
}
