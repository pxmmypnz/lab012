@extends('products.main', ['title' => $product->code])

@section('content')
    <form action="{{ route('products.update', [
        'product' => $product->code,
    ]) }}" method="post"
        class="app-cmp-product-form">
        @csrf

        <label for="product-code">Code</label>
        <input id="product-code" type="text" name="code" value="{{ old('code', $product->code) }}" required />

        <label for="product-name">Name</label>
        <input id="product-name" type="text" name="name" value="{{ old('name', $product->name) }}" required />

        <label for="product-category">Category</label>
        <select id="product-category" name="category" required>
            @foreach ($categories as $category)
                <option value="{{ $category->code }}" @selected(old('category', $product->category->code) === $category->code)>
                    [{{ $category->code }}] {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label for="product-price">Price</label>
        <input id="product-price" type="number" step="any" name="price" value="{{ old('price', $product->price) }}"
            required />

        <label for="product-description">Description</label>
        <textarea id="product-description" name="description" rows="8" required>{{ old('description', $product->description) }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-form-submit" type="submit">Update</button>
        </div>
    </form>
@endsection
