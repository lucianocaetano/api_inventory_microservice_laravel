<?php

namespace Src\product\infrastructure\models;

use Illuminate\Database\Eloquent\Model;
use Src\category\infrastructure\models\Category;
use Src\product\infrastructure\models\traits\ProductTrait;

class Product extends Model {

    use ProductTrait;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'name',
        'description',
        'quantity',
        'currency_symbol',
        'price',
        'category_id',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
