<?php

namespace Src\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\product\application\use_cases\CreateProductUseCase;
use Src\product\application\use_cases\DeleteProductUseCase;
use Src\product\application\use_cases\FindAllProductsUseCase;
use Src\product\application\use_cases\FindBySlugProductUseCase;
use Src\product\application\use_cases\UpdateProductUseCase;
use Src\product\domain\entities\Product;
use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Currency;
use Src\shared\domain\value_objects\Id;

class ProductController extends Controller {

    public function __construct(
        private CreateProductUseCase $createProductUseCase,
        private UpdateProductUseCase $updateProductUseCase,
        private DeleteProductUseCase $deleteProductUseCase,
        private FindAllProductsUseCase $findAllProductsUseCase,
        private FindBySlugProductUseCase $findBySlugProductUseCase
    ) {}

    public function index() {
        return $this->resApi(
            data: $this->findAllProductsUseCase->execute([]),
            status: Response::HTTP_OK
        );
    }

    public function show(string $slug) {

        return $this->resApi(
            data: $this->findBySlugProductUseCase->execute($slug),
            status: Response::HTTP_OK
        );
    }

    public function save(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'currency_code' => 'required|string',
            'currency_symbol' => 'required|string',
            'currency_decimals' => 'required|numeric',
            'category_id' => 'required',
        ]);

        $product = new Product(
            Id::randomId(),
            str()->slug($request->input('name')),
            $request->input('name'),
            $request->input('description'),
            $request->input('quantity'),
            new Amount(
                $request->input('price'),
                new Currency(
                    $request->input('currency_code'),
                    $request->input('currency_symbol'),
                    $request->input('currency_decimals')
                )
            ),
            new Id($request->input('category_id')),
        );

        $product = $this->createProductUseCase->execute(
            $product
        );

        return $this->resApi(
            data: $this->mapModelToArray($product),
            status: Response::HTTP_CREATED
        );
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'currency_code' => 'required|string',
            'currency_symbol' => 'required|string',
            'currency_decimals' => 'required|numeric',
            'category_id' => 'required',
        ]);

        $product = new Product(
            new Id($id),
            str()->slug($request->input('name')),
            $request->input('name'),
            $request->input('description'),
            $request->input('quantity'),
            new Amount(
                $request->input('price'),
                new Currency(
                    $request->input('currency_code'),
                    $request->input('currency_symbol'),
                    $request->input('currency_decimals')
                )
            ),
            $request->input('category_id'),
        );

        return $this->resApi(
            data: $this->mapModelToArray($product),
            status: Response::HTTP_OK
        );
    }

    public function delete(string $id) {

        $this->deleteProductUseCase->execute(
            new Id($id)
        );

        return $this->resApi(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    private function mapModelToArray(Product $product) {

        return [
            'id' => $product->id(),
            'slug' => $product->slug(),
            'name' => $product->name(),
            'description' => $product->description(),
            'quantity' => $product->quantity(),
            'price' => $product->priceToString(),
            'category_id' => $product->category_id(),
        ];
    }
}
