<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    /** @test */
    public function test_console_finish_votes()
    {
        $this->artisan('votes:finish')
            ->assertExitCode(0);
    }
}