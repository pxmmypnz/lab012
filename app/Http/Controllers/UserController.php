<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return User::orderBy('email');
    }

    #[\Override()]
    function getFilterOptions(): array
    {
        return [
            'term' => [
                'email' => static fn(Builder $query, string $word) =>
                    $query->where('email', 'LIKE', "%{$word}%"),
                'name' => static fn(Builder $query, string $word) =>
                    $query->where('name', 'LIKE', "%{$word}%"),
                'role' => static fn(Builder $query, string $word) =>
                    $query->where('role', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $users = $this->search($criteria)->paginate(static::MAX_ITEMS);

        return view('users.list', [
            'criteria' => $criteria,
            'users' => $users,
        ]);
    }

    function showCreateForm(): View
    {
        return view('users.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $data = Validator::make((array) $request->getParsedBody(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'in:ADMIN,USER'],
        ])->validate();

        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->role = $data['role'];
            $user->save();

            if (session()->has('bookmarks.users.create')) {
                session()->put(
                    'bookmarks.users.view',
                    session()->get('bookmarks.users.create'),
                );
            }
            session()->forget('bookmarks.users.create');

            return redirect()->route('users.view', ['user' => $user->email])
                ->with('status', "User {$user->email} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function view(string $user): View
    {
        $userModel = $this->findUser($user);

        return view('users.view', [
            'user' => $userModel,
            'isSelf' => Auth::id() === $userModel->getKey(),
        ]);
    }

    function showUpdateForm(string $user): View
    {
        $userModel = $this->findUser($user);

        return view('users.update-form', [
            'user' => $userModel,
            'isSelf' => Auth::id() === $userModel->getKey(),
        ]);
    }

    function update(
        string $user,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $userModel = $this->findUser($user);
        $isSelf = Auth::id() === $userModel->getKey();
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:4'],
        ];
        if (!$isSelf) {
            $rules['role'] = ['required', 'in:ADMIN,USER'];
        }
        $data = Validator::make((array) $request->getParsedBody(), [
            ...$rules,
        ])->validate();

        try {
            $userModel->name = $data['name'];
            if (!$isSelf) {
                $userModel->role = $data['role'];
            }
            if ($data['password'] !== null && $data['password'] !== '') {
                $userModel->password = $data['password'];
            }
            $userModel->save();

            if (session()->has('bookmarks.users.update')) {
                session()->put(
                    'bookmarks.users.view',
                    session()->get('bookmarks.users.update'),
                );
            }
            session()->forget('bookmarks.users.update');

            return redirect()->route('users.view', ['user' => $userModel->email])
                ->with('status', "User {$userModel->email} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function delete(string $user): RedirectResponse
    {
        $userModel = $this->findUser($user);
        Gate::authorize('delete', $userModel);

        try {
            $userModel->delete();

            return redirect(session()->get('bookmarks.users.delete') ?? route('users.index'))
                ->with('status', "User {$userModel->email} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function viewSelf(): View
    {
        $user = $this->authenticatedUser();

        return view('users.selves.view', [
            'user' => $user,
        ]);
    }

    function showUpdateSelfForm(): View
    {
        return view('users.selves.update-form', [
            'user' => $this->authenticatedUser(),
        ]);
    }

    function updateSelf(ServerRequestInterface $request): RedirectResponse
    {
        $user = $this->authenticatedUser();
        $data = Validator::make((array) $request->getParsedBody(), [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:4'],
        ])->validate();

        try {
            $user->name = $data['name'];
            if ($data['password'] !== null && $data['password'] !== '') {
                $user->password = $data['password'];
            }
            $user->save();

            if (session()->has('bookmarks.users.selves.update')) {
                session()->put(
                    'bookmarks.users.selves.view',
                    session()->get('bookmarks.users.selves.update'),
                );
            }
            session()->forget('bookmarks.users.selves.update');

            return redirect()->route('users.selves.view')
                ->with('status', 'Your information was updated.');
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    private function findUser(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    private function authenticatedUser(): User
    {
        $user = Auth::user();
        assert($user instanceof User);

        return $user;
    }
}
