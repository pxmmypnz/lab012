@extends('products.main', ['title' => "{$product->code}: Shops"])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Product shop actions" class="app-cmp-form-actions">
            @php
                session()->put('bookmarks.products.add-shops-form', url()->full());
            @endphp


            @can('update', $product)
                <form action="{{ route('products.remove-shop', ['product' => $product->code]) }}"
                    id="app-form-remove-shop" method="post">
                    @csrf
                </form>
            @endcan
        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('products.view-shops', ['product' => $product->code]) }}" method="get">
        <fieldset>
            <legend>Search</legend>

            <div class="app-cmp-data-form">
                <label for="shop-search-term">Term</label>
                <input id="shop-search-term" type="text" name="term" value="{{ $criteria['term'] }}" />

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Filter</button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled"
                            href="{{ route('products.view-shops', ['product' => $product->code]) }}">Clear</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    <a class="app-cl-button"
        href="{{ session()->get('bookmarks.products.view-shops') ?? route('products.view', ['product' => $product->code]) }}">
        &lt; Back
    </a>

    @can('update', $product)
        <a class="app-cl-button app-cl-primary app-cl-filled"
            href="{{ route('products.add-shops-form', ['product' => $product->code]) }}">
            Add Shops
        </a>
    @endcan

    {{ $shops->links() }}

    <table class="app-cmp-data-list">
        <caption>List of Shops for {{ $product->name }}</caption>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                <th>No. of Products</th>
                @can('update', $product)
                    <th></th>
                @endcan
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
                    @can('update', $product)
                        <td class="app-cl-action">
                            <button class="app-cl-button app-cl-warnning app-cl-filled" type="submit"
                                form="app-form-remove-shop" name="shop" value="{{ $shop->code }}">
                                Remove
                            </button>
                        </td>
                    @endcan
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shops->links() }}
@endsection
