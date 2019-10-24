<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\Rating\Channel;

class InquireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function not_auth_user_create_it_from_brand_page()
    {
        Mail::fake();

        $data = [
            'title' => 'Balbla',
            'name' => 'BlaBla',
            'phone' => '8729837493',
            'email' => 'example@example.com'
        ];

        $this->json('POST', '/api/inquires', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('inquires', [
            'title' => $data['title'],
        ]);
    }

    /** @test */
    public function not_auth_user_create_it_from_rating_page()
    {
        Mail::fake();

        $channel = Channel::create([
            'provider' => 'twitch',
            'name' => 'example',
            'url' => 'https://twtitch.tv/example',
            'exid' => '12312',
            'lang' => 'ru',
            'followers' => 100,
            'views' => 100,
            'rating' => 10,
            'top' => 1,
            'json' => []
        ]);

        $data = [
            'name' => 'BlaBla',
            'email' => 'example@example.com',
            'channel_id' => $channel->id
        ];

        $this->json('POST', '/api/inquires', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('inquires', [
            'channel_id' => $channel->id
        ]);
    }
}