@extends('categories.main', ['title' => 'Create'])

@section('content')
    <form action="{{ route('categories.create') }}" method="post" class="app-cmp-data-form">
        @csrf

        <label for="category-code">Code *</label>
        <input id="category-code" type="text" name="code" value="{{ old('code') }}" required />

        <label for="category-name">Name *</label>
        <input id="category-name" type="text" name="name" value="{{ old('name') }}" required />

        <label for="category-description">Description *</label>
        <textarea id="category-description" name="description" rows="8" required>{{ old('description') }}</textarea>

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Create</button>
        </div>
    </form>
@endsection
