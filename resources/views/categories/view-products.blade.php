@extends('categories.main', ['title' => "{$category->code}: Products"])

@section('header')
    @parent

    <search>
        <form action="{{ route('categories.view-products', ['category' => $category->code]) }}" method="get"
            class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>

                <div class="app-cmp-data-form">
                    <label for="product-search-term">Term</label>
                    <input id="product-search-term" type="search" name="term" value="{{ $criteria['term'] ?? '' }}" />

                    <label for="product-min-price">Min Price</label>
                    <input id="product-min-price" type="number" name="minPrice" value="{{ $criteria['minPrice'] ?? '' }}"
                        step="0.01" />

                    <label for="product-max-price">Max Price</label>
                    <input id="product-max-price" type="number" name="maxPrice" value="{{ $criteria['maxPrice'] ?? '' }}"
                        step="0.01" />
                </div>

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">
                        Filter
                    </button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled"
                            href="{{ route('categories.view-products', ['category' => $category->code]) }}">
                            Clear
                        </a>
                    </div>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-form-actions">
        <nav aria-label="Category actions">
            <a class="app-cl-button app-cl-primary" href="{{ route('categories.view', ['category' => $category->code]) }}">
                &lt; Back
            </a>
        </nav>

        <div class="app-cmp-form-secondary-actions">
            {{ $products->withQueryString()->links('vendor.pagination.compact') }}
        </div>
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Products for {{ $category->name }}</caption>

        <colgroup>
            <col style="width: 10ch">
            <col>
            <col style="width: 12ch">
            <col style="width: 10ch">
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Price</th>
                <th>No. of Shops</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>
                        <a class="app-cl-code" href="{{ route('products.view', ['product' => $product->code]) }}">
                            {{ $product->code }}
                        </a>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                    <td class="app-cl-number">{{ $product->shops_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
