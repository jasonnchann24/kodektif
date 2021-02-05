<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
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

    /**
     * @test
     */
    public function can_authenticate_using_github()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getEmail')
            ->andReturn('johnDoe@acme.com')
            ->shouldReceive('getName')
            ->andReturn('John Doe')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->andReturn($provider);

        $this->get('/login/github/callback')
            ->assertStatus(302);
    }
}
