<?php

namespace Src\coupon\application\use_cases;

use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\repositories\CouponRepository;

class UpdateCouponUseCase {

    public function __construct(
        private CouponRepository $repository
    ) {}

    public function execute(Coupon $coupon): Coupon
    {
        return $this->repository->update($coupon);
    }
}
