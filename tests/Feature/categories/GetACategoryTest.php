<?php

namespace Tests\Feature\categories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Tests\TestCase;

class GetACategoryTest extends TestCase {

    use RefreshDatabase;

    private string $slug;

    protected function setUp(): void {
        parent::setUp();

        $this->slug = str("new_category")->slug();

        Category::create([
            "id" => fake()->uuid(),
            "name" => "new_category",
            "slug" => str()->slug("new_category"),
            "parent" => null
        ]);
    }

    public function test_get_a_category() {

        $response = $this->getJson("/api/v1/category/{$this->slug}", [
            "Accept" => "application/json",
        ]);

        $response->assertStatus(200);
    }

    public function test_get_a_category_but_it_does_not_exist() {

        $response = $this->getJson("/api/v1/category/this-category-does-not-exist", [
            "Accept" => "application/json",
        ]);

        $response->assertStatus(404);
    }
}
