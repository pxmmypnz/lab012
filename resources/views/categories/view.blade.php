@extends('categories.main', ['title' => $category->name])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Category actions" class="app-cmp-form-actions"
            style="display: flex; flex-direction: row; align-items: center;">

            {{-- Store current Category View URL into session for child pages to link back to --}}
            @php
                session()->put('bookmarks.categories.view', url()->full());
            @endphp

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
