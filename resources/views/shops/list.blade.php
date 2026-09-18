@extends('shops.main', ['title' => 'List'])

@section('header')
    @parent

    <search>
        <form action="{{ route('shops.list') }}" method="get" class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>

                <div class="app-cmp-data-form">
                    <label for="shop-search-term">Term</label>
                    <input id="shop-search-term" type="search" name="term" value="{{ $criteria['term'] }}" />
                </div>

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">
                        Filter
                    </button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled" href="{{ route('shops.list') }}">
                            Clear
                        </a>
                    </div>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-form-actions">
        <nav aria-label="Shop actions">
            <a class="app-cl-button app-cl-primary app-cl-filled" href="{{ route('shops.create-form') }}">
                Create Shop
            </a>
        </nav>

        <div class="app-cmp-form-secondary-actions">
            {{ $shops->withQueryString()->links('vendor.pagination.compact') }}
        </div>
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Shops</caption>

        <colgroup>
            <col style="width: 10ch">
            <col>
            <col>
            <col>
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                <th>No. of Products</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($shops as $shop)
                <tr>
                    <td>
                        <a class="app-cl-code" href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                            {{ $shop->code }}
                        </a>
                    </td>

                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>
                    <td class="app-cl-number">{{ $shop->products_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
