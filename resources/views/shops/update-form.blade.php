@extends('shops.main', ['title' => $shop->code])

@section('content')
    <form action="{{ route('shops.update', [
        'shop' => $shop->code,
    ]) }}" method="post"
        class="app-cmp-product-form">
        @csrf

        <label for="shop-code">Code</label>
        <input id="shop-code" type="text" name="code" value="{{ old('code', $shop->code) }}" required />

        <label for="shop-name">Name</label>
        <input id="shop-name" type="text" name="name" value="{{ old('name', $shop->name) }}" required />

        <label for="shop-owner">Owner</label>
        <input id="shop-owner" type="text" name="owner" value="{{ old('owner', $shop->owner) }}" required />

        <label for="shop-latitude">Latitude</label>
        <input id="shop-latitude" type="number" name="latitude" value="{{ old('latitude', $shop->latitude) }}"
            step="any" required />

        <label for="shop-longitude">Longitude</label>
        <input id="shop-longitude" type="number" name="longitude" value="{{ old('longitude', $shop->longitude) }}"
            step="any" required />

        <label for="shop-address">Address</label>
        <textarea id="shop-address" name="address" rows="8" required>{{ old('address', $shop->address) }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-form-submit" type="submit">
                Update
            </button>
        </div>
    </form>
@endsection
