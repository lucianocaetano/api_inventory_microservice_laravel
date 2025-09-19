<?php

namespace Src\coupon\infrastructure\models\traits;

trait CouponTrait
{
    public function scopeFilter($query, array $filters)
    {
        if(isset($filters['type'])) {
            $query->where("type", $filters['type']);
        }

        if(isset($filters['code'])) {
            $query->where("code", $filters['code']);
        }

        if(isset($filters['is_active'])) {
            $query->where("is_active", $filters['is_active']);
        }

        return $query;
    }
}
