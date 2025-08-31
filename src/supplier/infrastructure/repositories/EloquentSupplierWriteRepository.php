<?php

namespace Src\supplier\infrastructure\repositories;

use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;
use Src\supplier\domain\entities\Supplier;
use Src\supplier\domain\repositories\SupplierRepository;
use Src\supplier\infrastructure\models\Supplier as ModelsSupplier;

class EloquentSupplierSupplierRepository implements SupplierRepository {

    public function save(Supplier $supplier): Supplier
    {
        ModelsSupplier::create([
            'id' => $supplier->id(),
            'name' => $supplier->name(),
            'email' => $supplier->email(),
            'phone' => $supplier->phone(),
        ]);

        return $supplier;
    }

    public function update(Supplier $supplier): Supplier
    {

        $data = ModelsSupplier::where('id', $supplier->id())->first();

        if(!$data)
            throw new DataNotFoundException("Supplier");

        $data::update([
            'id' => $supplier->id(),
            'name' => $supplier->name(),
            'email' => $supplier->email(),
            'phone' => $supplier->phone(),
        ]);

        return $supplier;
    }

    public function delete(Id $id)
    {
        $supplier = ModelsSupplier::where('id', $id->value())->first();

        if(!$supplier)
            throw new DataNotFoundException("Supplier");

        $supplier->delete();
    }
}
