@extends('shops.main', ['title' => "{$shop->code}: Products"])

@section('header')
    @parent

    <search>
        <form action="{{ route('shops.view-products', ['shop' => $shop->code]) }}" method="get" class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>

                <div class="app-cmp-search-fields">
                    <label for="product-search-term">Term</label>
                    <input id="product-search-term" type="search" name="term" value="{{ $criteria['term'] }}" />

                    <label for="product-min-price">Min Price</label>
                    <input id="product-min-price" type="number" name="minPrice" value="{{ $criteria['minPrice'] }}"
                        step="0.01" />

                    <label for="product-max-price">Max Price</label>
                    <input id="product-max-price" type="number" name="maxPrice" value="{{ $criteria['maxPrice'] }}"
                        step="0.01" />
                </div>

                <div class="app-cmp-search-actions">
                    <button class="app-cl-action app-cl-filter" type="submit">Filter</button>
                    <a class="app-cl-action app-cl-clear"
                        href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">Clear</a>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav aria-label="Shop actions">
            <a class="app-cl-action app-cl-filter" href="{{ route('shops.view', ['shop' => $shop->code]) }}">&lt; Back</a>
            <a class="app-cl-action app-cl-filter" href="{{ route('shops.add-products-form', ['shop' => $shop->code]) }}">
                Add Products
            </a>
            <form action="{{ route('shops.remove-product', ['shop' => $shop->code]) }}" id="app-form-remove-product"
                method="post">
                @csrf
            </form>
        </nav>
        {{ $products->withQueryString()->links('vendor.pagination.compact') }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Products for {{ $shop->name }}</caption>
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
            @foreach ($products as $product)
                <tr>
                    <th>
                        <a class="app-cl-code app-cl-button app-cl-primary"
                            href="{{ route('products.view', ['product' => $product->code]) }}">{{ $product->code }}</a>
                    </th>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                    <td class="app-cl-number">{{ $product->shops_count }}</td>
                    <td>
                        <button class="app-cl-action app-cl-clear" type="submit" form="app-form-remove-product"
                            name="product" value="{{ $product->code }}">
                            Remove
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
