<?php

namespace Src\category\infrastructure\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\category\infrastructure\models\traits\FilterTrait;

class Category extends Model
{
    use FilterTrait, HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'parent',
    ];
}
