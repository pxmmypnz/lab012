<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Database - {{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header id="app-main-header">
        <nav>
            <ul class="app-cmp-links">
                <li @class(['app-cl-active' => Route::is('products.*')])>
                    <a href="{{ route('products.list') }}">Product</a>
                </li>
                <li @class(['app-cl-active' => Route::is('shops.*')])>
                    <a href="{{ route('shops.list') }}">Shop</a>
                </li>
                <li @class(['app-cl-active' => Route::is('categories.*')])>
                    <a href="{{ route('categories.list') }}">Category</a>
                </li>
            </ul>
        </nav>
    </header>

    <main id="app-main-content">
        <header>
            @yield('header')
        </header>
        @yield('content')
    </main>

    <footer id="app-main-footer">
        &#xA9; Copyright Phanu's Database.
    </footer>
</body>

</html>
