@extends('users.main', ['title' => 'Self'])

@section('content')
    <form action="{{ route('users.selves.update') }}" method="post"
        class="app-cmp-data-form app-cmp-self-edit-form">
        @csrf

        <label for="user-email">Email</label>
        <output id="user-email">{{ $user->email }}</output>

        <label for="user-name">Name <span class="app-cl-required">*</span></label>
        <input id="user-name" type="text" name="name" value="{{ old('name', $user->name) }}" required>

        <label for="user-password">Password</label>
        <input id="user-password" type="password" name="password"
            placeholder="Leave blank if you don't want to update">

        @include('users.validation-errors')

        <div class="app-cmp-form-actions">
            <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Update</button>
            <a class="app-cl-button"
                href="{{ session()->get('bookmarks.users.selves.update-form') ?? route('users.selves.view') }}">Cancel</a>
        </div>
    </form>
@endsection
