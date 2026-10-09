@extends('shops.main', ['title' => $shop->name])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Shop actions" class="app-cmp-form-actions">
            @php
                session()->put('bookmarks.shops.view-products', url()->full());
                session()->put('bookmarks.shops.update-form', url()->full());
                session()->put('bookmarks.shops.update', session()->get('bookmarks.shops.view'));
                session()->put('bookmarks.shops.delete', session()->get('bookmarks.shops.view'));
            @endphp

            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.shops.view') ?? route('shops.index') }}">
                &lt; Back
            </a>

            <a class="app-cl-button app-cl-primary app-cl-filled"
                href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">
                View Products
            </a>

            @can('update', $shop)
                <a class="app-cl-button" href="{{ route('shops.update-form', ['shop' => $shop->code]) }}">
                    Update
                </a>
            @endcan
            @can('delete', $shop)
                <form action="{{ route('shops.delete', ['shop' => $shop->code]) }}" method="post" style="display: inline;">
                    @csrf
                    <button class="app-cl-button app-cl-warnning app-cl-filled" type="submit">
                        Delete
                    </button>
                </form>
            @endcan
        </nav>
    </div>
@endsection

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Code</dt>
        <dd class="app-cl-code">{{ $shop->code }}</dd>

        <dt>Name</dt>
        <dd>{{ $shop->name }}</dd>

        <dt>Owner</dt>
        <dd>{{ $shop->owner }}</dd>

        <dt>Location</dt>
        <dd>
            <span class="app-cl-number">{{ $shop->latitude }}</span>,
            <span class="app-cl-number">{{ $shop->longitude }}</span>
        </dd>

        <dt>Address</dt>
        <dd>
            <pre class="app-cl-address">{{ $shop->address }}</pre>
        </dd>
    </dl>
@endsection
