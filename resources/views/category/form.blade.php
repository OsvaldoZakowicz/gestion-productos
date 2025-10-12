<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-4">

            <!-- 
                formulario para crear o editar:
                - si la ruta que retorna este form contiene una categoria: action -> actualizar, method PUT
                - caso contrario: action -> almacenar nueva, method POST
            -->

            <form method="POST"
                action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}">
                @csrf
                @if ($category->exists)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="category_name">Nombre de categoría</label>
                    <input type="text" name="category_name" id="category_name" class="form-control"
                        value="{{ old('category_name', $category->category_name) }}">
                    @error('category_name')
                        <div>{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_desc">Descripción de categoría</label>
                    <input type="text" name="category_desc" id="category_desc" class="form-control"
                        value="{{ old('category_desc', $category->category_desc) }}">
                    @error('category_desc')
                        <div>{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $category->exists ? 'Actualizar' : 'Crear' }}</button>

            </form>
        </div>
    </div>
</x-layout>
