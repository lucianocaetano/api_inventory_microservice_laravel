<?php

namespace Tests\Feature\products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Tests\helpers\authentication;
use Tests\TestCase;

class CreateProductTest extends TestCase {

    use RefreshDatabase;

    private string $category_id;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category",
            "slug" => "new-category",
            "parent" => null
        ]);

        $this->category_id = $category->id;
    }

    public function test_create_a_new_product() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(201);

        $response->assertJsonStructure(["data" => ["id"]]);

        $response->assertJson([
            "data" => [
                "name" => "new product",
                "slug" => "new-product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => "$200.02",
                "category_id" => $this->category_id,
            ],
            "message" => null,
            "errors" => [],
            "status" => 201
        ]);
    }

    public function test_create_a_new_product_but_i_do_not_have_permission() {

        $token = authentication::getNormalToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(403);

        $response->assertJson([
            "data" => [],
            "message" => "You don't have permission to create a category",
            "errors" => [],
            "status" => 403
        ]);
    }

    public function test_create_a_new_product_but_i_do_not_have_token() {

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(301);

        $response->assertJson([
            "error" => "Invalid token",
        ]);
    }

    public function test_create_a_new_product_but_validation_of_the_name_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("name");
    }

    public function test_create_a_new_product_but_validation_of_the_name_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => 24,
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("name");
    }

    public function test_create_a_new_product_but_validation_of_the_description_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("description");
    }

    public function test_create_a_new_product_but_validation_of_the_description_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => 23,
                "quantity" => 5,
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("description");
    }

    public function test_create_a_new_product_but_validation_of_the_quantity_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("quantity");
    }

    public function test_create_a_new_product_but_validation_of_the_quantity_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => "this does not a string",
                "price" => 200.02,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("quantity");
    }

    public function test_create_a_new_product_but_validation_of_the_price_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("price");
    }

    public function test_create_a_new_product_but_validation_of_the_price_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => "this does not a string",
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("price");
    }

    public function test_create_a_new_product_but_validation_of_the_category_id_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("category_id");
    }

    public function test_create_a_new_product_but_category_id_field_does_not_exists() {

        $token = authentication::getSuperToken();

        $response = $this->postJson(
            "/api/v1/product",
            [
                "name" => "new product",
                "description" => "new product description",
                "quantity" => 5,
                "price" => 200.02,
                "category_price" => "this_id_does_not_exists"
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertInvalid("category_id");
    }
}
