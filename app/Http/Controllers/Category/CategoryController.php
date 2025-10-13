<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $category_service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->category_service->getAllCategories();

        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * envia en la ruta una categoria vacia
     */
    public function create()
    {
        return view('category.form', ['category' => new Category()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        $new_category = $this->category_service->createCategory($request->validated());

        return redirect()->route('categories.index')->with('message', 'categoria: ' . $new_category->category_name . ' creada!');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $category = $this->category_service->getCategory($id);

        return view('category.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id): RedirectResponse
    {
        $this->category_service->updateCategory($id, $request->validated());

        return redirect()->route('categories.index')->with('message', 'categoria actualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->category_service->deleteCategory($id);

        return redirect()->route('categories.index')->with('message', 'categoria eliminada!');
    }
}
