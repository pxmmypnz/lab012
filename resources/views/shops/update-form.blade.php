@extends('shops.main', ['title' => $shop->code])

@section('content')
    <form action="{{ route('shops.update', ['shop' => $shop->code]) }}" method="post" class="app-cmp-data-form">
        @csrf

        <label for="shop-code">Code *</label>
        <input id="shop-code" type="text" name="code" value="{{ old('code', $shop->code) }}" required />

        <label for="shop-name">Name *</label>
        <input id="shop-name" type="text" name="name" value="{{ old('name', $shop->name) }}" required />

        <label for="shop-owner">Owner *</label>
        <input id="shop-owner" type="text" name="owner" value="{{ old('owner', $shop->owner) }}" required />

        <label for="shop-latitude">Latitude *</label>
        <input id="shop-latitude" type="number" step="any" name="latitude"
            value="{{ old('latitude', $shop->latitude) }}" required />

        <label for="shop-longitude">Longitude *</label>
        <input id="shop-longitude" type="number" step="any" name="longitude"
            value="{{ old('longitude', $shop->longitude) }}" required />

        <label for="shop-address">Address *</label>
        <textarea id="shop-address" name="address" rows="8" required>{{ old('address', $shop->address) }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">
                Update
            </button>
        </div>
    </form>
@endsection
