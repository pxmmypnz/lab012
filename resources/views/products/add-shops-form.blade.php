@extends('products.main', ['title' => "{$product->code}: Add Shops"])

@section('header')
    @parent

    <search>
        <form action="{{ route('products.add-shops-form', ['product' => $product->code]) }}" method="get"
            class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>

                <div class="app-cmp-search-fields">
                    <label for="shop-search-term">Term</label>
                    <input id="shop-search-term" type="search" name="term" value="{{ $criteria['term'] }}" />
                </div>

                <div class="app-cmp-search-actions">
                    <button class="app-cl-action app-cl-filter" type="submit">Filter</button>
                    <a class="app-cl-action app-cl-clear"
                        href="{{ route('products.add-shops-form', ['product' => $product->code]) }}">Clear</a>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-links-bar">
        <nav aria-label="Product actions">
            <a class="app-cl-action app-cl-filter" href="{{ route('products.view-shops', ['product' => $product->code]) }}">
                &lt; Back
            </a>
            <form action="{{ route('products.add-shop', ['product' => $product->code]) }}" id="app-form-add-shop"
                method="post">
                @csrf
            </form>
        </nav>
        {{ $shops->withQueryString()->links('vendor.pagination.compact') }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Shops for Adding to {{ $product->name }}</caption>
        <colgroup>
            <col style="width: 10ch">
            <col>
            <col>
            <col style="width: 14ch">
            <col style="width: 8ch">
        </colgroup>
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
            @foreach ($shops as $shop)
                <tr>
                    <th>
                        <a class="app-cl-code app-cl-button app-cl-primary"
                            href="{{ route('shops.view', ['shop' => $shop->code]) }}">{{ $shop->code }}</a>
                    </th>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>
                    <td class="app-cl-number">{{ $shop->products_count }}</td>
                    <td>
                        <button class="app-cl-action app-cl-filter" type="submit" form="app-form-add-shop" name="shop"
                            value="{{ $shop->code }}">
                            Add
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
