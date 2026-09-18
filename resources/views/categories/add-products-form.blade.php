@extends('categories.main', ['title' => "{$category->code}: Add Products"])

@section('header')
    @parent

    <search>
        <form action="{{ route('categories.add-products-form', ['category' => $category->code]) }}" method="get"
            class="app-cmp-search-form">
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
                        href="{{ route('categories.add-products-form', ['category' => $category->code]) }}">Clear</a>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav aria-label="Category actions">
            <a class="app-cl-action app-cl-filter"
                href="{{ route('categories.view-products', ['category' => $category->code]) }}">&lt; Back</a>
            <form action="{{ route('categories.add-product', ['category' => $category->code]) }}" id="app-form-add-product"
                method="post">
                @csrf
            </form>
        </nav>
        {{ $products->withQueryString()->links('vendor.pagination.compact') }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Products for Adding to {{ $category->name }}</caption>
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
                        <button class="app-cl-action app-cl-filter" type="submit" form="app-form-add-product"
                            name="product" value="{{ $product->code }}">
                            Add
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
