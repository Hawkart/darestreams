<?php

namespace Tests\Feature;

use App\Models\AdvCampaign;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdvCampaignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generateRoles();
    }

    /** @test */
    public function not_auth_user_cannot_create_it()
    {
        factory(User::class)->create([]);
        $campaign = factory(AdvCampaign::class)->make();

        $this->json('POST', '/api/adv/campaigns', $campaign->toArray())
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

            $this->storeAssertFieldFailed($campaign->toArray(), $token, 'title');

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

    /**
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     * @param bool $json_structure
     */
    public function storeAssertFieldFailed($data, $token, $fields, $status = 422, $json_structure = true)
    {
        $response = $this->json('POST', '/api/adv/campaigns', $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status);

        if($json_structure)
            $response->assertJsonStructure([
                'errors' => [$fields],
                'message'
            ]);
    }
}
