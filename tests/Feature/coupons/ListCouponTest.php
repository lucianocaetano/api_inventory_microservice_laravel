<?php

namespace Tests\Feature\coupons;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Src\coupon\infrastructure\models\Coupon;
use Tests\TestCase;
use Illuminate\Support\Str;

class ListCouponTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private Category $category_2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category",
            "slug" => "new-category",
            "parent" => null
        ]);

       $this->category_2 = Category::create([
            "id" => fake()->uuid(),
            "name" => "new category 2",
            "slug" => "new-category-2",
            "parent" => null
        ]);

        foreach (range(1, 40) as $index) {
            $type = $index % 2 == 0 ? 'fixed' : 'percent';

            Coupon::create([
                "code" => strtoupper(Str::random(10)),
                "type" => $type,
                "amount" => $type === 'fixed'?"$" . fake()->numberBetween(20, 200): null,
                'percent' => $type === 'percent'? fake()->numberBetween(20, 200): null,
                'expires_at' => now()->addDays(2)->toDateTimeString(),
                "is_active" => $index % 2 == 0? true: false,
                "category_id" => $this->category->id,
            ]);
        }
    }

    public function test_lists_coupons()
    {
        $response = $this->get('api/v1/coupon');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $response->assertJsonPath("data.meta.total", 40);
        $response->assertJsonPath("data.meta.last_page", 3);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_coupons_page_2()
    {
        $response = $this->get('api/v1/coupon?page=2');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $response->assertJsonPath("data.meta.total", 40);
        $response->assertJsonPath("data.meta.last_page", 3);
        $response->assertJsonPath("data.meta.current_page", 2);
    }

    public function test_lists_coupons_but_i_filtering_by_fixed_type()
    {
        $response = $this->get('api/v1/coupon?type=fixed');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $values = $response->json('data.items.*.type');

        $this->assertTrue(
            collect($values)->every(fn ($v) => $v === 'fixed')
        );

        $response->assertJsonCount(15, "data.items");

        $response->assertJsonPath("data.meta.total", 20);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_coupons_but_i_filtering_by_percent_type()
    {

        $response = $this->get('api/v1/coupon?type=percent');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $values = $response->json('data.items.*.type');

        $this->assertTrue(
            collect($values)->every(fn ($v) => $v === 'percent')
        );

        $response->assertJsonCount(15, "data.items");

        $response->assertJsonPath("data.meta.total", 20);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_coupons_but_i_filtering_by_code()
    {
        $coupon = Coupon::first();

        $response = $this->get('api/v1/coupon?code='.$coupon->code);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $response->assertJsonPath("data.items.*.code", [$coupon->code]);
        $response->assertJsonPath("data.meta.total", 1);
        $response->assertJsonPath("data.meta.last_page", 1);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_coupons_but_i_filtering_by_is_active_field_is_true()
    {

        $response = $this->get('api/v1/coupon?is_active=true');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $value = $response->json('data.items.*.is_active');

        $this->assertTrue(
            collect($value)->every(fn ($v) => $v === true)
        );

        $response->assertJsonCount(15, "data.items");

        $response->assertJsonPath("data.meta.total", 20);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

    public function test_lists_coupons_but_i_filtering_by_is_active_field_is_false()
    {

        $response = $this->get('api/v1/coupon?is_active=false');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "items" => [
                    "*" => [
                        "code",
                        "type",
                        "percent",
                        "amount",
                        "expires_at",
                        "is_active",
                        "category_id",
                        "created_at",
                        "updated_at",
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

        $value = $response->json('data.items.*.is_active');

        $this->assertTrue(
            collect($value)->every(fn ($v) => $v === false)
        );

        $response->assertJsonCount(15, "data.items");

        $response->assertJsonPath("data.meta.total", 20);
        $response->assertJsonPath("data.meta.last_page", 2);
        $response->assertJsonPath("data.meta.current_page", 1);
    }

}
