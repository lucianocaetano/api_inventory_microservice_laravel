<?php

namespace Tets\Feature\products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Src\product\infrastructure\models\Product;
use Tests\helpers\authentication;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

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

        $name = fake()->name();

        $this->product = Product::create([
            "id" => fake()->uuid(),
            "name" => $name,
            "slug" => str($name)->slug(),
            "description" => fake()->paragraph(),
            "quantity" => fake()->numberBetween(1, 4),
            "price" => fake()->numberBetween(200, 300.80),
            "currency_code" => "UYU",
            "currency_symbol" => "\$U",
            "currency_decimals" => 2,
            "category_id" => $category->id
        ]);
    }

    public function test_delete_a_product()
    {
        $token = authentication::getSuperToken();

        $response = $this->deleteJson(
            "/api/v1/product/" . $this->product->id,
            [],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
            ]
        );

        $response->assertStatus(204);
    }

    public function test_delete_a_product_but_i_do_not_have_permission()
    {
        $token = authentication::getNormalToken();

        $response = $this->deleteJson(
            "/api/v1/product/" . $this->product->id,
            [],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
            ]
        );

        $response->assertStatus(403);
    }

    public function test_delete_a_product_but_i_do_not_have_token()
    {
        $response = $this->deleteJson(
            "/api/v1/product/" . $this->product->id,
            [],
            [
                "Accept" => "application/json",
            ]
        );

        $response->assertStatus(301);
    }

    public function test_delete_a_product_but_this_product_does_not_exists()
    {
        $token = authentication::getSuperToken();

        $response = $this->deleteJson(
            "/api/v1/product/" . "this-product-does-not-exists",
            [],
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $token,
            ]
        );

        $response->assertStatus(404);
    }
}
