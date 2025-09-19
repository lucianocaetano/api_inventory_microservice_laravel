<?php

namespace Src\category\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Src\category\infrastructure\models\Category;
use Tests\helpers\authentication;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{

    use RefreshDatabase;

    private string $token;
    private string $slug;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(
            [
                "id" => uniqid(),
                "name" => "test",
                "slug" => "test",
                "parent" => null
            ]
        );

        $this->slug = $category->slug;
    }

    public function test_to_delete_category(): void
    {
        $token = authentication::getSuperToken();

        $response = $this->deleteJson(
            'api/v1/category/' . $this->slug,
            [],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(204);
    }

    public function test_to_delete_category_not_found(): void
    {
        $token = authentication::getSuperToken();

        $response = $this->deleteJson(
            'api/v1/category/' . "not_found_category",
            [],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(404);
    }

    public function test_to_delete_category_but_i_do_not_have_permission(): void
    {
        $token = authentication::getNormalToken();

        $response = $this->deleteJson(
            'api/v1/category/' . $this->slug,
            [],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(403);

        $response->assertJson([
            "data" => [],
            "message" => 'You don\'t have permission to delete a category',
            "errors" => [],
            "status" => 403,
        ]);
    }

    public function test_to_delete_category_but_without_token(): void
    {

        $response = $this->deleteJson(
            'api/v1/category/' . $this->slug,
            [],
            [
            ]
        );

        $response->assertStatus(301);

        $response->assertJson([
            "error" => 'Invalid token',
        ]);
    }

}
