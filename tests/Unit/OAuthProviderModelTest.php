<?php

namespace Tests\Unit;

use App\Models\OAuthProvider;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OAuthProviderModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function oauth_provider_belongs_only_to_one_user()
    {
        $users = factory(User::class, 2)->create();
        $oauths = factory(OAuthProvider::class, 3)->create();

        $oauths[1]->user()->associate($users[1]);
        $oauths[1]->save();

        $this->assertDatabaseHas('oauth_providers', [
            'id' => $oauths[1]->id,
            'user_id'    => $users[1]->id,
        ]);

        $this->assertEquals($oauths[1]->user->id, $users[1]->id);
    }
}