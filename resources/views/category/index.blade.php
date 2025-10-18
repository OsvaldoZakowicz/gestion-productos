<x-layout>
    <div class="row m-4">
        <div class="col-12">
            
            @if (session('exception'))
                <div class="alert alert-info my-2">{{ session('exception') }}</div>
            @endif
            
            @if (session('message'))
                <div class="alert alert-secondary my-2">{{ session('message') }}</div>
            @endif

            <a href="{{ route('categories.create') }}" class="btn btn-primary">Nueva Categoría</a>
        </div>

        <div class="col-12 mt-4">
            <ul>
                @foreach ($categories as $category)
                    <li class="mb-2">
                        <strong>{{ $category->category_name }}</strong>
                        <span>{{ $category->category_desc }}</span>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Editar</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            {{ $categories->links() }}
        </div>
    </div>
</x-layout>