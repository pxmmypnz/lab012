@extends('products.main', ['title' => 'List'])

@section('header')
    @parent

    <search>
        <form action="{{ route('products.list') }}" method="get" class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>

                <div class="app-cmp-data-form">
                    <label for="product-search-term">Term</label>
                    <input id="product-search-term" type="search" name="term" value="{{ $criteria['term'] }}" />

                    <label for="product-min-price">Min Price</label>
                    <input id="product-min-price" type="number" name="minPrice" value="{{ $criteria['minPrice'] }}"
                        step="0.01" />

                    <label for="product-max-price">Max Price</label>
                    <input id="product-max-price" type="number" name="maxPrice" value="{{ $criteria['maxPrice'] }}"
                        step="0.01" />
                </div>

                <div class="app-cmp-form-actions">
                    <button class="app-cl-action app-cl-filter" type="submit">Filter</button>
                    <a class="app-cl-action app-cl-clear" href="{{ route('products.list') }}">Clear</a>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-links-bar"
        style="display: flex; justify-content: space-between; align-items: center; margin: 10px 0;">
        <nav aria-label="Product actions">
            <a class="app-cl-button app-cl-primary app-cl-filled" href="{{ route('products.create-form') }}">
                Create Product
            </a>
        </nav>
        {{ $products->withQueryString()->links('vendor.pagination.compact') }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Products</caption>
        <colgroup>
            <col style="width: 10ch">
            <col>
            <col style="width: 13ch">
            <col style="width: 13ch">
        </colgroup>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>No. of Shops</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <th>
                        <a class="app-cl-code" href="{{ route('products.view', ['product' => $product->code]) }}">
                            {{ $product->code }}
                        </a>
                    </th>
                    <td>{{ $product->name }}</td>
                    <td>
                        <a
                            href="{{ route('categories.view', [
                                'category' => $product->category->code,
                            ]) }}">
                            {{ $product->category->name }}
                        </a>
                    </td>
                    <td class="app-cl-number">{{ number_format($product->price, 2) }}</td>
                    <td class="app-cl-number">{{ $product->shops_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
