<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestTraits\CreateUserTrait;

class LanguageControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserTrait;

    /** @test */
    public function non_admin_can_not_access_store_update_language_apis()
    {
        $user = $this->createBasicUser();
        $lang = Language::factory()->create();

        $this->actingAs($user);

        $this->json('POST', route('languages.store'), [])
            ->assertStatus(403);

        $this->json('PATCH', route('languages.update', ['language' => $lang->id]), [])
            ->assertStatus(403);

        $this->json('DELETE', route('languages.destroy', ['language' => $lang->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function can_list_all_languages()
    {
        Language::factory()->create();
        $this->json('GET', route('languages.index'))
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        0 => [
                            'id',
                            'name',
                            'iso_639_1',
                            'slug'
                        ]
                    ]
                ]
            );
    }

    /** @test */
    public function admin_can_store_language()
    {
        $payload = [
            'iso_639_1' => 'en',
            'name' => 'English',
            'slug' => 'english'
        ];

        $admin = $this->createAdminUser();

        $this->actingAs($admin);


        $this->json('POST', route('languages.store'), $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas('languages', $payload);
    }

    /** @test */
    public function admin_can_update_language()
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin);
        $lang = Language::factory()->create();
        $payload = ['name' => 'updated language.'];

        $this->json('PATCH', route('languages.update', ['language' => $lang->id]), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('languages', $payload);
    }

    /** @test */
    public function admin_can_delete_language()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);
        $payload = [
            'name' => 'English test',
            'iso_639_1' => 'en test',
            'slug' => 'english-test'
        ];

        $lang = Language::create($payload);
        $this->json('DELETE', route('languages.destroy', ['language' => $lang->id]))
            ->assertStatus(204);

        $this->assertSoftDeleted($lang);
    }
}
