<?php

namespace Src\coupon\infrastructure\models;

use Illuminate\Database\Eloquent\Model;
use Src\coupon\infrastructure\models\traits\CouponTrait;

class Coupon extends Model
{
    use CouponTrait;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'type',
        'amount',
        'percent',
        'expires_at',
        'is_active',
        'category_id',
    ];
}
