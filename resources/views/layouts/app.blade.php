<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Inventory Management System') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>

<nav class="navbar navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Inventory Management System
        </a>

        @auth

            <div class="d-flex align-items-center">

                <a href="{{ route('dashboard') }}"
                   class="btn btn-outline-light btn-sm me-2">
                    Dashboard
                </a>

                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-light btn-sm me-3">
                    Products
                </a>

                <span class="text-white me-3">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="btn btn-danger btn-sm">
                        Logout
                    </button>
                </form>

            </div>

        @endauth

    </div>
</nav>

<main>
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>