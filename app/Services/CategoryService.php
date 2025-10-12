<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{

    /**
     * obtener un listado de categorias
     * @return $categories LengthAwarePaginator
     */
    public function getAllCategories(): LengthAwarePaginator
    {
        $query = Category::latest();

        return $query->paginate(Category::PAGINATE);
    }

    /**
     * crear una categoria
     * @param array $data datos de categoria validados
     * @return App\Models\Category
     */
    public function createCategory(array $data): Category
    {
        return Category::create([
            'category_name' => $data['category_name'],
            'category_desc' => $data['category_desc'],
        ]);
    }

    /**
     * obtener una categoria
     * @param int $category_id
     * @return App\Models\Category
     */
    public function getCategory(int $category_id): Category
    {
        return Category::findOrFail($category_id);
    }

    /**
     * actualizar una categoria
     * @param int $category_id category
     * @param array $data datos de categoria validados
     * @return bool
     */
    public function updateCategory(int $category_id, array $data): bool
    {
        return Category::where('id', $category_id)->update($data);
    }

    /**
     * eliminar una categoria
     * @param int $category_id
     * @return bool
     */
    public function deleteCategory(int $category_id): bool
    {
        return Category::where('id', $category_id)->delete();
    }

}