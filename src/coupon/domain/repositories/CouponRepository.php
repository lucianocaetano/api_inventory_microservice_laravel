<?php

namespace Src\coupon\domain\repositories;

use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\value_objects\CouponCode;

interface CouponRepository
{
    public function save(Coupon $coupon): Coupon;
    public function update(Coupon $coupon): Coupon;
    public function delete(CouponCode $code): void;
}
