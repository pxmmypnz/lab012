@extends('shops.main', ['title' => 'Create'])

@section('content')
    <form action="{{ route('shops.create') }}" method="post" class="app-cmp-data-form">
        @csrf

        <label for="shop-code">Code *</label>
        <input id="shop-code" type="text" name="code" value="{{ old('code') }}" required />

        <label for="shop-name">Name *</label>
        <input id="shop-name" type="text" name="name" value="{{ old('name') }}" required />

        <label for="shop-owner">Owner *</label>
        <input id="shop-owner" type="text" name="owner" value="{{ old('owner') }}" required />

        <label for="shop-latitude">Latitude *</label>
        <input id="shop-latitude" type="number" name="latitude" value="{{ old('latitude') }}" step="any" required />

        <label for="shop-longitude">Longitude *</label>
        <input id="shop-longitude" type="number" name="longitude" value="{{ old('longitude') }}" step="any" required />

        <label for="shop-address">Address *</label>
        <textarea id="shop-address" name="address" rows="8" required>{{ old('address') }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">
                Create
            </button>
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.shops.create-form') ?? route('shops.index') }}">
                Cancel
            </a>
        </div>
    </form>
@endsection
