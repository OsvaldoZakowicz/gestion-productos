<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $product_service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->product_service->getAllProducts();

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = $this->product_service->getBrandsForProduct();
        $categories = $this->product_service->getCategoriesForProduct();

        return view('product.form', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $new_product = $this->product_service->createProduct($request->validated());

        return redirect()->route('products.index')->with('message', 'producto: ' . $new_product->product_name . ' creado!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // todo: editar producto
        // servicio
        // request
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
