@extends('users.main', ['title' => 'Create'])

@section('content')
    <form action="{{ route('users.create') }}" method="post" class="app-cmp-data-form">
        @csrf

        <label for="user-name">Name</label>
        <input id="user-name" type="text" name="name" value="{{ old('name') }}" required>

        <label for="user-email">Email</label>
        <input id="user-email" type="email" name="email" value="{{ old('email') }}" required>

        <label for="user-password">Password</label>
        <input id="user-password" type="password" name="password" required>

        <label for="user-role">Role</label>
        <select id="user-role" name="role" required>
            <option value="USER" @selected(old('role', 'USER') === 'USER')>USER</option>
            <option value="ADMIN" @selected(old('role') === 'ADMIN')>ADMIN</option>
        </select>

        @include('users.validation-errors')

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Create</button>
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.users.create-form') ?? route('users.index') }}">Cancel</a>
        </div>
    </form>
@endsection
