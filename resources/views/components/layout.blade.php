<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CRUD Profesional con Laravel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>

    <header class="container">
        <h2>dashboard</h2>
        <nav>
            <ul>
                <li><a href="{{ route('brands.index') }}">marcas</a></li>
                <li><a href="{{ route('categories.index') }}">categorias</a></li>
                <li><a href="{{ route('products.index') }}">productos</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        {{ $slot ?? ''}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>

</html>
