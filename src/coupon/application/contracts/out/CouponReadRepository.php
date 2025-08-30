<?php

namespace Src\coupon\application\contracts\out;

interface CouponReadRepository
{

    public function findAllCoupons(array $filters): array;
}
