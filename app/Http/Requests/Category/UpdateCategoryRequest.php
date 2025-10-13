<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
             'category_name' => [
                'required',
                Rule::unique('categories', 'category_name')->ignore($this->route('category')),
                'string',
                'max:255'
             ],
            'category_desc' => 'nullable|string|max:255'
        ];
    }

    /**
     * Obtener nombres de atributos personalizados
     * reemplaza el nombre de los atributos del request para los mensajes de error
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_name' => 'nombre de categoria',
            'category_desc' => 'descripcion de categoria',
        ];
    }

    /**
     * Obtener los mensajes de error personalizados para las reglas
     * si no esta presente, se usan mensajes de validacion por defecto
     * :attribute usa el name="" definido en cada input del request, o los nombres de atributos custom de attributes()
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_name.required' => 'El :attribute es requerido',
            'category_name.unique'   => 'Ya existe un :attribute con el mismo nombre',
            'category_name.string'   => 'El :attribute debe ser de tipo texto',
            'category_name.max'      => 'El :attribute debe ser de hasta :max caracteres',

            'category_desc.string' => 'El :attribute debe ser de tipo texto',
            'category_desc.max'    => 'El :attribute debe ser de hasta :max caracteres',

        ];
    }
}
