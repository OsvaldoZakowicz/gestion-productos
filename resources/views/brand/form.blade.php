<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $brand->exists ? route('brands.update', $brand) : route('brands.store') }}">
                @csrf
                @if ($brand->exists)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label>Nombre de marca</label>
                    <input name="brand_name" class="form-control" value="{{ old('brand_name', $brand->brand_name) }}">
                    @error('brand_name')
                        <div>{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $brand->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
</x-layout>
