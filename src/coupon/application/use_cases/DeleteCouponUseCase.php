<?php

namespace Src\coupon\application\use_cases;

use Src\coupon\domain\repositories\CouponRepository;
use Src\coupon\domain\service\CouponService;
use Src\coupon\domain\value_objects\CouponCode;
use Src\shared\application\contracts\out\ExtractCurrentUser;

class DeleteCouponUseCase {

    public function __construct(
        private CouponRepository $repository,
        private ExtractCurrentUser $extractCurrentUser
    ) {}

    public function execute(CouponCode $code): void
    {
        $user = $this->extractCurrentUser->currentUser();

        $couponService = new CouponService(
            $user->permissions(),
        );

        $couponService->validDelete();

        $this->repository->delete($code);
    }
}
