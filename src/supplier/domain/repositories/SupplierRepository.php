<?php

namespace Src\supplier\domain\repositories;

use Src\shared\domain\value_objects\Id;
use Src\supplier\domain\entities\Supplier;

interface SupplierRepository {

    public function save(Supplier $supplier): Supplier;
    public function update(Supplier $supplier): Supplier;
    public function delete(Id $id);
}
