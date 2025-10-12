<?php

namespace App\Http\Controllers\Brand;

use App\Models\Brand;
use App\Services\BrandService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;

class BrandController extends Controller
{

    public function __construct(protected BrandService $brand_service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = $this->brand_service->getAllBrands();

        return view('brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brand.form', ['brand' => new Brand()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBrandRequest $request)
    {
        $new_brand = $this->brand_service->createBrand($request->validated());

        return redirect()->route('brands.index')->with('message', 'marca: ' . $new_brand->brand_name . ' creada!');
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
        $brand = $this->brand_service->getBrand($id);

        return view('brand.form', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, int $id)
    {
        $this->brand_service->updateBrand($id, $request->validated());

        return redirect()->route('brands.index')->with('message', 'marca actualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->brand_service->deleteBrand($id);

        return redirect()->route('brands.index')->with('message', 'marca eliminada!');
    }
}
