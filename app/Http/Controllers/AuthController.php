<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('ppr', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'ppr' => __('Les informations de connexion fournies ne correspondent pas à nos enregistrements.'),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function showUsers(): View
    {
        $users = User::orderBy('name')->paginate(10);
        return view('auth.users.index', compact('users'));
    }

    public function showCreateUser(): View
    {
        return view('auth.users.create');
    }

    public function storeUser(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'ppr' => $request->ppr,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('auth.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function showEditUser(User $user): View
    {
        return view('auth.users.edit', compact('user'));
    }

    public function updateUser(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name' => $request->name,
            'ppr' => $request->ppr,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('auth.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('auth.users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->update(['is_deleted' => true]);
        return redirect()->route('auth.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function showProfile(): View
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // Check current password if changing password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Le mot de passe actuel est incorrect.',
                ]);
            }
        }

        $user->update([
            'name' => $request->name,
            'ppr' => $request->ppr,
        ]);

        if ($request->filled('new_password')) {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return redirect()->route('auth.profile')->with('success', 'Profil mis à jour avec succès.');
    }
} 