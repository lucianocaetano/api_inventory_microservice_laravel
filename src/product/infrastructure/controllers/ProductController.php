<?php

namespace Src\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\product\application\contracts\in\CreateProductUseCasePort;
use Src\product\application\contracts\in\DeleteProductUseCasePort;
use Src\product\application\contracts\in\FindAllProductsUseCasePort;
use Src\product\application\contracts\in\FindBySlugProductUseCasePort;
use Src\product\application\contracts\in\UpdateProductUseCasePort;
use Src\product\domain\entities\Product;
use Src\shared\domain\exception\InvalidPermission;
use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Currency;
use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class ProductController extends Controller {

    public function __construct(
        private CreateProductUseCasePort $createProductUseCase,
        private UpdateProductUseCasePort $updateProductUseCase,
        private DeleteProductUseCasePort $deleteProductUseCase,
        private FindAllProductsUseCasePort $findAllProductsUseCase,
        private FindBySlugProductUseCasePort $findBySlugProductUseCase
    ) {}

    public function index(Request $request) {
        $filters = $request->only(
            [
                'name',
                'description',
                'min_price',
                'max_price',
                'category_id'
            ]
        );

        return $this->resApi(
            data: $this->findAllProductsUseCase->execute($filters),
            status: Response::HTTP_OK
        );
    }

    public function show(string $slug) {

        try{
            return $this->resApi(
                data: $this->findBySlugProductUseCase->execute($slug),
                status: Response::HTTP_OK
            );
        } catch(DataNotFoundException $e) {
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_NOT_FOUND
            );
        }
    }

    public function save(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = new Product(
            Id::randomId(),
            str()->slug($request->input('name')),
            $request->input('name'),
            [],
            $request->input('description'),
            $request->input('quantity'),
            new Amount(
                $request->input('price'),
                new Currency(
                    "$",
                )
            ),
            new Id($request->input('category_id')),
        );

        try {
            $product = $this->createProductUseCase->execute(
                $product
            );

            return $this->resApi(
                data: $this->mapModelToArray($product),
                status: Response::HTTP_CREATED
            );
        } catch(InvalidPermission $e) {
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required',
        ]);

        $product = new Product(
            new Id($id),
            str()->slug($request->input('name')),
            $request->input('name'),
            [],
            $request->input('description'),
            $request->input('quantity'),
            new Amount(
                $request->input('price'),
                new Currency(
                    "$",
                )
            ),
            new Id($request->input('category_id')),
        );

        try {
            $this->updateProductUseCase->execute($product);

            return $this->resApi(
                data: $this->mapModelToArray($product),
                status: Response::HTTP_OK
            );
        } catch(InvalidPermission $e) {

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }
    }

    public function delete(string $id) {

        try {
            $this->deleteProductUseCase->execute(
                new Id($id)
            );

            return $this->resApi(
                data: null,
                status: Response::HTTP_NO_CONTENT
            );
        } catch(DataNotFoundException $e) {

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_NOT_FOUND
            );
        } catch(InvalidPermission $e) {

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }
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
