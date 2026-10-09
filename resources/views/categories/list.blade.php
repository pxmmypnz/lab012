@extends('categories.main', ['title' => 'List'])

@section('header')
    @parent

    @php
        session()->put('bookmarks.categories.create-form', url()->full());
        session()->put('bookmarks.categories.create', session()->get('bookmarks.categories.create-form'));
        session()->put('bookmarks.categories.view', url()->full());
    @endphp

    <search>
        <form action="{{ route('categories.list') }}" method="get" class="app-cmp-search-form">
            <fieldset>
                <legend>Search</legend>

                <div class="app-cmp-data-form">
                    <label for="category-search-term">Term</label>
                    <input id="category-search-term" type="search" name="term" value="{{ $criteria['term'] }}" />
                </div>

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">
                        Filter
                    </button>

                    <div class="app-cmp-form-secondary-actions">
                        <a class="app-cl-button app-cl-warnning app-cl-filled" href="{{ route('categories.list') }}">
                            Clear
                        </a>
                    </div>
                </div>
            </fieldset>
        </form>
    </search>

    <div class="app-cmp-form-actions">
        <nav aria-label="Category actions">
            <a class="app-cl-button app-cl-primary app-cl-filled" href="{{ route('categories.create-form') }}">
                Create Category
            </a>
        </nav>

        <div class="app-cmp-form-secondary-actions">
            {{ $categories->withQueryString()->links('vendor.pagination.compact') }}
        </div>
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Categories</caption>

        <colgroup>
            <col style="width: 10ch">
            <col>
            <col style="width: 16ch">
        </colgroup>

        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>No. of Products</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>
                        <a class="app-cl-code" href="{{ route('categories.view', ['category' => $category->code]) }}">
                            {{ $category->code }}
                        </a>
                    </td>

                    <td>{{ $category->name }}</td>
                    <td class="app-cl-number">
                        {{ $category->products_count }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
