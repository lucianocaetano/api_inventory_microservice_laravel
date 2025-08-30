<?php

namespace Src\coupon\infrastructure\models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    public $incrementing = false;

    protected $keyType = 'string';

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
