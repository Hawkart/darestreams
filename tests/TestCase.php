<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $guard = 'api';

    protected function setUp(): void
    {
        parent::setUp();

        JsonResource::wrap('data');
    }
}