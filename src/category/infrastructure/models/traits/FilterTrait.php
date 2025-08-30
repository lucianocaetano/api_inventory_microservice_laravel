<?php

namespace Src\category\infrastructure\models\traits;

trait FilterTrait
{
    public function scopeFilter($query, array $filter)
    {

        return $query;
    }
}
