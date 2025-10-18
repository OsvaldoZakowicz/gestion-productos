<?php

namespace App\Services;

use App\Exceptions\Category\CategoryAlreadyInUseException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Exceptions\Database\DatabaseTransactionException;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

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
     * @throws DatabaseTransactionException
     */
    public function createCategory(array $data): Category
    {
        try {
            DB::beginTransaction();

            $new_category =  Category::create([
                'category_name' => $data['category_name'],
                'category_desc' => $data['category_desc'],
            ]);

            DB::commit();
            return $new_category;
        } catch (\Throwable $th) {

            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible crear la categoria, intente nuevamente.',
                'categories.index'
            );
        }
    }

    /**
     * obtener una categoria
     * @param int $category_id
     * @return App\Models\Category
     * @throws CategoryNotFoundException
     */
    public function getCategory(int $category_id): Category
    {
        try {
            return Category::findOrFail($category_id);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException();
        }

    }

    /**
     * actualizar una categoria
     * @param int $category_id category
     * @param array $data datos de categoria validados
     * @return bool
     * @throws DatabaseTransactionException
     */
    public function updateCategory(int $category_id, array $data): bool
    {
        try {
            DB::beginTransaction();

            $was_updated = Category::where('id', $category_id)
                ->update($data);

            DB::commit();
            return $was_updated;
        } catch (\Throwable $th) {
            
            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible actualizar la categoria, intente nuevamente.',
                'categories.index'
            );
        }
    }

    /**
     * eliminar una categoria
     * @param int $category_id
     * @return bool
     * @throws DatabaseTransactionException
     * @throws CategoryAlreadyInUseException
     */
    public function deleteCategory(int $category_id): bool
    {
        if ($this->isCategoryAlreadyInUse($category_id)) {
            throw new CategoryAlreadyInUseException();
        }

        try {
            DB::beginTransaction();

            $was_deleted = Category::where('id', $category_id)->delete();

            DB::commit();
            return $was_deleted;
        } catch (\Throwable $th) {
            
            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible borrar la categoria, intente nuevamente.',
                'categories.index'
            );
        }
    }

    /**
     * comprobar si una categoria esta siendo usada por productos
     * @param int $category_id id de categoria
     * @return bool
     */
    private function isCategoryAlreadyInUse(int $category_id): bool
    {
        $category = $this->getCategory($category_id);

        $related_products_count = $category->products->count();

        if ($related_products_count > 0) {
            return true;
        }

        return false;
    }
}
