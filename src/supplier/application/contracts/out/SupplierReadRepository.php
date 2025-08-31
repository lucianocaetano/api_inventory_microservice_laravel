<?php

namespace Src\supplier\application\contracts\out;

interface SupplierReadRepository {

    public function findAllSupplier(array $filters): array;
}
