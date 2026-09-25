@extends('categories.main', ['title' => "{$category->code}: Add Products"])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Category add product actions" class="app-cmp-form-actions">
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.categories.add-products-form') ?? route('categories.view-products', ['category' => $category->code]) }}">
                &lt; Back
            </a>

            <form action="{{ route('categories.add-product', ['category' => $category->code]) }}" id="app-form-add-product"
                method="post">
                @csrf
            </form>
        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('categories.add-products-form', ['category' => $category->code]) }}" method="get">
        <fieldset>
            <legend>Search</legend>

            <div class="app-cmp-data-form">
                <label for="product-search-term">Term</label>
                <input id="product-search-term" type="text" name="term" value="{{ $criteria['term'] ?? '' }}" />

                <label for="product-min-price">Min Price</label>
                <input id="product-min-price" type="number" name="minPrice" value="{{ $criteria['minPrice'] ?? '' }}"
                    step="0.01" />

                <label for="product-max-price">Max Price</label>
                <input id="product-max-price" type="number" name="maxPrice" value="{{ $criteria['maxPrice'] ?? '' }}"
                    step="0.01" />

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Filter</button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled"
                            href="{{ route('categories.add-products-form', ['category' => $category->code]) }}">Clear</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $products->links() }}

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
                    <td>{{ $product->category->name }}</td>
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
