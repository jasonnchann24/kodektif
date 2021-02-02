<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_redirects_to_github()
    {
        $response = $this->call('GET', '/login/github');

        $this->assertStringContainsString('github.com/login/oauth', $response->getTargetUrl());
    }
}
