@extends('categories.main', ['title' => $category->name])

@section('header')
    @parent

    @php
        session()->put('bookmarks.categories.view-products', url()->full());
        session()->put('bookmarks.categories.update-form', url()->full());
        session()->put('bookmarks.categories.update', session()->get('bookmarks.categories.view'));
        session()->put('bookmarks.categories.delete', session()->get('bookmarks.categories.view'));
    @endphp

    <div class="app-cmp-form-actions">
        <nav aria-label="Category actions" class="app-cmp-form-actions"
            style="display: flex; flex-direction: row; align-items: center;">

            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.categories.view') ?? route('categories.index') }}">
                &lt; Back
            </a>

            <a class="app-cl-button app-cl-primary"
                href="{{ route('categories.view-products', ['category' => $category->code]) }}" style="white-space: nowrap;">
                View Products
            </a>

            <a class="app-cl-button app-cl-primary app-cl-filled"
                href="{{ route('categories.update-form', [
                    'category' => $category->code,
                ]) }}">
                Update
            </a>

            <form action="{{ route('categories.delete', [
                'category' => $category->code,
            ]) }}"
                method="post" style="margin: 0;">
                @csrf
                <button class="app-cl-button app-cl-warnning app-cl-filled" type="submit">Delete</button>
            </form>
        </nav>
    </div>
@endsection

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Code</dt>
        <dd class="app-cl-code">{{ $category->code }}</dd>

        <dt>Name</dt>
        <dd>{{ $category->name }}</dd>
    </dl>

    <pre class="app-cl-description">{{ $category->description }}</pre>
@endsection
