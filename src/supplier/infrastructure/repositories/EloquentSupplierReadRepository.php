<?php

namespace Src\supplier\infrastructure\repositories;

use Src\supplier\application\contracts\out\SupplierReadRepository;
use Src\supplier\infrastructure\models\Supplier;

class EloquentSupplierReadRepository implements SupplierReadRepository {

    public function findAllSupplier(array $filters): array
    {
        $data = Supplier::paginate();

        return [
            "items" => $data->items(),
            "meta" => [
                "total" => $data->total(),
                "last_page" => $data->lastPage(),
                "current_page" => $data->currentPage()
            ]
        ];
    }
}
