<x-layout>
    <div class="row m-4">
        <div class="col-12">
            @if (session('message'))
                <div class="alert alert-secondary my-2">{{ session('message') }}</div>
            @endif

            <a href="{{ route('products.create') }}" class="btn btn-primary">Nuevo Producto</a>
        </div>

        <div class="col-12 mt-4">
            <ul>
                @forelse ($products as $product)
                    <li class="mb-2">
                        <strong>{{ $product->product_name }}</strong>
                        <span>{{ $product->product_desc }}, </span>
                        <span>costo: ${{ $product->cost }}, </span>
                        <span>marca: {{ $product->brand->brand_name }}, </span>
                        <span>categoria: {{ $product->category->category_name }}</span>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Editar</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger">Eliminar</button>
                        </form>
                    </li>
                @empty
                    <li>Sin productos</li>
                @endforelse
            </ul>

            {{ $products->links() }}
        </div>
    </div>
</x-layout>