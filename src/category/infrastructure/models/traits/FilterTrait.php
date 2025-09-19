<?php

namespace Src\category\infrastructure\models\traits;

trait FilterTrait
{
    public function scopeFilter($query, array $filter)
    {

        if($filter['name']) {
            $query->where("name", $filter['name']);
        }

        return $query;
    }
}
