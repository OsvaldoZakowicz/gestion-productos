<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\MessageService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $product_service,
        protected MessageService $message_service
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
        $product = new Product(); // variable producto vacia

        return view('product.form', compact('brands', 'categories', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $new_product = $this->product_service->createProduct($request->validated());

        return redirect()->route('products.index')->with(
            MessageService::SESSION_KEY,
            $this->message_service->get('resource_created')
        );
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
        $brands = $this->product_service->getBrandsForProduct();
        $categories = $this->product_service->getCategoriesForProduct();
        $product = $this->product_service->getProduct($id);

        return view('product.form', compact('brands', 'categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $this->product_service->updateProduct($request->validated(), $id);

        return redirect()->route('products.index')->with(
            MessageService::SESSION_KEY,
            $this->message_service->get('resource_edited')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->product_service->deleteProduct($id);

        return redirect()->route('products.index')->with(
            MessageService::SESSION_KEY,
            $this->message_service->get('resource_deleted')
        );
    }
}
