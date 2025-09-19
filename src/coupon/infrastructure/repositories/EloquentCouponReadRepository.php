<?php

namespace Src\coupon\infrastructure\repositories;

use Src\coupon\application\contracts\out\CouponReadRepository;
use Src\coupon\infrastructure\models\Coupon;

class EloquentCouponReadRepository implements CouponReadRepository {

    public function findAllCoupons(array $filters): array
    {
        $data = Coupon::filter($filters)->paginate();

        return [
            "items" => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total()
            ]
        ];
    }
}
