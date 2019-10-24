<?php

namespace Tests\Feature;

use App\Models\AdvCampaign;
use App\Models\AdvTask;
use App\Models\Channel;
use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdvTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function not_auth_user_cannot_create_it()
    {
        factory(User::class)->create();
        $campaign = factory(AdvCampaign::class)->create();
        $task = factory(AdvTask::class)->make(['campaign_id' => $campaign->id]);

        $this->json('POST', '/api/campaigns/'.$campaign->id."/tasks", $task->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_not_advertiser_cannot_create_it()
    {
        $user = factory(User::class)->create(['role_id' => 4]);     //user
        $campaign = factory(AdvCampaign::class)->create(['user_id'=>$user->id]);

        $roles = [2, 3];    //user, streamer

        foreach($roles as $role_id)
        {
            $user->update(['role_id' => $role_id]);
            $token = auth()->login($user);

            $task = factory(AdvTask::class)->make(['campaign_id' => $campaign->id]);

            $this->json('POST', '/api/campaigns/'.$campaign->id."/tasks", $task->toArray(), ['Authorization' => "Bearer $token"])
                ->assertStatus(403);

            auth()->logout();
        }
    }

    /** @test */
    public function auth_user_not_owner_cannot_create_it()
    {
        $owner = factory(User::class)->create(['role_id' => 4]);
        $campaign = factory(AdvCampaign::class)->create(['user_id'=>$owner->id]);

        $user = factory(User::class)->create(['role_id' => 4]);
        $token = auth()->login($user);

        $task = factory(AdvTask::class)->make(['campaign_id' => $campaign->id]);
        $url = '/api/campaigns/'.$campaign->id."/tasks";

        $this->storeAssertFieldFailed($url, $task->toArray(), $token, 'price');
    }

    /** @test */
    public function auth_user_create_but_requires_fields_not_filled()
    {
        $user = factory(User::class)->create(['role_id' => 4]);
        $campaign = factory(AdvCampaign::class)->create(['user_id'=>$user->id]);
        $token = auth()->login($user);

        $url = '/api/campaigns/'.$campaign->id."/tasks";
        $fields = ['small_desc','full_desc', 'limit', 'price', 'type', 'min_rating'];

        foreach($fields as $field)
        {
            $data = factory(AdvTask::class)->make(['campaign_id' => $campaign->id])->toArray();

            $data[$field] = '';
            $this->storeAssertFieldFailed($url, $data, $token, $field);

            unset($data[$field]);
            $this->storeAssertFieldFailed($url, $data, $token, $field);
        }
    }

    /** @test */
    public function auth_user_create_successfully()
    {
        $user = factory(User::class)->create(['role_id' => 4]);
        $campaign = factory(AdvCampaign::class)->create([
            'user_id'=>$user->id,
            'from' => Carbon::now('UTC')->addMinutes(45)->toDateTimeString(),
            'to' => Carbon::now('UTC')->addMinutes(245)->toDateTimeString()
        ]);
        $token = auth()->login($user);

        $url = '/api/campaigns/'.$campaign->id."/tasks";

        $data = [
            'campaign_id' => $campaign->id,
            'small_desc' => 'task',
            'full_desc' => 'full task',
            'limit' => 50,
            'type' => 1,
            'price' => 5,
            'min_rating' => 0
        ];

        $this->json('POST', $url, $data,  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('adv_tasks', [
            'campaign_id' => $campaign->id,
            'small_desc' => $data['small_desc'],
        ]);
    }

    /** @test */
    public function auth_user_role_user_cannot_view_all()
    {
        $user = factory(User::class)->create(['role_id' => 2]);
        $token = auth()->login($user);

        $this->json('GET', '/api/campaigns/all/tasks', [],  ['Authorization' => "Bearer $token"])
            ->assertStatus(403);
    }

    /** @test */
    public function auth_user_role_streamer_view_all()
    {
        $user = factory(User::class)->create(['role_id' => 3]);
        factory(Game::class)->create();
        factory(Channel::class)->create(['user_id' => $user->id]);
        $token = auth()->login($user);

        $this->json('GET', '/api/campaigns/all/tasks', [],  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);
    }

    /**
     * @param $url
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     * @param bool $json_structure
     */
    public function storeAssertFieldFailed($url, $data, $token, $fields, $status = 422, $json_structure = true)
    {
        $response = $this->json('POST', $url, $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status);

        if($json_structure)
            $response->assertJsonStructure([
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
    public function updateAssertFieldFailed($url, $data, $token, $fields, $status = 422, $json_structure = true)
    {
        $response = $this->json('PUT', $url, $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status);

        if($json_structure)
            $response->assertJsonStructure([
                'errors' => [$fields],
                'message'
            ]);
    }
}