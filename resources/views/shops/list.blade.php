@extends('shops.main', ['title' => 'List'])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Shop actions" class="app-cmp-form-actions">
            @php
                session()->put('bookmarks.shops.create-form', url()->full());
                session()->put('bookmarks.shops.create', session()->get('bookmarks.shops.create-form'));
            @endphp


        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('shops.list') }}" method="get">
        <fieldset>
            <legend>Search</legend>

            <div class="app-cmp-data-form">
                <label for="shop-search-term">Term</label>
                <input id="shop-search-term" type="text" name="term" value="{{ $criteria['term'] ?? '' }}" />

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Filter</button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled" href="{{ route('shops.list') }}">Clear</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>


    <a class="app-cl-button app-cl-primary app-cl-filled" href="{{ route('shops.create-form') }}">
        Create Shop
    </a>
    {{ $shops->links() }}
    <table class="app-cmp-data-list">
        <caption>List of Shops</caption>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                <th>No. of Products</th>
            </tr>
        </thead>
        <tbody>
            @php
                session()->put('bookmarks.shops.view', url()->full());
            @endphp

            @foreach ($shops as $shop)
                <tr>
                    <th class="app-cl-code">
                        <a href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                            {{ $shop->code }}
                        </a>
                    </th>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>
                    <td class="app-cl-number">{{ $shop->products_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
