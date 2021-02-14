<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    /** @test */
    public function non_admin_user_can_not_access_store_update_category_apis()
    {

        $user = $this->createBasicUser();
        $category = Category::factory()->create();
        $this->actingAs($user);

        $this->json('POST', '/api/categories')
            ->assertStatus(403);
        $this->json('PATCH', '/api/categories/' . $category->id)
            ->assertStatus(403);
    }

    /** @test */
    public function can_access_category_index()
    {
        Category::factory()->count(2)->create();

        $this->json('GET', '/api/categories')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        0 => [
                            'id',
                            'name',
                            'parent_id',
                            'sub_categories'
                        ]
                    ]
                ]
            );
    }

    /** @test */
    public function admin_user_can_store_new_category()
    {

        $user = $this->createAdminUser();
        $payload = [
            'name' => 'test',
            'parent_id' => null
        ];

        $this->actingAs($user)
            ->json('POST', '/api/categories', $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'name' => 'test',
            'parent_id' => null
        ]);
    }

    /** @test */
    public function admin_user_can_update_category()
    {
        $user = $this->createAdminUser();
        $category = Category::factory()->create();
        $categoryTwo = Category::factory()->create();
        $data = ['name' => 'Category Updated.', 'parent_id' => $category->id];


        $this->actingAs($user)
            ->json('PATCH', '/api/categories/' . $categoryTwo->id, $data)
            ->assertStatus(200)
            ->assertJson(
                [
                    'name' => 'Category Updated.',
                    'parent_id' => $category->id
                ]
            );
    }
}
