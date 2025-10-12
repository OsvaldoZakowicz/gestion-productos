<x-layout>
    <div class="row m-4">
        <div class="col-12">
            @if (session('message'))
                <div class="alert alert-secondary my-2">{{ session('message') }}</div>
            @endif

            <a href="{{ route('brands.create') }}" class="btn btn-primary">Nueva Marca</a>
        </div>

        <div class="col-12 mt-4">
            <ul>
                @foreach ($brands as $brand)
                    <li class="mb-2">
                        <strong>{{ $brand->brand_name }}</strong>
                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-warning">Editar</a>
                        <form method="POST" action="{{ route('brands.destroy', $brand) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            {{ $brands->links() }}
        </div>
    </div>
</x-layout>
