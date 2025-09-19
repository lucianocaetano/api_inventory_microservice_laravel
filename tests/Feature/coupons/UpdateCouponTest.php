<?php

namespace Tests\Feature\coupons;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Src\coupon\infrastructure\models\Coupon;
use Tests\helpers\authentication;
use Tests\TestCase;

class UpdateCouponTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private Coupon $coupon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'id' => fake()->uuid(),
            'name' => 'Category 1',
            'slug' => 'category-1',
            'parent' => null
        ]);

        $this->coupon = Coupon::create([
            'code' => strtoupper(fake()->uuid()),
            'type' => 'fixed',
            'amount' => 10,
            'expires_at' => now()->addDays(1)->toDateTimeString(),
            'is_active' => true,
            'category_id' => $this->category->id
        ]);
    }

    public function test_update_a_fixed_coupon()
    {
        $token = authentication::getSuperToken();

        $date = now()->addDays(1)->toDateTimeString();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 40,
                'currency_symbol' => '$',
                'expires_at' => $date,
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "code",
                "type",
                "amount",
                "percent",
                "expires_at",
                "is_active",
                "category_id"
            ],
            "status",
            "errors",
            "message"
        ]);

        $response->assertJsonPath('data.amount', "$40");
        $response->assertJsonPath('data.expires_at', $date);
        $response->assertJsonPath('data.is_active', true);
    }

    public function test_update_a_percent_coupon()
    {
        $token = authentication::getSuperToken();

        $date = now()->addDays(1)->toDateTimeString();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'percent',
                'percent' => 40,
                'expires_at' => $date,
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "code",
                "type",
                "amount",
                "percent",
                "expires_at",
                "is_active",
                "category_id"
            ],
            "status",
            "errors",
            "message"
        ]);

        $response->assertJsonPath('data.type', 'percent');
        $response->assertJsonPath('data.percent', 40);
        $response->assertJsonPath('data.expires_at', $date);
    }

    public function test_update_coupon_but_i_is_not_have_permissions()
    {
        $token = authentication::getNormalToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 10,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertStatus(403);
        $response->assertJson([
            "status" => 403,
            "errors" => [],
            "message" => "You don't have permission to update a coupon",
            "data" => []
        ]);
    }

    public function test_update_coupon_but_i_is_not_have_token()
    {
        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 10,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertStatus(301);
        $response->assertJson([
            "error" => "Invalid token",
        ]);
    }

    public function test_update_coupon_but_the_type_field_is_not_here()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'amount' => 10,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("type");
    }

    public function test_update_coupon_but_the_type_field_is_not_a_fixed_string_or_percent_string()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => "random text",
                'amount' => 10,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("type");
    }

    public function test_update_coupon_but_the_type_field_is_not_a_string()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 123,
                'amount' => 10,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("type");
    }

    public function test_update_coupon_but_the_amount_is_with_the_field_type_equal_to_a_fixed()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'fixed',
                'percent' => 50,
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("amount");
    }

    public function test_update_coupon_but_the_amount_is_with_the_field_type_equal_to_a_percent()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'percent',
                'amount' => 3,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("percent");
    }

    public function test_update_coupon_but_type_amount_is_less_than_1()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'fixed',
                'amount' => 0,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("amount");
    }

    public function test_update_coupon_but_type_amount_is_not_a_numeric()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'fixed',
                'amount' => 'random text',
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("amount");
    }

    public function test_update_coupon_but_the_currency_symbol_field_is_not_a_string_type()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'fixed',
                'amount' => 3,
                'currency_symbol' => 123,
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("currency_symbol");
    }

    public function test_update_coupon_but_the_currency_symbol_field_is_not_here_but_the_amount_field_is_here()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'fixed',
                'amount' => 3,
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("currency_symbol");
    }

    public function test_update_coupon_but_the_percent_field_is_not_a_integer_type()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                "type" => 'percent',
                'percent' => 'random text',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("percent");
    }

    public function test_update_coupon_but_expired_at_field_is_not_here()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 10,
                'currency_symbol' => '$',
                'is_active' => true,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("expires_at");
    }

    public function test_update_coupon_but_expired_at_field_does_not_have_the_format()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 10,
                'currency_symbol' => '$',
                'is_active' => true,
                'expires_at' => 'random text',
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("expires_at");
    }

    public function test_update_coupon_but_is_active_field_is_not_here()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 10,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("is_active");
    }

    public function test_update_coupon_but_is_active_field_does_not_boolean_type()
    {
        $token = authentication::getSuperToken();

        $response = $this->putJson('/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 40,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => 123,
                'category_id' => $this->category->id
            ],
            [
                'Accept' => 'application/json',
                "Authorization" => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertInvalid("is_active");
    }

    public function test_update_a_coupon_but_category_id_field_does_not_exists() {

        $token = authentication::getSuperToken();

        $response = $this->putJson(
            '/api/v1/coupon/'.$this->coupon->code,
            [
                'type' => 'fixed',
                'amount' => 40,
                'currency_symbol' => '$',
                'expires_at' => now()->addDays(1)->toDateTimeString(),
                'is_active' => true,
                'category_id' => "random_text"
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
