<?php

namespace Src\category\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\category\application\contracts\in\CreateCategoryUseCasePort;
use Src\category\application\contracts\in\DeleteCategoryUseCasePort;
use Src\category\application\contracts\in\FindAllCategoriesUseCasePort;
use Src\category\application\contracts\in\FindBySlugCategoryUseCasePort;
use Src\category\application\contracts\in\FindBySlugWithProductsUseCasePort;
use Src\category\application\contracts\in\UpdateCategoryUseCasePort;
use Src\category\domain\entities\Category;
use Src\shared\domain\exception\InvalidPermission;
use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class CategoryController extends Controller {

    public function __construct (
        private FindAllCategoriesUseCasePort $findAllCategoriesUseCase,
        private FindBySlugCategoryUseCasePort $findBySlugCategoryUseCase,
        private FindBySlugWithProductsUseCasePort $findBySlugWithProductsUseCase,
        private CreateCategoryUseCasePort $createCategoryUseCase,
        private UpdateCategoryUseCasePort $updateCategoryUseCase,
        private DeleteCategoryUseCasePort $deleteCategoryUseCase
    ) {}

    public function index(Request $request) {

        $name = $request->get('name');

        $data = $this->findAllCategoriesUseCase->execute([
            "name" => $name
        ]);

        return $this->resApi($data, Response::HTTP_OK);
    }

    public function show(string $slug) {

        try {
            $data = $this->findBySlugCategoryUseCase->execute($slug);

            return $this->resApi($data, Response::HTTP_OK);
        } catch (DataNotFoundException $e) {

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_NOT_FOUND
            );
        }

    }

    public function find_all_with_products(string $category_slug) {

        $data = $this->findBySlugWithProductsUseCase->execute($category_slug);

        return $this->resApi($data, Response::HTTP_OK);
    }

    public function store(Request $request) {

        try {
            $request->validate([
                'name' => 'required|string',
                'parent' => 'nullable',
            ]);

            $data = $this->createCategoryUseCase->execute(
                new Category(
                    Id::randomId(),
                    $request->get('name'),
                    str()->slug($request->get('name')),
                    $request->get('parent')
                )
            );

            return $this->resApi(
                data: $this->mapModelToArray($data),
                status: Response::HTTP_CREATED
            );
        } catch(InvalidPermission $e) {
            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }
    }

    public function update(string $id, Request $request) {

        $request->validate([
            'name' => 'required|string',
            'parent' => 'nullable',
        ]);

        try{

            $data = $this->updateCategoryUseCase->execute(
                new Category(
                    new Id($id),
                    str()->slug($request->get('name')),
                    $request->get('name'),
                    $request->get('parent')
                )
            );

            return $this->resApi(
                $this->mapModelToArray($data),
                Response::HTTP_OK
            );
        } catch(DataNotFoundException $e) {

            return $this->resApi(
                null,
                Response::HTTP_NOT_FOUND
            );
        } catch(InvalidPermission $e) {

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }
    }

    public function destroy(string $slug) {

        try{

            $this->deleteCategoryUseCase->execute($slug);

            return $this->resApi(
                null,
                Response::HTTP_NO_CONTENT
            );
        } catch(DataNotFoundException $e) {

            return $this->resApi(
                null,
                Response::HTTP_NOT_FOUND
            );
        } catch(InvalidPermission $e) {

            return $this->resApi(
                message: $e->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }
    }

    private function mapModelToArray(Category $category) {
        return [
            'id' => $category->id(),
            'name' => $category->name(),
            'slug' => $category->slug(),
            'parent' => $category->parent(),
        ];
    }
}
