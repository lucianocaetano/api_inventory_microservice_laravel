<?php

namespace Src\coupon\application\use_cases;

use Src\coupon\domain\repositories\CouponRepository;
use Src\coupon\domain\value_objects\CouponCode;

class DeleteCouponUseCase {

    public function __construct(
        private CouponRepository $repository
    ) {}

    public function execute(CouponCode $code): void
    {
        $this->repository->delete($code);
    }
}
