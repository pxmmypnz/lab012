@extends('shops.main', ['title' => "{$shop->code}: Add Products"])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Shop add product actions" class="app-cmp-form-actions">
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.shops.add-products-form') ?? route('shops.view-products', ['shop' => $shop->code]) }}">
                &lt; Back
            </a>

            <form action="{{ route('shops.add-product', ['shop' => $shop->code]) }}" id="app-form-add-product" method="post">
                @csrf
            </form>
        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}" method="get">
        <fieldset>
            <legend>Search</legend>

            <div class="app-cmp-data-form">
                <label for="product-term">Term</label>
                <input id="product-term" type="text" name="term" value="{{ $criteria['term'] }}" />

                <label for="product-min-price">Min Price</label>
                <input id="product-min-price" type="number" step="any" name="minPrice"
                    value="{{ $criteria['minPrice'] }}" />

                <label for="product-max-price">Max Price</label>
                <input id="product-max-price" type="number" step="any" name="maxPrice"
                    value="{{ $criteria['maxPrice'] }}" />

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Filter</button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled"
                            href="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}">Clear</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $products->links() }}

    <table class="app-cmp-data-list">
        <caption>List of Products for Adding to {{ $shop->name }}</caption>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>No. of Shops</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                session()->put('bookmarks.products.view', url()->full());
            @endphp

            @foreach ($products as $product)
                <tr>
                    <th class="app-cl-code">
                        <a href="{{ route('products.view', ['product' => $product->code]) }}">
                            {{ $product->code }}
                        </a>
                    </th>
                    <td>{{ $product->name }}</td>
                    <td>
                        @if ($product->category)
                            <a href="{{ route('categories.view', ['category' => $product->category->code]) }}">
                                {{ $product->category->name }}
                            </a>
                        @endif
                    </td>
                    <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                    <td class="app-cl-number">{{ $product->shops_count }}</td>
                    <td class="app-cl-action">
                        <button class="app-cl-button app-cl-primary app-cl-filled" type="submit"
                            form="app-form-add-product" name="product" value="{{ $product->code }}">
                            Add
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
@endsection
