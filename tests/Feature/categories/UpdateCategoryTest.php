<?php

namespace Tests\Feature\categories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Tests\helpers\authentication;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    private string $id;

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

        $this->id = $category->id;
    }

    public function test_to_update_category(): void
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson(
            'api/v1/category/' . $this->id,
            [
                "name" => "test 2",
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                "name" => "test 2",
                "slug" => "test-2",
                "parent" => null,
            ],
            "message" => null,
            "errors" => [],
            "status" => 200,
        ]);
    }

    public function test_to_update_category_but_validation_of_the_name_field_is_required(): void {
        $token = authentication::getSuperToken();

        $response = $this->putJson(
            'api/v1/category/'.$this->id,
            [
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertInvalid("name");
    }

    public function test_to_update_category_but_validation_of_the_parent_field_is_string_type(): void {
        $token = authentication::getSuperToken();

        $response = $this->putJson(
            'api/v1/category/'.$this->id,
            [
                "name" => 2,
                "parent" => null,
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertInvalid("name");
    }

    public function test_to_update_category_not_found(): void
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson(
            'api/v1/category/' . "not_found_category",
            [
                "name" => "test 2",
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(404);

        $response->assertJson([
            "data" => [],
            "message" => null,
            "errors" => [],
            "status" => 404,
        ]);
    }

    public function test_to_update_category_buy_i_do_not_have_permission(): void
    {
        $token = authentication::getNormalToken();

        $response = $this->putJson(
            'api/v1/category/' . $this->id,
            [
                "name" => "test 2",
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(403);

        $response->assertJson([
            "data" => [],
            "message" => 'You don\'t have permission to update a category',
            "errors" => [],
            "status" => 403,
        ]);
    }
}
