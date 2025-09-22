<?php

namespace Tests\Feature\products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Src\product\infrastructure\models\Product;
use Tests\helpers\authentication;
use Tests\TestCase;

class UpdateProductTest extends TestCase {

    use RefreshDatabase;

    private string $category_id;
    private string $other_category_id;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category",
            "slug" => "new-category",
            "parent" => null
        ]);

        $other_category = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category 2",
            "slug" => "new-category-2",
            "parent" => null
        ]);

        $name = fake()->name();

        $this->product = Product::create([
            "id" => fake()->uuid(),
            "name" => $name,
            "slug" => str($name)->slug(),
            "description" => fake()->paragraph(),
            "quantity" => fake()->numberBetween(1, 4),
            "price" => fake()->numberBetween(200, 300.80),
            "currency_symbol" => "\$U",
            "category_id" => $category->id
        ]);

        $this->category_id = $category->id;
        $this->other_category_id = $other_category->id;
    }

    public function test_update_product_name() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
            [
                "name" => "new product",
                "description" => $this->product->description,
                "quantity" => $this->product->quantity,
                "price" => $this->product->price,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure(["data" => ["id"]]);

        $response->assertJson([
            "data" => [
                "name" => "new product",
            ],
            "message" => null,
            "errors" => [],
            "status" => 200
        ]);
    }

    public function test_update_product_description() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
            [
                "name" => $this->product->name,
                "description" => "new product description",
                "quantity" => $this->product->quantity,
                "price" => $this->product->price,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure(["data" => ["id"]]);

        $response->assertJson([
            "data" => [
                "description" => "new product description",
            ],
            "message" => null,
            "errors" => [],
            "status" => 200
        ]);
    }

    public function test_update_product_quantity() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
            [
                "name" => $this->product->name,
                "description" => $this->product->description,
                "quantity" => 45,
                "price" => $this->product->price,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure(["data" => ["id"]]);

        $response->assertJson([
            "data" => [
                "quantity" => 45,
            ],
            "message" => null,
            "errors" => [],
            "status" => 200
        ]);
    }

    public function test_update_product_price() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
            [
                "name" => $this->product->name,
                "description" => $this->product->description,
                "quantity" => $this->product->quantity,
                "price" => 500,
                "category_id" => $this->category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure(["data" => ["id"]]);

        $response->assertJson([
            "data" => [
                "price" => "$500",
            ],
            "message" => null,
            "errors" => [],
            "status" => 200
        ]);
    }

    public function test_update_product_category_id() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
            [
                "name" => $this->product->name,
                "description" => $this->product->description,
                "quantity" => $this->product->quantity,
                "price" => $this->product->price,
                "category_id" => $this->other_category_id
            ],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json"
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure(["data" => ["id"]]);

        $response->assertJson([
            "data" => [
                "category_id" => $this->other_category_id,
            ],
            "message" => null,
            "errors" => [],
            "status" => 200
        ]);
    }

    public function test_update_a_product_but_i_do_not_have_permission() {

        $token = authentication::getNormalToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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
            "message" => "You don't have permission to update a category",
            "errors" => [],
            "status" => 403
        ]);
    }

    public function test_update_a_product_but_i_do_not_have_token() {

        $response = $this->putJson(
            "/api/v1/product/" . $this->category_id,
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

        $response->assertStatus(401);

        $response->assertJson([
            "message" => "Unauthenticated.",
        ]);
    }

    public function test_update_a_product_but_validation_of_the_name_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_name_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_description_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_description_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_quantity_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_quantity_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_price_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_price_field_is_string() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_validation_of_the_category_id_field_is_required() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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

    public function test_update_a_product_but_category_id_field_does_not_exists() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            "/api/v1/product/" . $this->product->id,
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
