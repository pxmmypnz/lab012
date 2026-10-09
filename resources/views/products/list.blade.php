@extends('products.main', ['title' => 'List'])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Product actions" class="app-cmp-form-actions">
            @php
                session()->put('bookmarks.products.create-form', url()->full());
                session()->put('bookmarks.products.create', session()->get('bookmarks.products.create-form'));
                session()->put('bookmarks.categories.view', url()->full());
            @endphp


        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('products.list') }}" method="get">
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
                        <a class="app-cl-button app-cl-warnning app-cl-filled" href="{{ route('products.list') }}">Clear</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>


    <a class="app-cl-button app-cl-primary app-cl-filled" href="{{ route('products.create-form') }}">
        Create Product
    </a>
    {{ $products->links() }}
    <table class="app-cmp-data-list">
        <caption>List of Products</caption>
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
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
