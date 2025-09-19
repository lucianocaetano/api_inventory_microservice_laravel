<?php

namespace Tests\Feature\products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Src\product\infrastructure\models\Product;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category",
            "slug" => "new-category",
            "parent" => null
        ]);

        $category_2 = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category 2",
            "slug" => "new-category-2",
            "parent" => null
        ]);

        Product::create([
            "id" => fake()->uuid(),
            "name" => "this_is_a_product",
            "slug" => str("this_is_a_product")->slug(),
            "description" => "this_is_the_description_of_a_product",
            "quantity" => fake()->numberBetween(1, 4),
            "price" => fake()->numberBetween(200, 300.80),
            "currency_symbol" => "\$U",
            "category_id" => $category->id,
        ]);

        for ($i = 0; $i < 15; $i++) {
            Product::create([
                "id" => fake()->uuid(),
                "name" => fake()->name(),
                "slug" => str(fake()->name())->slug(),
                "description" => fake()->paragraph(),
                "quantity" => fake()->numberBetween(1, 4),
                "price" => fake()->numberBetween(200, 300.80),
                "currency_symbol" => "\$U",
                "category_id" => $category->id
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            Product::create([
                "id" => fake()->uuid(),
                "name" => fake()->name(),
                "slug" => str(fake()->name())->slug(),
                "description" => fake()->paragraph(),
                "quantity" => fake()->numberBetween(1, 4),
                "price" => fake()->numberBetween(200, 300.80),
                "currency_symbol" => "\$U",
                "category_id" => $category_2->id
            ]);
        }

        $this->category = $category_2;
    }

    public function test_lists_products()
    {
        $response = $this->get('api/v1/product');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonCount(15, "data.items");

        $response->assertJsonPath("data.meta.total", 21);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_products_page_2()
    {
        $response = $this->get('api/v1/product?page=2');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonCount(6, "data.items");

        $response->assertJsonPath("data.meta.total", 21);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 2);
    }

    public function test_lists_products_but_i_filtering_by_name()
    {
        $response = $this->get('api/v1/product?name=this_is_a_product');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonCount(1, "data.items");

        $response->assertJsonPath("data.meta.total", 1);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_products_but_i_filtering_by_description()
    {
        $response = $this->get('api/v1/product?description=_product');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonCount(1, "data.items");

        $response->assertJsonPath("data.meta.total", 1);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_products_but_i_filtering_by_category_id()
    {
        $response = $this->get('api/v1/product?category_id=' . $this->category->id);

        $response->assertStatus(200);

        $response->assertJsonCount(5, "data.items");

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonPath("data.meta.total", 5);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);

    }

    public function test_lists_products_but_i_filtering_by_min_price_500()
    {
        $response = $this->get('api/v1/product?min_price=' . 500 );

        $response->assertStatus(200);

        $response->assertJsonCount(0, "data.items");

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonPath("data.meta.total", 0);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_products_but_i_filtering_by_max_price()
    {
        $response = $this->get('api/v1/product?max_price=' . 100 );

        $response->assertStatus(200);

        $response->assertJsonCount(0, "data.items");

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonPath("data.meta.total", 0);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_products_but_i_filtering_by_max_price_100_and_min_price_500()
    {
        $response = $this->get('api/v1/product?max_price=' . 100 . "&min_price=" . 500);

        $response->assertStatus(200);

        $response->assertJsonCount(0, "data.items");

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonPath("data.meta.total", 0);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_products_but_i_filtering_by_max_price_500_and_min_price_100()
    {
        $response = $this->get('api/v1/product?max_price=' . 500 . "&min_price=" . 100);

        $response->assertStatus(200);

        $response->assertJsonCount(15, "data.items");

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "id",
                        "slug",
                        "name",
                        "description",
                        "quantity",
                        "price",
                        "currency_symbol",
                        "category_id"
                    ]
                ],
                "meta" => [
                    "current_page",
                    "last_page",
                    "total"
                ]
            ],
            "errors",
            "message",
            "status"
        ]);

        $response->assertJsonPath("data.meta.total", 21);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 1);
    }
}
