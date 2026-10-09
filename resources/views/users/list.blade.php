@extends('users.main', ['title' => 'List'])

@section('header')
    @parent

    @php
        session()->put('bookmarks.users.create-form', url()->full());
        session()->put('bookmarks.users.create', session()->get('bookmarks.users.create-form'));
        session()->put('bookmarks.users.view', url()->full());
    @endphp

    <form action="{{ route('users.list') }}" method="get">
        <fieldset>
            <legend>Search users</legend>
            <div class="app-cmp-data-form">
                <label for="user-term">Email, name, or role</label>
                <input id="user-term" type="search" name="term" value="{{ $criteria['term'] ?? '' }}">
                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Search</button>
                    <a class="app-cl-button app-cl-warnning app-cl-filled" href="{{ route('users.list') }}">Clear</a>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="app-cmp-form-actions">
        <a class="app-cl-button app-cl-primary app-cl-filled" href="{{ route('users.create-form') }}">Create User</a>
        {{ $users->withQueryString()->links('vendor.pagination.compact') }}
    </div>
@endsection

@section('content')
    <table class="app-cmp-data-list">
        <caption>List of Users</caption>
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th><a href="{{ route('users.view', ['user' => $user->email]) }}">{{ $user->email }}</a></th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
