<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    function showForm(): View
    {
        return view('logins.form');
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse
    {
        $validator = Validator::make(
            (array) $request->getParsedBody(),
            [
                'email' => 'required',
                'password' => 'required',
            ],
        );

        if ($validator->passes()) {
            if (Auth::attempt($validator->safe()->only(['email', 'password']))) {
                session()->regenerate();

                return redirect()->intended(route('products.index'));
            }

            $validator->errors()->add(
                'credentials',
                'The provided credentials do not match our records.',
            );
        }

        return redirect()->back()->withErrors($validator);
    }

    function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
