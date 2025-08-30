<?php

namespace Src\coupon\application\use_cases;

use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\repositories\CouponRepository;

class CreateCouponUseCase {

    public function __construct(
        private CouponRepository $repository
    ) {}

    public function execute(Coupon $coupon): Coupon
    {
        return $this->repository->save($coupon);
    }
}
