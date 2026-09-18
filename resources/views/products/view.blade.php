@extends('products.main', ['title' => $product->name])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Product actions" class="app-cmp-form-actions">
            <a class="app-cl-button app-cl-primary" href="{{ route('products.view-shops', ['product' => $product->code]) }}">
                View Shops
            </a>

            <a class="app-cl-button app-cl-primary app-cl-filled"
                href="{{ route('products.update-form', [
                    'product' => $product->code,
                ]) }}">
                Update
            </a>

            <form action="{{ route('products.delete', [
                'product' => $product->code,
            ]) }}"
                method="post">
                @csrf
                <button class="app-cl-button app-cl-warnning app-cl-filled" type="submit">Delete</button>
            </form>
        </nav>
    </div>
@endsection

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Code</dt>
        <dd class="app-cl-code">{{ $product->code }}</dd>

        <dt>Name</dt>
        <dd>{{ $product->name }}</dd>

        <dt>Category</dt>
        <dd>
            <a class="app-cl-code" href="{{ route('categories.view', ['category' => $product->category->code]) }}">
                [{{ $product->category->code }}]
            </a>
            {{ $product->category->name }}
        </dd>

        <dt>Price</dt>
        <dd class="app-cl-number">{{ number_format($product->price, 2) }}</dd>
    </dl>

    <pre class="app-cl-description">{{ $product->description }}</pre>
@endsection
