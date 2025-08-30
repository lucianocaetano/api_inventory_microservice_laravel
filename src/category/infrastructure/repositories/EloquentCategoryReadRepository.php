<?php

namespace Src\category\infrastructure\repositories;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\category\infrastructure\models\Category;
use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class EloquentCategoryReadRepository implements CategoryReadRepository
{

    public function findAll(array $filter): array
    {
        $data = Category::filter($filter)->orderBy('created_at', 'desc')->paginate();

        return [
            "items" => $data->items(),
            'meta' => [
                "total" => $data->total(),
                "current_page" => $data->currentPage(),
                "last_page" => $data->lastPage()
            ],
        ];
    }

    public function findById(Id $id): array
    {
        $data = Category::where('id', $id->value())->first();

        if (!$data)
            throw new DataNotFoundException('Category');

        return [
            "id" => $data->id,
            "name" => $data->name,
            "slug" => $data->slug,
            "parent" => $data->parent,
            "created_at" => $data->created_at,
            "updated_at" => $data->updated_at,
        ];
    }

    public function findBySlug(string $slug): array
    {
        $data = Category::where('slug', $slug)->first();

        if (!$data)
            throw new DataNotFoundException('Category');

        return [
            "id" => $data->id,
            "name" => $data->name,
            "slug" => $data->slug,
            "parent" => $data->parent,
            "created_at" => $data->created_at,
            "updated_at" => $data->updated_at,
        ];
    }

    public function findBySlugWithProducts(string $category_slug): array
    {
        $category = Category::where('slug', $category_slug)->first();

        if (!$category)
            throw new DataNotFoundException('Category');

        $data = Category::where('slug', $category_slug)->orderBy('created_at', 'desc')->with('products')->first();

        return [
            "category" => [
                "id" => $data->id,
                "name" => $data->name,
                "slug" => $data->slug,
                "parent" => $data->parent,
                "created_at" => $data->created_at,
                "updated_at" => $data->updated_at,
            ],
            "products" => $data->products
        ];
    }
}
