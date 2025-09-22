<?php

namespace Tests\Feature\coupons;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\category\infrastructure\models\Category;
use Src\coupon\infrastructure\models\Coupon;
use Tests\helpers\authentication;
use Tests\TestCase;

class DeleteCouponTest extends TestCase
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

    public function test_delete_a_coupon()
    {
        $token = authentication::getSuperToken();

        $response = $this->deleteJson('/api/v1/coupon/'.$this->coupon->code, [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(204);
    }

    public function test_delete_a_coupon_but_i_do_not_have_permissions()
    {
        $token = authentication::getNormalToken();

        $response = $this->deleteJson('/api/v1/coupon/'.$this->coupon->code, [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(403);
        $response->assertJsonPath('message', "You don't have permission to delete a coupon");
    }

    public function test_delete_a_coupon_but_i_do_not_have_token()
    {
        $response = $this->deleteJson('/api/v1/coupon/'.$this->coupon->code, [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(401);
    }

    public function test_delete_a_coupon_but_the_coupon_does_not_exists()
    {
        $token = authentication::getSuperToken();

        $response = $this->deleteJson('/api/v1/coupon/' . 'radom coupon that does not exists', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(404);
    }
}
