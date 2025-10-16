<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name'  => 'required|string|max:255',
            'product_desc'  => 'nullable|string|max:255',
            'sku'           => 'required|string|unique:products,sku|max:100',
            'barcode'       => 'required|string|max:100',
            'price'         => 'required|numeric|min:0',
            'cost'          => 'required|numeric|min:0',
            'brand_id'      => 'required|exists:brands,id',
            'category_id'   => 'required|exists:categories,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'product_name'  => 'nombre de producto',
            'product_desc'  => 'descripcion de producto',
            'sku'           => 'codigo unico',
            'barcode'       => 'codigo de barras',
            'price'         => 'precio de lista',
            'cost'          => 'precio de gondola',
            'brand_id'      => 'marca',
            'category_id'   => 'categoria'
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required'    => 'El :attribute es obligatorio',
            'product_name.string'      => 'El :attribute debe ser texto',
            'product_name.max'         => 'El :attribute no debe exceder los 255 caracteres',
            
            'product_desc.string'      => 'La :attribute debe ser texto',
            'product_desc.max'         => 'La :attribute no debe exceder los 255 caracteres',
            
            'sku.required'             => 'El :attribute es obligatorio',
            'sku.string'               => 'El :attribute debe ser texto',
            'sku.unique'               => 'El :attribute ya está registrado',
            'sku.max'                  => 'El :attribute no debe exceder los 100 caracteres',
            
            'barcode.required'         => 'El :attribute es obligatorio',
            'barcode.string'           => 'El :attribute debe ser texto',
            'barcode.max'              => 'El :attribute no debe exceder los 100 caracteres',
            
            'price.required'           => 'El :attribute es obligatorio',
            'price.numeric'            => 'El :attribute debe ser un número',
            'price.min'                => 'El :attribute no puede ser negativo',
            
            'cost.required'            => 'El :attribute es obligatorio',
            'cost.numeric'             => 'El :attribute debe ser un número',
            'cost.min'                 => 'El :attribute no puede ser negativo',
            
            'brand_id.required'        => 'La :attribute es obligatoria',
            'brand_id.exists'          => 'La :attribute seleccionada no existe',
            
            'category_id.required'     => 'La :attribute es obligatoria',
            'category_id.exists'       => 'La :attribute seleccionada no existe'
        ];
    }
}
