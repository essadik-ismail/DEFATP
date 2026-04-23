<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — DEFATP Gestion Forestière</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green-900: #0B1F14;
            --green-800: #163326;
            --green-700: #204A37;
            --green-600: #2C644E;
            --green-500: #2D7A54;
            --green-400: #3DA870;
            --green-300: #71CE9C;
            --green-200: #B3E6CA;
            --green-100: #E8F7EF;
            --green-50: #F2FBF6;
            --border: #DDE6E2;
            --text: #0F1F18;
            --text-sec: #4E6B5D;
            --text-muted: #7A9B8A;
            --surface: #FFFFFF;
            --bg: #F3F6F5;
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: var(--bg);
        }

        /* ═══════════════════════════════════════════════
           RIGHT PANEL — Login Form
        ═══════════════════════════════════════════════ */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            background: var(--bg);
        }

        .login-card {
            width: 100%;
            max-width: 396px;
        }

        /* Brand header */
        .mobile-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .mobile-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--green-800);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 0.9375rem;
        }

        .mobile-brand-name {
            font-size: 0.9375rem;
            font-weight: 800;
            color: var(--green-800);
            margin: 0;
        }

        .mobile-brand-sub {
            font-size: 0.5625rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 1px;
        }

        /* Form card */
        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.875rem;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        /* Security strip */
        .security-strip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: var(--green-50);
            border: 1px solid var(--green-200);
            border-radius: 0.375rem;
            font-size: 0.75rem;
            color: var(--green-700);
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .security-strip i {
            color: var(--green-500);
            font-size: 0.75rem;
        }

        .form-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.25rem;
            letter-spacing: -0.01em;
        }

        .form-subtitle {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-bottom: 1.625rem;
        }

        /* Error alert */
        .error-alert {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            padding: 0.75rem 0.875rem;
            background: #FFF5F5;
            border: 1px solid #FED7D7;
            border-left: 4px solid #DC2626;
            border-radius: 0.4375rem;
            font-size: 0.8125rem;
            color: #7F1D1D;
            margin-bottom: 1.25rem;
        }

        .error-alert i {
            flex-shrink: 0;
            margin-top: 1px;
            font-size: 0.875rem;
        }

        /* Field */
        .field {
            margin-bottom: 1rem;
        }

        .field-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.375rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #A8C4B4;
            font-size: 0.8125rem;
            pointer-events: none;
        }

        .field-input {
            width: 100%;
            padding: 0.5625rem 0.875rem 0.5625rem 2.25rem;
            border: 1px solid #C9D7D1;
            border-radius: 0.4375rem;
            background: #FAFCFB;
            font-size: 0.875rem;
            color: var(--text);
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            -webkit-appearance: none;
        }

        .field-input::placeholder {
            color: #A8C4B4;
        }

        .field-input:focus {
            outline: none;
            border-color: var(--green-500);
            box-shadow: 0 0 0 3px rgba(45, 122, 84, 0.12);
            background: #FFFFFF;
        }

        .field-input.is-invalid {
            border-color: #DC2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
            background: #FFF5F5;
        }

        .field-error {
            font-size: 0.75rem;
            color: #DC2626;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3125rem;
        }

        /* Captcha row */
        .captcha-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }

        .captcha-box {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.75rem;
            background: var(--green-50);
            border: 1px solid var(--green-200);
            border-radius: 0.4375rem;
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--green-700);
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .captcha-box i {
            color: var(--green-400);
            font-size: 0.75rem;
        }

        .captcha-row .field-wrap {
            flex: 1;
        }

        .captcha-refresh {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border: 1px solid var(--border);
            border-radius: 0.4375rem;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.75rem;
            transition: border-color 0.15s, color 0.15s, background 0.15s;
        }

        .captcha-refresh:hover {
            border-color: var(--green-400);
            color: var(--green-600);
            background: var(--green-50);
        }

        /* Divider */
        .form-sep {
            height: 1px;
            background: var(--border);
            margin: 1.125rem 0;
        }

        /* Remember */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
        }

        .remember-row input[type="checkbox"] {
            width: 14px;
            height: 14px;
            accent-color: var(--green-500);
            cursor: pointer;
            flex-shrink: 0;
        }

        .remember-row label {
            font-size: 0.8125rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        /* Submit button */
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6875rem 1.25rem;
            background: var(--green-800);
            color: #FFFFFF;
            border: 1px solid var(--green-900);
            border-radius: 0.4375rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: background 0.15s, transform 0.12s, box-shadow 0.15s;
            box-shadow: 0 2px 8px rgba(22, 51, 38, 0.28);
        }

        .btn-submit:hover {
            background: var(--green-700);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(22, 51, 38, 0.32);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 1px 4px rgba(22, 51, 38, 0.20);
        }

        /* Footer note */
        .login-note {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-note p {
            font-size: 0.6875rem;
            color: #A8C4B4;
            line-height: 1.6;
        }

        *:focus-visible {
            outline: 2px solid var(--green-500);
            outline-offset: 2px;
        }
    </style>
</head>

<body>

    {{-- Right panel --}}
    <div class="right-panel" role="main">
        <div class="login-card">

            {{-- Mobile brand --}}
            <div class="mobile-brand">
                <div class="mobile-brand-icon"><i class="fas fa-tree"></i></div>
                <div>
                    <h1 class="mobile-brand-name">DEFATP</h1>
                    <p class="mobile-brand-sub">Eaux et Forêts</p>
                </div>
            </div>

            <div class="form-card">

                <div class="security-strip">
                    <i class="fas fa-lock"></i>
                    Accès réservé aux agents autorisés — Connexion sécurisée
                </div>

                <h2 class="form-title">Connexion</h2>
                <p class="form-subtitle">Entrez vos identifiants pour accéder au portail.</p>

                @if ($errors->any())
                    <div class="error-alert" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    {{-- PPR --}}
                    <div class="field">
                        <label for="ppr" class="field-label">Numéro PPR</label>
                        <div class="field-wrap">
                            <i class="fas fa-id-card field-icon" aria-hidden="true"></i>
                            <input type="text" id="ppr" name="ppr" value="{{ old('ppr') }}"
                                autocomplete="username" placeholder="Votre numéro PPR"
                                class="field-input {{ $errors->has('ppr') ? 'is-invalid' : '' }}" autofocus required>
                        </div>
                        @error('ppr')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field">
                        <label for="password" class="field-label">Mot de passe</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon" aria-hidden="true"></i>
                            <input type="password" id="password" name="password" autocomplete="current-password"
                                placeholder="••••••••"
                                class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                        </div>
                        @error('password')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-sep"></div>

                    {{-- CAPTCHA --}}
                    <div class="field">
                        <label class="field-label">Vérification de sécurité</label>
                        <div class="captcha-row">
                            <div class="captcha-box" id="captcha-display">
                                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                                <span id="captcha-text">{{ $captcha_question }} = ?</span>
                            </div>
                            <div class="field-wrap">
                                <i class="fas fa-calculator field-icon" aria-hidden="true"></i>
                                <input type="number" id="captcha" name="captcha" placeholder="Réponse"
                                    class="field-input {{ $errors->has('captcha') ? 'is-invalid' : '' }}" min="0"
                                    aria-label="Réponse à la question de sécurité" required>
                            </div>
                            <button type="button" class="captcha-refresh" id="refresh-captcha"
                                title="Nouvelle question" aria-label="Générer une nouvelle question">
                                <i class="fas fa-rotate-right" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('captcha')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="remember-row">
                        <input type="checkbox" name="remember" id="remember" value="1"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                        Se connecter
                    </button>
                </form>

            </div>

            <div class="login-note">
                <p>
                    Pour toute assistance, contactez votre administrateur système.
                </p>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('refresh-captcha').addEventListener('click', function() {
            this.disabled = true;
            const icon = this.querySelector('i');
            icon.style.transition = 'transform 0.4s';
            icon.style.transform = 'rotate(180deg)';
            fetch('{{ route('captcha.refresh') }}')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('captcha-text').textContent = data.question + ' = ?';
                    document.getElementById('captcha').value = '';
                })
                .catch(() => {})
                .finally(() => {
                    this.disabled = false;
                    setTimeout(() => {
                        icon.style.transform = 'rotate(0deg)';
                    }, 120);
                });
        });
    </script>
</body>

</html>
