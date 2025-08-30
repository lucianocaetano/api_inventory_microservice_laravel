<?php

namespace Src\coupon\application\use_cases;

use Src\coupon\application\contracts\out\CouponReadRepository;

class FindAllCouponsUseCase {

    public function __construct(
        private CouponReadRepository $repository
    ) {}

    public function execute(array $filters): array {
        return $this->repository->findAllCoupons($filters);
    }
}
