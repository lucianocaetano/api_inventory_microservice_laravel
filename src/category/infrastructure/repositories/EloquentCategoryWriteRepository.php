<?php

namespace Src\category\infrastructure\repositories;

use Src\category\domain\entities\Category;
use Src\category\domain\repositories\CategoryRepository;
use Src\category\infrastructure\models\Category as ModelsCategory;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class EloquentCategoryWriteRepository implements CategoryRepository
{

    public function save(Category $category): Category
    {

        ModelsCategory::create([
            'id' => $category->id(),
            'name' => $category->name(),
            'slug' => $category->slug(),
            'parent' => $category->parent()
        ]);

        return $category;
    }

    public function update(Category $category): Category
    {

        $model = ModelsCategory::find($category->id());

        $model->update([
            'id' => $category->id(),
            'name' => $category->name(),
            'slug' => $category->slug(),
            'parent' => $category->parent()
        ]);

        return $category;
    }

    public function delete(string $category_slug)
    {

        $model = ModelsCategory::where('slug', $category_slug)->first();

        if(!$model)
            throw new DataNotFoundException('Category');

        ModelsCategory::deleted($category_slug);
    }
}
