<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator; // para after()

class CreateCategoryRequest extends FormRequest
{
    /**
     * * NOTAS:
     * - Cada request personalizado extiende de FormRequest,
     * que a su vez extiende de Request. Lo que significa que tengo acceso a metodos
     * utiles como:
     * 
     * - $this->user(), para obtener el usuario en sesion que esta realizando el request
     * - $this->route(), para acceder a la ruta en cuestion, e incluso parametros URI
     * - entre otros metodos.
     */


    /**
     * Indica si el validador debe detenerse la validacion de los demas
     * atributos al encontrar un primer error de validacion
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;


    /**
     * La URI a la que los usuarios deberian ser redireccionados cuando
     * ocurran errores de validacion
     * 
     * Por defecto el redirect se realiza a la locacion previa, si existe este
     * atributo $redirect, el validador redireccionara a la URI proporcionada
     *
     * @var string
     */
    //protected $redirect = '/dashboard';


    /**
     * * Determina si el usuario está autorizado a hacer el request
     * por lo general aqui interactuamos con nuestras puertas de seguridad
     * (authorization gates) o politicas (policies) https://laravel.com/docs/12.x/authorization
     * 
     * si este metodo retorna false, se enviara un codigo de estado 403 (no autorizado),
     * el metodo de controlador vinculado a esta request NO se ejecutara.
     */
    public function authorize(): bool
    {
        // true = todos autorizados
        // aqui se puede implementar logica extra que retorne bool
        return true;
    }

    /**
     * use Illuminate\Support\Str;
     * 
     * Preparar datos para validacion
     * Si necesitamos preparar o sanear datos ANTES de que se ejecuten
     * sobre ellos las reglas de validacion
     * 
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // 'atributo' => 'preparacion',
            // 'slug' => Str::slug($this->slug),
        ]);
    }

    /**
     * Obtener las reglas de validacion que se deben aplicar al request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_name' => 'required|unique:categories,category_name|string|max:100',
            'category_desc' => 'nullable|string|max:255'
        ];
    }

    /**
     * Manejar datos luego de la validacion exitosa con las reglas
     * Cuando necesitamos hacer algo con los datos ya validados,
     * por ejemplo alguna normalizacion 
     */
    protected function passedValidation(): void
    {
        //$this->replace(['name' => 'Taylor']);
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

    /**
     * use Illuminate\Validation\Validator;
     * 
     * Obtener invocables (callables) de validacion o validaciones custom
     * para despues de la validacion inicial de rules()
     * 
     * util cuando necesitamos realizar validaciones extra despues de las iniciales.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                /* if ($this->somethingElseIsInvalid()) {
                    $validator->errors()->add(
                        'field',
                        'Something is wrong with this field!'
                    );
                } */
            }
        ];
    }
}
