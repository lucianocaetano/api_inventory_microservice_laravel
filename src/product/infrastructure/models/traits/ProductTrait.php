<?php

namespace Src\product\infrastructure\models\traits;

trait ProductTrait
{
    public function scopeFilter($query, array $filter)
    {
        if (!empty($filter['name'])) {
            $query->where("name", "like", "%{$filter['name']}%");
        }

        if (!empty($filter['description'])) {
            $query->where("description", "like", "%{$filter['description']}%");
        }

        if (!empty($filter['category_id'])) {
            $query->where("category_id", $filter['category_id']);
        }

        if (!empty($filter['min_price'])) {
            $query->where("price", ">=", $filter['min_price']);
        }

        if (!empty($filter['max_price'])) {
            $query->where("price", "<=", $filter['max_price']);
        }

        return $query;
    }
}
