<?php

namespace Tests\Feature;

use App\Models\AdvCampaign;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdvCampaignTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function not_auth_user_cannot_create_it()
    {
        factory(User::class)->create([]);
        $campaign = factory(AdvCampaign::class)->make();

        $this->json('POST', '/api/campaigns', $campaign->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_not_advertiser_cannot_create_campaign()
    {
        $roles = [2, 3];    //user, streamer

        foreach($roles as $role_id)
        {
            $user = factory(User::class)->create(['role_id' => $role_id]);
            $token = auth()->login($user);

            $campaign = factory(AdvCampaign::class)->make();

            $this->storeAssertFieldFailed($campaign->toArray(), $token, 'title', 403, false);

            auth()->logout();
        }
    }

    /** @test */
    public function auth_user_create_but_requires_fields_not_filled()
    {
        $user = factory(User::class)->create(['role_id' => 4]);
        $token = auth()->login($user);

        $fields = ['from', 'to', 'title', 'brand', 'limit'];

        foreach($fields as $field)
        {
            $data = factory(AdvCampaign::class)->make()->toArray();

            $data[$field] = '';
            $this->storeAssertFieldFailed($data, $token, $field);

            unset($data[$field]);
            $this->storeAssertFieldFailed($data, $token, $field);
        }
    }

    /** @test */
    public function auth_user_create_but_wrong_dates()
    {
        $user = factory(User::class)->create(['role_id' => 4]);
        $token = auth()->login($user);

        $fields = ['from', 'to', 'title', 'brand', 'limit'];

        $data = factory(AdvCampaign::class)->make()->toArray();
        $data['from'] = Carbon::now('UTC')->subMinutes(45)->toDateTimeString();
        $this->storeAssertFieldFailed($data, $token, 'from');

        $data = factory(AdvCampaign::class)->make()->toArray();
        $data['to'] = Carbon::now('UTC')->subMinutes(45)->toDateTimeString();
        $this->storeAssertFieldFailed($data, $token, 'to');

        $data = factory(AdvCampaign::class)->make()->toArray();
        $data['from'] = Carbon::now('UTC')->addMinutes(45)->toDateTimeString();
        $data['to'] = Carbon::now('UTC')->addMinutes(25)->toDateTimeString();
        $this->storeAssertFieldFailed($data, $token, 'from');
    }

    /** @test */
    public function auth_user_create_not_enough_money()
    {
        $user = factory(User::class)->create(['role_id' => 4]);
        $user->account->update(['amount' => 50]);
        $token = auth()->login($user);

        $data = [
            'title' => "Updated stream",
            'brand' => 'Brand',
            'limit' => 100,
            'from' => Carbon::now('UTC')->addMinutes(45)->toDateTimeString(),
            'to' => Carbon::now('UTC')->addMinutes(245)->toDateTimeString(),
        ];

        $this->storeAssertFieldFailed($data, $token, 'limit');
    }

    /** @test */
    public function auth_user_create_successfully()
    {
        $user = factory(User::class)->create(['role_id' => 4]);
        $user->account->update(['amount' => 200]);
        $token = auth()->login($user);

        $data = [
            'title' => "Updated stream",
            'brand' => 'Brand',
            'limit' => 100,
            'from' => Carbon::now('UTC')->addMinutes(45)->toDateTimeString(),
            'to' => Carbon::now('UTC')->addMinutes(245)->toDateTimeString(),
        ];

        $this->json('POST', '/api/campaigns', $data,  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('adv_campaigns', [
            'user_id' => $user->id,
            'title' => $data['title'],
        ]);
    }

    /** @test */
    public function auth_user_role_user_cannot_watch_list_it()
    {
        $user = factory(User::class)->create(['role_id' => 2]);
        $token = auth()->login($user);

        $this->json('GET', '/api/campaigns', [],  ['Authorization' => "Bearer $token"])
            ->assertStatus(403);
    }

    /** @test */
    public function auth_user_can_show_it()
    {
        $user = factory(User::class)->create(['role_id' => 1]);
        $token = auth()->login($user);

        $c = factory(AdvCampaign::class)->create(['user_id' => $user->id]);

        $this->json('GET', '/api/campaigns/'.$c->id, [],  ['Authorization' => "Bearer $token"])
            ->assertStatus(200);
    }

    /**
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     * @param bool $json_structure
     */
    public function storeAssertFieldFailed($data, $token, $fields, $status = 422, $json_structure = true)
    {
        $response = $this->json('POST', '/api/campaigns', $data, ['Authorization' => "Bearer $token"])
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
