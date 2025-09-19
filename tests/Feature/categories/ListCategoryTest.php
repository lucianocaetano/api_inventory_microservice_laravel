<?php

namespace Tests\Feature\categories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Tests\TestCase;

class ListCategoryTest extends TestCase {

    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        $name = "new_category_that_is_100_percent_impossible_to_replicate_by_a_factory";

        Category::create([
            "id" => fake()->uuid(),
            "name" => $name,
            "slug" => str()->slug($name),
            "parent" => null
        ]);

        for ($i = 0; $i < 15; $i++) {
            Category::create([
                "id" => fake()->uuid(),
                "name" => fake()->name(),
                "slug" => str()->slug(fake()->name()),
                "parent" => null
            ]);
        }
    }

    public function test_to_list_categories(): void
    {
        $response = $this->getJson('api/v1/category', [
            "Accept" => "application/json",
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'parent',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'meta' => [
                    'total',
                    'current_page',
                    'last_page',
                ]
            ],
        ]);

        $response->assertJsonPath('data.meta.current_page', 1);
        $response->assertJsonPath('data.meta.last_page', 2);
        $response->assertJsonPath('data.meta.total', 16);
    }

    public function test_to_list_categories_page_2(): void
    {

        $response = $this->getJson('api/v1/category?page=2', [
            "Accept" => "application/json",
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'parent',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'meta' => [
                    'total',
                    'current_page',
                    'last_page',
                ]
            ],
        ]);

        $response->assertJsonPath('data.meta.current_page', 2);
        $response->assertJsonPath('data.meta.last_page', 2);
        $response->assertJsonPath('data.meta.total', 16);
    }

    public function test_to_list_categories_but_filtering_name(): void
    {
        $name="new_category_that_is_100_percent_impossible_to_replicate_by_a_factory";

        $response = $this->getJson('api/v1/category?name='.$name, [
            "Accept" => "application/json",
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'parent',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'meta' => [
                    'total',
                    'current_page',
                    'last_page',
                ]
            ],
        ]);

        $response->assertJsonPath('data.meta.current_page', 1);
        $response->assertJsonPath('data.meta.last_page', 1);
        $response->assertJsonPath('data.meta.total', 1);
    }
}
