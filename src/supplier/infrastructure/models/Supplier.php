<?php

namespace Src\supplier\infrastructure\models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        "id",
        "name",
        "email",
        "phone",
    ];
}
