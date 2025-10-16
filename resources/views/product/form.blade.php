<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                <div class="form-group">
                    <label for="product_name">Nombre del producto*</label>
                    <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}">
                    @error('product_name')
                        <div>{{ $message }}</div>
                    @enderror                 
                </div>

                <div class="form-group">
                    <label for="product_desc">Descripcion del producto</label>
                    <input type="text" name="product_desc" id="product_desc" class="form-control" value="{{ old('product_desc') }}">
                    @error('product_desc')
                        <div>{{ $message }}</div>
                    @enderror                 
                </div>

                <div class="form-group">
                    <label for="brand_id">Marca</label>
                    <select name="brand_id" id="brand_id" class="form-control">
                        <option value="">seleccione ...</option>
                        @forelse ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                        @empty
                            <option value="">sin marcas registradas!</option>
                        @endforelse
                    </select>
                    @error('brand_id')
                        <div>{{ $message }}</div>
                    @enderror 
                </div>

                <div class="form-group">
                    <label for="category_id">Categoria</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">seleccione ...</option>
                        @forelse ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @empty
                            <option value="">sin categorias registradas!</option>
                        @endforelse
                    </select>
                    @error('category_id')
                        <div>{{ $message }}</div>
                    @enderror 
                </div>

                <div class="form-group">
                    <label for="sku">Código único</label>
                    <input type="text" name="sku" id="sku" placeholder="Ejemplo P000001 ..." class="form-control" value="{{ old('sku') }}">
                    @error('sku')
                        <div>{{ $message }}</div>
                    @enderror                 
                </div>

                <div class="form-group">
                    <label for="barcode">Código de barras</label>
                    <input type="text" name="barcode" id="barcode" placeholder="número del código" class="form-control" value="{{ old('barcode') }}">
                    @error('barcode')
                        <div>{{ $message }}</div>
                    @enderror                 
                </div>

                <div class="form-group">
                    <label for="price">$ Precio de lista</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" placeholder="" class="form-control" value="{{ old('price') }}">
                    @error('price')
                        <div>{{ $message }}</div>
                    @enderror                 
                </div>

                <div class="form-group">
                    <label for="cost">$ Costo en góndola</label>
                    <input type="number" step="0.01" min="0" name="cost" id="cost" placeholder="" class="form-control" value="{{ old('cost') }}">
                    @error('cost')
                        <div>{{ $message }}</div>
                    @enderror                 
                </div>

                <button class="btn btn-primary mt-2">Crear</button>
            </form>
        </div>
    </div>
</x-layout>