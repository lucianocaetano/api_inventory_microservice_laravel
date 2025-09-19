<?php

namespace Src\payment\infrastructure\models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'order_id',
        'client_id',
        'status',
        'amount',
    ];
}

