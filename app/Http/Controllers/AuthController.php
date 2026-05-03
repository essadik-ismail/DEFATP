<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        // Generate captcha question with sum between 1-10
        $maxSum = 10;
        $num1 = rand(1, $maxSum - 1);
        $num2 = rand(1, $maxSum - $num1);
        $captcha_question = "{$num1} + {$num2}";
        $captcha_answer = $num1 + $num2;
        
        // Store captcha answer in session for validation
        session(['captcha_answer' => $captcha_answer]);
        
        return view('auth.login', compact('captcha_question'));
    }

    public function refreshCaptcha()
    {
        // Generate new captcha question with sum between 1-10
        $maxSum = 10;
        $num1 = rand(1, $maxSum - 1);
        $num2 = rand(1, $maxSum - $num1);
        $captcha_question = "{$num1} + {$num2}";
        $captcha_answer = $num1 + $num2;
        
        // Store captcha answer in session for validation
        session(['captcha_answer' => $captcha_answer]);
        
        return response()->json([
            'question' => $captcha_question,
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // Validate captcha first
        $sessionCaptchaAnswer = session('captcha_answer');
        $userCaptchaAnswer = (int) $request->input('captcha');

        if (!$sessionCaptchaAnswer || $userCaptchaAnswer !== $sessionCaptchaAnswer) {
            session()->forget('captcha_answer');
            throw ValidationException::withMessages([
                'captcha' => 'Résultat incorrect. Veuillez réessayer.',
            ]);
        }

        // Find user and run pre-auth checks in the correct order
        $user = User::where('ppr', $request->ppr)->first();

        // 3. Account existence
        if (!$user) {
            session()->forget('captcha_answer');
            throw ValidationException::withMessages([
                'ppr' => 'Compte introuvable. Vérifiez vos informations.',
            ]);
        }

        // 4. Account status
        if (!$user->is_active) {
            session()->forget('captcha_answer');
            throw ValidationException::withMessages([
                'ppr' => 'Votre compte est inactif. Veuillez contacter l\'administration.',
            ]);
        }

        // Account lockout
        if ($user->locked_until && now()->lt($user->locked_until)) {
            session()->forget('captcha_answer');
            $minutes = (int) now()->diffInMinutes($user->locked_until) + 1;
            throw ValidationException::withMessages([
                'ppr' => "Compte temporairement verrouillé. Réessayez dans {$minutes} minute(s).",
            ]);
        }
        // Reset stale lock
        if ($user->locked_until && now()->gte($user->locked_until)) {
            $user->update(['login_attempts' => 0, 'locked_until' => null]);
        }

        $credentials = $request->only('ppr', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            session()->forget('captcha_answer');

            // Reset failed attempts on success
            Auth::user()->update(['login_attempts' => 0, 'locked_until' => null, 'last_activity_at' => now()]);

            ActivityLogger::logLogin(Auth::user(), $request);

            return redirect()->intended(route('dashboard'));
        }

        session()->forget('captcha_answer');

        // 5. Password incorrect — increment failed attempts; lock after 5
        $attempts = $user->login_attempts + 1;
        $lockedUntil = $attempts >= 5 ? now()->addMinutes(15) : null;
        $user->update(['login_attempts' => $attempts, 'locked_until' => $lockedUntil]);

        if ($lockedUntil) {
            throw ValidationException::withMessages([
                'ppr' => 'Trop de tentatives échouées. Compte verrouillé pendant 15 minutes.',
            ]);
        }

        throw ValidationException::withMessages([
            'password' => 'Mot de passe incorrect.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout before actually logging out
        if ($user) {
            ActivityLogger::logLogout($user, $request);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function showUsers(): View
    {
        // Log view action
        ActivityLogger::log('view', 'Consultation de la liste des utilisateurs', User::class);
        
        $users = User::orderBy('name')->paginate(10)->withQueryString();
        return view('auth.users.index', compact('users'));
    }

    public function showCreateUser(): View
    {
        return view('auth.users.create');
    }

    public function storeUser(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'ppr' => $request->ppr,
            'password' => Hash::make($request->password),
        ]);

        // Sync Spatie role based on the user's role column or explicit roles input
        $rolesToSync = $request->input('roles') ?: ($request->input('role') ? [$request->input('role')] : []);
        if (!empty($rolesToSync)) {
            $user->syncRoles($rolesToSync);
        }

        // Log user creation
        ActivityLogger::logCreate(User::class, $user->id, "Utilisateur {$user->name}", $request);

        return redirect()->route('auth.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function showEditUser(User $user): View
    {
        // Log view action
        ActivityLogger::logView(User::class, $user->id, "Utilisateur {$user->name}", request());
        
        return view('auth.users.edit', compact('user'));
    }

    public function updateUser(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $oldData = $user->only(['name', 'ppr']);
        
        $user->update([
            'name' => $request->name,
            'ppr' => $request->ppr,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Log user update
        $changes = array_diff_assoc($user->fresh()->only(['name', 'ppr']), $oldData);
        if ($request->filled('password')) {
            $changes['password'] = 'changed';
        }
        
        ActivityLogger::logUpdate(User::class, $user->id, "Utilisateur {$user->name}", $changes, $request);

        return redirect()->route('auth.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('auth.users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userName = $user->name;
        $user->update(['is_deleted' => true]);
        
        // Log user deletion
        ActivityLogger::logDelete(User::class, $user->id, "Utilisateur {$userName}", request());
        
        return redirect()->route('auth.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function showProfile(): View
    {
        $user = Auth::user()->load(['dranef', 'dpanef', 'zdtf', 'dfp', 'province']);

        $dranefs = \App\Models\Dranef::orderBy('dranef')->get();
        $dpanefs = \App\Models\Dpanef::orderBy('dpanef')->get();
        $zdtfs = \App\Models\Zdtf::orderBy('code')->get();
        $dfps = \App\Models\Dfp::orderBy('code')->get();
        $provinces = \App\Models\Province::orderBy('nom')->get();

        $activityJournals = \App\Models\ActivityJournal::where('user_id', $user->id)
            ->orderBy('Date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('auth.profile', compact('user', 'activityJournals', 'dranefs', 'dpanefs', 'zdtfs', 'dfps', 'provinces'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $oldData = $user->only(['name', 'ppr', 'dranef_id', 'dpanef_id', 'zdtf_id', 'dfp_id', 'province_id']);

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
            'dranef_id' => $request->input('dranef_id') ?: null,
            'dpanef_id' => $request->input('dpanef_id') ?: null,
            'zdtf_id' => $request->input('zdtf_id') ?: null,
            'dfp_id' => $request->input('dfp_id') ?: null,
            'province_id' => $request->input('province_id') ?: null,
        ]);

        if ($request->filled('new_password')) {
            $user->update(['password' => Hash::make($request->new_password)]);
        }

        $changes = array_diff_assoc($user->fresh()->only(['name', 'ppr', 'dranef_id', 'dpanef_id', 'zdtf_id', 'dfp_id', 'province_id']), $oldData);
        if ($request->filled('new_password')) {
            $changes['password'] = 'changed';
        }

        ActivityLogger::logUpdate(User::class, $user->id, "Profil de {$user->name}", $changes, $request);

        return redirect()->route('auth.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    public function updateProfileInfo(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $userId = $user->id;

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($userId)],
            'ppr'   => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('users', 'ppr')->ignore($userId)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required'  => 'Le nom est requis.',
            'email.required' => "L'adresse e-mail est requise.",
            'email.email'    => "L'adresse e-mail n'est pas valide.",
            'email.unique'   => 'Cette adresse e-mail est déjà utilisée.',
            'ppr.required'   => 'Le PPR est requis.',
            'ppr.unique'     => 'Ce PPR est déjà utilisé.',
            'image.image'    => 'Le fichier doit être une image.',
            'image.max'      => "L'image ne doit pas dépasser 2 Mo.",
        ]);

        $oldData = $user->only(['name', 'email', 'ppr', 'image']);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'ppr'   => $validated['ppr'],
        ];

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $request->file('image')->store('avatars', 'public');
        }

        $user->update($data);
        $changes = array_diff_assoc($user->fresh()->only(['name', 'email', 'ppr']), array_intersect_key($oldData, array_flip(['name', 'email', 'ppr'])));
        ActivityLogger::logUpdate(User::class, $user->id, "Profil de {$user->name}", $changes, $request);

        return redirect()->route('auth.profile')->with('success', 'Informations personnelles mises à jour.');
    }

    public function updateProfileAffectation(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'dranef_id'   => ['nullable', 'exists:dranefs,id'],
            'dpanef_id'   => ['nullable', 'exists:dpanefs,id'],
            'zdtf_id'     => ['nullable', 'exists:zdtfs,id'],
            'dfp_id'      => ['nullable', 'exists:dfps,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
        ]);

        $oldData = $user->only(['dranef_id', 'dpanef_id', 'zdtf_id', 'dfp_id', 'province_id']);
        $user->update([
            'dranef_id'   => $validated['dranef_id']   ?? null,
            'dpanef_id'   => $validated['dpanef_id']   ?? null,
            'zdtf_id'     => $validated['zdtf_id']     ?? null,
            'dfp_id'      => $validated['dfp_id']      ?? null,
            'province_id' => $validated['province_id'] ?? null,
        ]);
        $changes = array_diff_assoc($user->fresh()->only(['dranef_id', 'dpanef_id', 'zdtf_id', 'dfp_id', 'province_id']), $oldData);
        ActivityLogger::logUpdate(User::class, $user->id, "Affectation de {$user->name}", $changes, $request);

        return redirect()->route('auth.profile')->with('success', 'Affectation mise à jour avec succès.');
    }

    public function updateProfilePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password'      => ['required', 'string'],
            'new_password'          => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'new_password.required'     => 'Le nouveau mot de passe est requis.',
            'new_password.min'          => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed'    => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        ActivityLogger::logUpdate(User::class, $user->id, "Mot de passe de {$user->name}", ['password' => 'changed'], $request);

        return redirect()->route('auth.profile')->with('success', 'Mot de passe mis à jour avec succès.');
    }
} 
