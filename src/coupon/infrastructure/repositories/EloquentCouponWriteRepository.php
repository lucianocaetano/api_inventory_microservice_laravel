<?php

namespace Src\coupon\infrastructure\repositories;

use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\repositories\CouponRepository;
use Src\coupon\domain\value_objects\CouponCode;

class EloquentCouponWriteRepository implements CouponRepository {

    public function save(Coupon $coupon): Coupon
    {

        return $coupon;
    }

    public function update(Coupon $coupon): Coupon
    {

        return $coupon;
    }

    public function delete(CouponCode $code): void
    {

    }
}
