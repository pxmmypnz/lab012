@extends('users.main', ['title' => 'Your information'])

@section('header')
    @parent

    @php
        session()->put('bookmarks.users.selves.update-form', url()->full());
        session()->put('bookmarks.users.selves.update', session()->get('bookmarks.users.selves.view'));
    @endphp

    <div class="app-cmp-form-actions">
        <a class="app-cl-button"
            href="{{ session()->get('bookmarks.users.selves.view') ?? '/' }}">&lt; Back</a>
        <a class="app-cl-button app-cl-primary"
            href="{{ route('users.selves.update-form') }}">Update your information</a>
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
