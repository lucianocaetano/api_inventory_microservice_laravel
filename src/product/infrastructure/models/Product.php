<?php

namespace Src\product\infrastructure\models;

use Illuminate\Database\Eloquent\Model;
use Src\category\infrastructure\models\Category;

class Product extends Model {

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'name',
        'description',
        'quantity',
        'currency_code',
        'currency_symbol',
        'currency_decimals',
        'price',
        'category_id',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
