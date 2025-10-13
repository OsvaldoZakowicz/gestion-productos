<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
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
     * $this->route('brand') obtiene el id de marca desde la ruta de recurso, por el parametro 'brand'
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_name' => [
                'required',
                Rule::unique('brands', 'brand_name')->ignore($this->route('brand')),
                'string',
                'max:255',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'brand_name' => 'nombre de marca',
        ];
    }

    public function messages(): array
    {
        return [
            'brand_name.required' => 'El :attribute es requerido',
            'brand_name.unique'   => 'Ya existe un :attribute con el mismo nombre',
            'brand_name.string'   => 'El :attribute debe ser de tipo texto',
            'brand_name.max'      => 'El :attribute debe ser de hasta :max caracteres',
        ];
    }
}
