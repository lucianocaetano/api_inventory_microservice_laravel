<?php

namespace Tests\Feature\categories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\helpers\authentication;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    public function test_to_create_category(): void
    {
        $token = authentication::getSuperToken();

        $response = $this->postJson(
            'api/v1/category',
            [
                "name" => "test",
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(201);

        $response->assertJson([
            "data" => [
                "name" => "test",
                "slug" => "test",
                "parent" => null,
            ],
            "message" => null,
            "errors" => [],
            "status" => 201,
        ]);
    }

    public function test_to_create_category_but_validation_of_the_name_field_is_required(): void {
        $token = authentication::getSuperToken();

        $response = $this->postJson(
            'api/v1/category',
            [
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertInvalid("name");
    }

    public function test_to_create_category_but_validation_of_the_parent_field_is_string_type(): void {
        $token = authentication::getSuperToken();

        $response = $this->postJson(
            'api/v1/category',
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

    public function test_to_create_category_without_token(): void {
        $token = null;

        $response = $this->postJson(
            'api/v1/category',
            [
                "name" => "test",
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(301);

        $response->assertJson([
            "error" => "Invalid token",
        ]);
    }

    public function test_to_create_category_but_i_do_not_have_permission(): void
    {
        $token = authentication::getNormalToken();

        $response = $this->postJson(
            'api/v1/category',
            [
                "name" => "test",
                "parent" => null
            ],
            [
                'Authorization' => "Bearer $token"
            ]
        );

        $response->assertStatus(403);

        $response->assertJson([
            "data" => [],
            "message" => 'You don\'t have permission to create a category',
            "errors" => [],
            "status" => 403,
        ]);
    }
}
