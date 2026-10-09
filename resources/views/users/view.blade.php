@extends('users.main', ['title' => $user->name])

@section('header')
    @parent

    @php
        session()->put('bookmarks.users.delete', session()->get('bookmarks.users.view'));
        session()->put('bookmarks.users.update-form', url()->full());
        session()->put('bookmarks.users.update', session()->get('bookmarks.users.view'));
    @endphp

    <div class="app-cmp-form-actions">
        <a class="app-cl-button"
            href="{{ session()->get('bookmarks.users.view') ?? route('users.index') }}">&lt; Back</a>
        @if ($isSelf)
            <a class="app-cl-button app-cl-primary"
                href="{{ route('users.selves.update-form') }}">Update your information</a>
        @else
            <a class="app-cl-button app-cl-primary"
                href="{{ route('users.update-form', ['user' => $user->email]) }}">Update</a>
            <form action="{{ route('users.delete', ['user' => $user->email]) }}" method="post">
                @csrf
                <button class="app-cl-button app-cl-warnning app-cl-filled" type="submit">Delete</button>
            </form>
        @endif
    </div>
@endsection

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Email</dt>
        <dd>{{ $user->email }}</dd>
        <dt>Name</dt>
        <dd>{{ $user->name }}</dd>
        <dt>Role</dt>
        <dd>{{ $user->role }}</dd>
    </dl>
@endsection
