@extends('products.main', ['title' => 'Create'])

@section('content')
    <form action="{{ route('products.create') }}" method="post" class="app-cmp-data-form">
        @csrf

        <label for="product-code">Code *</label>
        <input id="product-code" type="text" name="code" value="{{ old('code') }}" required />

        <label for="product-name">Name *</label>
        <input id="product-name" type="text" name="name" value="{{ old('name') }}" required />

        <label for="product-category">Category *</label>
        <select id="product-category" name="category" required>
            <option value="">--- Please Select Category ---</option>
            @foreach ($categories as $category)
                <option value="{{ $category->code }}" @selected(old('category') === $category->code)>
                    [{{ $category->code }}] {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label for="product-price">Price *</label>
        <input id="product-price" type="number" step="any" name="price" value="{{ old('price') }}" required />

        <label for="product-description">Description *</label>
        <textarea id="product-description" name="description" rows="8" required>{{ old('description') }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Create</button>
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.products.create-form') ?? route('products.list') }}">
                Cancel
            </a>
        </div>
    </form>
@endsection
