@extends('products.main', ['title' => "{$product->code}: Add Shops"])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Product add shop actions" class="app-cmp-form-actions">
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.products.add-shops-form') ?? route('products.view-shops', ['product' => $product->code]) }}">
                &lt; Back
            </a>

            <form action="{{ route('products.add-shop', ['product' => $product->code]) }}" id="app-form-add-shop"
                method="post">
                @csrf
            </form>
        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('products.add-shops-form', ['product' => $product->code]) }}" method="get">
        <fieldset>
            <legend>Search</legend>

            <div class="app-cmp-data-form">
                <label for="shop-search-term">Term</label>
                <input id="shop-search-term" type="text" name="term" value="{{ $criteria['term'] }}" />

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Filter</button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled"
                            href="{{ route('products.add-shops-form', ['product' => $product->code]) }}">Clear</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $shops->links() }}

    <table class="app-cmp-data-list">
        <caption>List of Shops for Adding to {{ $product->name }}</caption>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                <th>No. of Products</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                session()->put('bookmarks.shops.view', url()->full());
            @endphp

            @foreach ($shops as $shop)
                <tr>
                    <th class="app-cl-code">
                        @can('view', $shop)
                            <a href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                                {{ $shop->code }}
                            </a>
                        @else
                            {{ $shop->code }}
                        @endcan
                    </th>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>
                    <td class="app-cl-number">{{ $shop->products_count }}</td>
                    <td class="app-cl-action">
                        <button class="app-cl-button app-cl-primary app-cl-filled" type="submit" form="app-form-add-shop"
                            name="shop" value="{{ $shop->code }}">
                            Add
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shops->links() }}
@endsection
