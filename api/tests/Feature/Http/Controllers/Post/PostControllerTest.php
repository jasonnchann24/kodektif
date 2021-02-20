<?php

namespace Tests\Feature\Http\Controllers\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    /** @test */
    public function non_authenticated_user_can_not_access_these_post_apis()
    {

        $this->json('POST', '/api/posts', [])
            ->assertStatus(403);

        $this->json('PATCH', '/api/posts/-1', [])
            ->assertStatus(403);

        $this->json('DELETE', '/api/posts/-1')
            ->assertStatus(403);
    }

    /** @test */
    public function public_can_access_these_post_apis()
    {
        $this->json('GET', '/api/posts')
            ->assertStatus(200);

        $this->json('GET', '/api/posts/-1')
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_create_new_posts()
    {
        // $user = $this->createBasicUser();
        // $this->actingAs($user);

        // $user->json('POST', route('posts.store'))
    }
}
