@extends('categories.main', ['title' => "Update: {$category->code}"])

@section('header')
    @parent

    <div class="app-cmp-form-actions">
        <nav aria-label="Category actions">
            <a class="app-cl-button app-cl-primary" href="{{ route('categories.view', ['category' => $category->code]) }}">
                &lt; Back
            </a>
        </nav>
    </div>
@endsection

@section('content')
    <form action="{{ route('categories.update', ['category' => $category->code]) }}" method="post" class="app-cmp-data-form">
        @csrf

        <label for="category-code">Code</label>
        <input id="category-code" type="text" name="code" value="{{ old('code', $category->code) }}" required />

        <label for="category-name">Name</label>
        <input id="category-name" type="text" name="name" value="{{ old('name', $category->name) }}" required />

        <label for="category-description">Description</label>
        <textarea id="category-description" name="description" rows="8" required>{{ old('description', $category->description) }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">
                Update
            </button>
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.categories.update-form') ?? route('categories.view', ['category' => $category->code]) }}">
                Cancel
            </a>
        </div>
    </form>
@endsection
