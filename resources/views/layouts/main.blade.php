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
        <nav class="app-cmp-main-menu" aria-label="Main navigation">
            <ul class="app-cmp-links">
                <li @class(['app-cl-active' => Route::is('products.*')])>
                    <a href="{{ route('products.list') }}">Product</a>
                </li>
                @can('list', \App\Models\Shop::class)
                    <li @class(['app-cl-active' => Route::is('shops.*')])>
                        <a href="{{ route('shops.list') }}">Shop</a>
                    </li>
                @endcan
                @can('list', \App\Models\Category::class)
                    <li @class(['app-cl-active' => Route::is('categories.*')])>
                        <a href="{{ route('categories.list') }}">Category</a>
                    </li>
                @endcan
                @auth
                    @php
                        if (!Route::is('users.selves.*')) {
                            session()->put('bookmarks.users.selves.view', url()->full());
                        }
                    @endphp
                    @can('manage', \App\Models\User::class)
                        <li @class(['app-cl-active' => Route::is('users.*')])>
                            <a href="{{ route('users.list') }}">Users</a>
                        </li>
                    @endcan
                @endauth
            </ul>
            @auth
                <form action="{{ route('logout') }}" method="post" id="app-form-logout">
                    @csrf
                </form>
                <ul class="app-cmp-user-panel">
                    <li><a class="app-cl-user-link"
                            href="{{ route('users.selves.view') }}">{{ \Auth::user()->name }}</a></li>
                    <li>
                        <button class="app-cl-button app-cl-warnning" type="submit"
                            form="app-form-logout">Logout</button>
                    </li>
                </ul>
            @endauth
        </nav>
    </header>

    <main id="app-main-content">
        <div class="app-cmp-notifications">
            @session('status')
                <div role="status">{{ $value }}</div>
            @endsession
        </div>
        <header>
            @yield('header')
        </header>
        @yield('content')
    </main>

    <footer id="app-main-footer">
        &#xA9; Copyright Pamila's Database.
    </footer>
</body>

</html>
