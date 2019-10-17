<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::shouldUse('api');
        JsonResource::wrap('data');
    }

    protected function generateRoles()
    {
        $roles = User::$roleList;
        foreach($roles as $role)
        {
            factory(Role::class)->create([
                'name' => $role,
                'display_name' => $role
            ]);
        }
    }
}