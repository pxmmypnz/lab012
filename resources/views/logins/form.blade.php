<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <main id="app-main-content">
        <h1 class="app-page-title">Login</h1>

        <form action="{{ route('authentication') }}" method="post">
            @csrf

            <div class="app-cmp-data-form">
                <label for="email">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>

                <div class="app-cmp-form-actions">
                    <button class="app-cl-button app-cl-primary app-cl-filled" type="submit">Login</button>
                </div>
            </div>
        </form>

        <div class="app-cmp-notifications">
            @error('credentials')
                <div role="alert">{{ $message }}</div>
            @enderror
            @error('email')
                <div role="alert">{{ $message }}</div>
            @enderror
            @error('password')
                <div role="alert">{{ $message }}</div>
            @enderror
        </div>
    </main>
</body>

</html>
