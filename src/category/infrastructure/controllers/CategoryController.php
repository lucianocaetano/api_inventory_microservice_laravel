<?php

namespace Src\category\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\category\application\use_cases\CreateCategoryUseCase;
use Src\category\application\use_cases\DeleteCategoryUseCase;
use Src\category\application\use_cases\FindAllCategoriesUseCase;
use Src\category\application\use_cases\FindBySlugCategoryUseCase;
use Src\category\application\use_cases\FindBySlugWithProductsUseCase;
use Src\category\application\use_cases\UpdateCategoryUseCase;
use Src\category\domain\entities\Category;
use Src\shared\domain\value_objects\Id;

class CategoryController extends Controller {

    public function __construct (
        private FindAllCategoriesUseCase $findAllCategoriesUseCase,
        private FindBySlugCategoryUseCase $findBySlugCategoryUseCase,
        private FindBySlugWithProductsUseCase $findBySlugWithProductsUseCase,
        private CreateCategoryUseCase $createCategoryUseCase,
        private UpdateCategoryUseCase $updateCategoryUseCase,
        private DeleteCategoryUseCase $deleteCategoryUseCase
    ) {}

    public function index(Request $request) {

        $name = $request->get('name');

        $data = $this->findAllCategoriesUseCase->execute([
            "name" => $name
        ]);

        return $this->resApi($data, Response::HTTP_OK);
    }

    public function show(string $slug) {

        $data = $this->findBySlugCategoryUseCase->execute($slug);

        return $this->resApi($data, Response::HTTP_OK);
    }

    public function find_all_with_products(string $category_slug) {

        $data = $this->findBySlugWithProductsUseCase->execute($category_slug);

        return $this->resApi($data, Response::HTTP_OK);
    }

    public function store(Request $request) {

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
            $this->mapModelToArray($data),
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, string $id) {

        $request->validate($request, [
            'name' => 'required|string',
            'parent' => 'nullable',
        ]);

        $data = $this->updateCategoryUseCase->execute(
            new Category(
                new Id($id),
                $request->get('name'),
                str()->slug($request->get('name'))->value(),
                $request->get('parent')
            )
        );

        return $this->resApi(
            $this->mapModelToArray($data),
            Response::HTTP_OK
        );
    }

    public function destroy(string $slug) {

        $this->deleteCategoryUseCase->execute($slug);

        return $this->resApi(
            null,
            Response::HTTP_NO_CONTENT
        );
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
