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
        
        return view('auth.login', compact('captcha_question', 'captcha_answer'));
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
            'answer' => $captcha_answer
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // Validate captcha first
        $sessionCaptchaAnswer = session('captcha_answer');
        $userCaptchaAnswer = (int) $request->input('captcha');
        
        if (!$sessionCaptchaAnswer || $userCaptchaAnswer !== $sessionCaptchaAnswer) {
            // Clear captcha from session on failure
            session()->forget('captcha_answer');
            
            throw ValidationException::withMessages([
                'captcha' => 'La réponse à la question de sécurité est incorrecte. Veuillez réessayer.',
            ]);
        }
        
        $credentials = $request->only('ppr', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Clear captcha from session on successful login
            session()->forget('captcha_answer');
            
            // Log successful login
            ActivityLogger::logLogin(Auth::user(), $request);
            
            return redirect()->intended(route('dashboard'));
        }

        // Clear captcha from session on login failure
        session()->forget('captcha_answer');

        throw ValidationException::withMessages([
            'ppr' => __('Les informations de connexion fournies ne correspondent pas à nos enregistrements.'),
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
        
        $users = User::orderBy('name')->paginate(10);
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
} 