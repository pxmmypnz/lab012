@extends('shops.main', ['title' => $shop->name])
@section('header')
    @parent

    <nav class="app-cmp-record-actions" aria-label="Shop actions">
        <a class="app-cl-action app-cl-filter" href="{{ route('shops.view-products', ['shop' => $shop->code]) }}">
            View Products
        </a>

        <a class="app-cl-action app-cl-filter"
            href="{{ route('shops.update-form', [
                'shop' => $shop->code,
            ]) }}">
            Update
        </a>

        <form action="{{ route('shops.delete', [
            'shop' => $shop->code,
        ]) }}" method="post">
            @csrf

            <button class="app-cl-action app-cl-clear" type="submit">
                Delete
            </button>
        </form>
    </nav>
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
            <span class="app-cl-number">{{ $shop->latitude }}</span>
            <b>,</b>
            <span class="app-cl-number">{{ $shop->longitude }}</span>
        </dd>

        <dt>Address</dt>
        <dd>
            <pre class="app-cl-address">{{ $shop->address }}</pre>
        </dd>
    </dl>
@endsection
