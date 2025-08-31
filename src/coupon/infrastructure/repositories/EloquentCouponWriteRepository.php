<?php

namespace Src\coupon\infrastructure\repositories;

use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\repositories\CouponRepository;
use Src\coupon\domain\value_objects\CouponCode;
use Src\coupon\infrastructure\models\Coupon as ModelsCoupon;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class EloquentCouponWriteRepository implements CouponRepository {

    public function save(Coupon $coupon): Coupon
    {
        ModelsCoupon::create([
            'code' => $coupon->code(),
            'type' => $coupon->type(),
            'amount' => $coupon->amount(),
            'percent' => $coupon->percent(),
            'expires_at' => $coupon->expiresAt(),
            'is_active' => $coupon->isValidNow(),
            'category_id' => $coupon->category_id(),
        ]);

        return $coupon;
    }

    public function update(Coupon $coupon): Coupon
    {
        $model = ModelsCoupon::where('code', $coupon->code())->first();

        if(!$model)
            throw new DataNotFoundException('Coupon');

        $model::update([
            'code' => $coupon->code(),
            'type' => $coupon->type(),
            'amount' => $coupon->amount(),
            'percent' => $coupon->percent(),
            'expires_at' => $coupon->expiresAt(),
            'is_active' => $coupon->isValidNow(),
            'category_id' => $coupon->category_id(),
        ]);

        return $coupon;
    }

    public function delete(CouponCode $code): void
    {
        $model = ModelsCoupon::where('code', $code)->find();

        if(!$model)
            throw new DataNotFoundException('Coupon');

        $model->delete();
    }
}
