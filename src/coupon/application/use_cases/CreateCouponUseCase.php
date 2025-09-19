<?php

namespace Src\coupon\application\use_cases;

use Src\coupon\domain\entities\Coupon;
use Src\coupon\domain\repositories\CouponRepository;
use Src\coupon\domain\service\CouponService;
use Src\shared\application\contracts\out\ExtractCurrentUser;

class CreateCouponUseCase {

    public function __construct(
        private CouponRepository $repository,
        private ExtractCurrentUser $extractCurrentUser,
    ) {}

    public function execute(Coupon $coupon): Coupon
    {
        $user = $this->extractCurrentUser->currentUser();

        $couponService = new CouponService(
            $user->permissions(),
        );

        $couponService->validCreate();

        return $this->repository->save($coupon);
    }
}
