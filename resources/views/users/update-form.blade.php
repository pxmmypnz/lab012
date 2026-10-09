@extends('users.main', ['title' => $user->email])

@section('header')
    @parent
    <a class="app-cl-button" href="{{ route('users.view', ['user' => $user->email]) }}">&lt; Back</a>
@endsection

@section('content')
    <form action="{{ route('users.update', ['user' => $user->email]) }}" method="post"
        class="app-cmp-data-form app-cmp-user-update-form">
        @csrf

        <label for="user-email">Email</label>
        <input id="user-email" type="email" value="{{ $user->email }}" readonly>

        <label for="user-name">Name <span class="app-cl-required">*</span></label>
        <input id="user-name" type="text" name="name" value="{{ old('name', $user->name) }}" required>

        @can('updateRole', $user)
            <label for="user-role">Role</label>
            <select id="user-role" name="role" required>
                <option value="USER" @selected(old('role', $user->role) === 'USER')>USER</option>
                <option value="ADMIN" @selected(old('role', $user->role) === 'ADMIN')>ADMIN</option>
            </select>
        @else
            <label for="user-role">Role</label>
            <input id="user-role" type="text" value="{{ $user->role }}" readonly>
        @endcan

        <label for="user-password">Password</label>
        <input id="user-password" type="password" name="password"
            placeholder="Leave blank if you don't want to update">

        @include('users.validation-errors')

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Update</button>
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.users.update-form') ?? route('users.view', ['user' => $user->email]) }}">Cancel</a>
        </div>
    </form>
@endsection
