<?php

namespace Src\supplier\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\shared\domain\value_objects\Id;
use Src\supplier\domain\entities\Supplier;
use Src\supplier\application\use_cases\DeleteSupplierUseCase;
use Src\supplier\application\use_cases\FindAllSuppliersUseCase;
use Src\supplier\application\use_cases\CreateSupplierUseCase;;
use Src\supplier\application\use_cases\UpdateSupplierUseCase;;

class SupplierController extends Controller {

    public function __construct(
        private FindAllSuppliersUseCase $findAllSuppliersUseCase,
        private CreateSupplierUseCase $createSupplierUseCase,
        private DeleteSupplierUseCase $deleteSupplierUseCase,
        private UpdateSupplierUseCase $updateSupplierUseCase
    ) {}

    public function index(Request $request) {
        $queries = $request->query();

        return $this->findAllSuppliersUseCase->execute($queries);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        return $this->createSupplierUseCase->execute(new Supplier(
            Id::randomId(),
            $data['name'],
            $data['email'],
            $data['phone']
        ));
    }

    public function update(Request $request, string $id) {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        return $this->updateSupplierUseCase->execute(new Supplier(
            new Id($id),
            $data['name'],
            $data['email'],
            $data['phone']
        ));
    }

    public function destroy(string $id) {
        return $this->deleteSupplierUseCase->execute(new Id($id));
    }

}
