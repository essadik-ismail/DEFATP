<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - DEFATP</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-darker: #065f46;
            --bg: #F2F6F3;
            --border: rgba(154, 179, 163, 0.45);
            --text: #1F2D24;
            --muted: #6B7C72;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            background: var(--bg);
        }

        /* ── Left branding panel ── */
        .brand-panel {
            display: none;
            width: 420px;
            flex-shrink: 0;
            background: linear-gradient(160deg, #047857 0%, #059669 45%, #34d399 100%);
            position: relative;
            overflow: hidden;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 2.5rem;
        }

        @media (min-width: 1024px) { .brand-panel { display: flex; } }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            position: relative;
            z-index: 1;
        }
        .brand-logo-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            background: rgba(255,255,255,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.375rem;
            color: #fff;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.25);
        }
        .brand-logo-text { font-size: 1.5rem; font-weight: 700; color: #fff; letter-spacing: 0.04em; }
        .brand-logo-sub  { font-size: 0.6875rem; color: rgba(255,255,255,0.7); font-weight: 400; margin-top: 1px; }

        .brand-center {
            position: relative;
            z-index: 1;
        }
        .brand-headline {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
            margin: 0 0 1rem;
        }
        .brand-desc {
            font-size: 0.9375rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.6;
            margin: 0 0 2rem;
        }

        .brand-features { display: flex; flex-direction: column; gap: 0.75rem; }
        .brand-feature {
            display: flex; align-items: center; gap: 0.75rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }
        .brand-feature i { color: #a7f3d0; font-size: 0.875rem; width: 16px; text-align: center; }
        .brand-feature span { font-size: 0.875rem; color: rgba(255,255,255,0.9); font-weight: 500; }

        .brand-footer {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            position: relative;
            z-index: 1;
        }

        /* ── Right form panel ── */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            overflow-y: auto;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
        }

        .login-header { margin-bottom: 2rem; }
        .login-header h2 {
            font-size: 1.625rem; font-weight: 700;
            color: var(--text); margin: 0 0 0.375rem;
        }
        .login-header p { font-size: 0.9375rem; color: var(--muted); margin: 0; }

        /* Mobile brand strip (visible < 1024px) */
        .mobile-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 1rem;
        }
        @media (min-width: 1024px) { .mobile-brand { display: none; } }
        .mobile-brand i { font-size: 1.5rem; color: #fff; }
        .mobile-brand-text span { display: block; font-size: 1.125rem; font-weight: 700; color: #fff; }
        .mobile-brand-text small { font-size: 0.75rem; color: rgba(255,255,255,0.75); }

        /* Form fields */
        .field-group { margin-bottom: 1.25rem; }
        .field-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        .field-label span { color: var(--muted); font-weight: 400; margin-left: 0.25rem; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            top: 50%; left: 0.875rem;
            transform: translateY(-50%);
            color: #9AB3A3;
            font-size: 0.875rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .field-input {
            width: 100%;
            height: 2.875rem;
            padding: 0 0.875rem 0 2.625rem;
            font-size: 0.9375rem;
            font-family: inherit;
            border: 1.5px solid var(--border);
            border-radius: 0.625rem;
            background: #fff;
            color: var(--text);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .field-input::placeholder { color: #b0bfb7; font-weight: 400; }
        .field-input:hover { border-color: rgba(5,150,105,0.35); }
        .field-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
        }
        .field-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--primary); }

        /* Validation states */
        .field-input.is-valid   { border-color: #10b981; }
        .field-input.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

        .field-hint {
            font-size: 0.75rem; color: var(--muted);
            margin-top: 0.3rem;
            display: flex; align-items: center; gap: 0.3rem;
        }
        .field-error {
            font-size: 0.75rem; color: #dc2626; font-weight: 500;
            margin-top: 0.3rem;
            display: flex; align-items: center; gap: 0.3rem;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            top: 50%; right: 0.75rem;
            transform: translateY(-50%);
            background: none; border: none;
            color: #9AB3A3; cursor: pointer;
            padding: 0.25rem; font-size: 0.875rem;
            transition: color 0.2s;
        }
        .pw-toggle:hover { color: var(--primary); }

        /* Captcha row */
        .captcha-row {
            display: flex;
            align-items: stretch;
            gap: 0.5rem;
        }
        .captcha-display {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            background: #f8faf9;
            border: 1.5px solid var(--border);
            border-radius: 0.625rem;
            padding: 0.5rem 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.1em;
            color: var(--text);
            user-select: none;
            min-height: 2.875rem;
        }
        .captcha-display.valid   { border-color: #10b981; background: #f0fdf9; }
        .captcha-display.invalid { border-color: #ef4444; background: #fef2f2; }

        .captcha-refresh-btn {
            width: 2.875rem;
            border-radius: 0.625rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none; color: #fff;
            cursor: pointer; font-size: 0.875rem;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.2s, transform 0.3s;
            flex-shrink: 0;
        }
        .captcha-refresh-btn:hover { opacity: 0.88; }
        .captcha-refresh-btn:active { transform: rotate(180deg) scale(0.95); }

        /* Remember me */
        .remember-row {
            display: flex; align-items: center; gap: 0.625rem;
            margin-top: 0.25rem;
        }
        .remember-row input[type="checkbox"] {
            width: 1rem; height: 1rem;
            accent-color: var(--primary);
            cursor: pointer;
        }
        .remember-row label {
            font-size: 0.875rem; color: var(--text);
            cursor: pointer; font-weight: 500;
        }

        /* Alert */
        .login-alert {
            display: flex; align-items: flex-start; gap: 0.625rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid #dc2626;
            border-radius: 0.625rem;
            padding: 0.75rem 0.875rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem; color: #991b1b;
        }
        .login-alert i { margin-top: 0.1rem; flex-shrink: 0; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            height: 2.875rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 0.75rem;
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: filter 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(5,150,105,0.25);
            margin-top: 1.75rem;
            position: relative;
            overflow: hidden;
        }
        .btn-submit:hover {
            filter: brightness(1.06);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(5,150,105,0.3);
        }
        .btn-submit:active { transform: translateY(0); filter: brightness(0.97); }
        .btn-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        /* Spinner inside button */
        .btn-spinner {
            width: 1rem; height: 1rem;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        .btn-submit.loading .btn-spinner { display: block; }
        .btn-submit.loading .btn-label { display: none; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Section divider */
        .section-sep {
            display: flex; align-items: center; gap: 0.75rem;
            margin: 1.5rem 0;
        }
        .section-sep::before, .section-sep::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }
        .section-sep span { font-size: 0.75rem; color: var(--muted); white-space: nowrap; }

        /* Security note */
        .security-note {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
            margin-top: 1.5rem;
            font-size: 0.75rem; color: var(--muted);
        }
        .security-note i { font-size: 0.8125rem; color: #10b981; }

        /* Responsive */
        @media (max-width: 640px) {
            .form-panel { padding: 1.5rem 1rem; align-items: flex-start; padding-top: 2rem; }
            .field-input, .btn-submit { height: 3.25rem; font-size: 1rem; }
            .captcha-display { min-height: 3.25rem; }
            .captcha-refresh-btn { width: 3.25rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>

    <!-- LEFT: Branding Panel (desktop only) -->
    <div class="brand-panel" aria-hidden="true">
        <div class="brand-logo">
            <div class="brand-logo-icon"><i class="fas fa-tree"></i></div>
            <div>
                <div class="brand-logo-text">DEFATP</div>
                <div class="brand-logo-sub">Gestion Forestière</div>
            </div>
        </div>

        <div class="brand-center">
            <h1 class="brand-headline">Gestion forestière intégrée</h1>
            <p class="brand-desc">Plateforme centralisée pour la gestion des articles, contrats, cessions et documents forestiers.</p>
            <div class="brand-features">
                <div class="brand-feature">
                    <i class="fas fa-file-contract"></i>
                    <span>Suivi complet des articles & contrats</span>
                </div>
                <div class="brand-feature">
                    <i class="fas fa-layer-group"></i>
                    <span>Gestion des cessions & carnets</span>
                </div>
                <div class="brand-feature">
                    <i class="fas fa-file-pdf"></i>
                    <span>Génération automatique de documents</span>
                </div>
                <div class="brand-feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Accès sécurisé & traçabilité complète</span>
                </div>
            </div>
        </div>

        <div class="brand-footer">© {{ date('Y') }} DEFATP · Direction des Eaux et Forêts</div>
    </div>

    <!-- RIGHT: Form Panel -->
    <div class="form-panel">
        <div class="login-card">

            <!-- Mobile brand strip -->
            <div class="mobile-brand">
                <i class="fas fa-tree"></i>
                <div class="mobile-brand-text">
                    <span>DEFATP</span>
                    <small>Gestion Forestière</small>
                </div>
            </div>

            <div class="login-header">
                <h2>Bienvenue</h2>
                <p>Connectez-vous à votre espace de travail</p>
            </div>

            @if ($errors->any())
            <div class="login-alert" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach(array_unique($errors->all()) as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <!-- PPR -->
                <div class="field-group">
                    <label class="field-label" for="ppr">
                        Numéro PPR <span>(Personnel)</span>
                    </label>
                    <div class="input-wrap">
                        <i class="fas fa-id-badge input-icon"></i>
                        <input
                            type="number"
                            id="ppr"
                            name="ppr"
                            class="field-input"
                            value="{{ old('ppr') }}"
                            placeholder="Entrez votre PPR"
                            required
                            autocomplete="username"
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="field-group">
                    <label class="field-label" for="password">Mot de passe</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="field-input"
                            placeholder="Entrez votre mot de passe"
                            required
                            autocomplete="current-password"
                            style="padding-right: 2.75rem;"
                        >
                        <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1" aria-label="Afficher/masquer le mot de passe">
                            <i class="fas fa-eye" id="pwToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Se souvenir de moi</label>
                </div>

                <!-- Separator -->
                <div class="section-sep">
                    <span>Vérification de sécurité</span>
                </div>

                <!-- Captcha -->
                <div class="field-group">
                    <label class="field-label" for="captcha">Répondez à la question ci-dessous</label>

                    <div class="captcha-row" style="margin-bottom: 0.625rem;">
                        <div class="captcha-display" id="captchaDisplay">
                            <span id="captchaText">{{ $captcha_question ?? '? + ? = ?' }}</span>
                        </div>
                        <button type="button" class="captcha-refresh-btn" id="captchaRefresh" title="Nouvelle question" aria-label="Régénérer le captcha">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>

                    <div class="input-wrap">
                        <i class="fas fa-check-double input-icon"></i>
                        <input
                            type="number"
                            id="captcha"
                            name="captcha"
                            class="field-input"
                            placeholder="Votre réponse"
                            required
                            min="0"
                            autocomplete="off"
                        >
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit" id="loginBtn">
                    <div class="btn-spinner"></div>
                    <span class="btn-label">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </span>
                </button>
            </form>

            <div class="security-note">
                <i class="fas fa-lock"></i>
                Connexion sécurisée — vos données sont protégées
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        // ── Field validation ──────────────────────────────────────
        const pprField      = document.getElementById('ppr');
        const passwordField = document.getElementById('password');
        const captchaField  = document.getElementById('captcha');
        const captchaDisp   = document.getElementById('captchaDisplay');

        function validateField(field) {
            const v = field.value.trim();
            field.classList.remove('is-valid', 'is-invalid');
            if (!v) return false;
            if (field.name === 'ppr'      && v.length >= 3) { field.classList.add('is-valid'); return true; }
            if (field.name === 'password' && v.length >= 6) { field.classList.add('is-valid'); return true; }
            return false;
        }

        function validateCaptcha() {
            if (!captchaField) return true;
            const v = captchaField.value.trim();
            captchaField.classList.remove('is-valid', 'is-invalid');
            captchaDisp.classList.remove('valid', 'invalid');
            if (!v || isNaN(parseInt(v))) {
                captchaField.classList.add('is-invalid');
                captchaDisp.classList.add('invalid');
                return false;
            }
            captchaField.classList.add('is-valid');
            captchaDisp.classList.add('valid');
            return true;
        }

        [pprField, passwordField].forEach(f => {
            if (!f) return;
            f.addEventListener('input', () => validateField(f));
            f.addEventListener('blur',  () => validateField(f));
        });

        if (captchaField) {
            captchaField.addEventListener('input', validateCaptcha);
            captchaField.addEventListener('blur',  validateCaptcha);
        }

        // ── Password toggle ──────────────────────────────────────
        const pwToggle     = document.getElementById('pwToggle');
        const pwToggleIcon = document.getElementById('pwToggleIcon');
        if (pwToggle) {
            pwToggle.addEventListener('click', () => {
                const shown = passwordField.type === 'text';
                passwordField.type = shown ? 'password' : 'text';
                pwToggleIcon.classList.toggle('fa-eye',       shown);
                pwToggleIcon.classList.toggle('fa-eye-slash', !shown);
            });
        }

        // ── Captcha refresh (AJAX) ────────────────────────────────
        const refreshBtn = document.getElementById('captchaRefresh');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', async () => {
                refreshBtn.disabled = true;
                refreshBtn.style.opacity = '0.6';
                try {
                    const res = await fetch('/captcha/refresh', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (res.ok) {
                        const data = await res.json();
                        if (data.question) document.getElementById('captchaText').textContent = data.question;
                        if (captchaField) {
                            captchaField.value = '';
                            captchaField.classList.remove('is-valid', 'is-invalid');
                        }
                        captchaDisp.classList.remove('valid', 'invalid');
                    }
                } catch(e) { /* silent fail */ }
                refreshBtn.disabled = false;
                refreshBtn.style.opacity = '';
            });
        }

        // ── Form submit ──────────────────────────────────────────
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', e => {
                const pprOk      = validateField(pprField);
                const passOk     = validateField(passwordField);
                const captchaOk  = validateCaptcha();

                if (!pprOk || !passOk || !captchaOk) {
                    e.preventDefault();
                    if (!pprOk)     pprField.focus();
                    else if (!passOk) passwordField.focus();
                    else             captchaField?.focus();
                    return;
                }

                const btn = document.getElementById('loginBtn');
                if (btn) {
                    btn.classList.add('loading');
                    btn.disabled = true;
                    setTimeout(() => { btn.classList.remove('loading'); btn.disabled = false; }, 12000);
                }
            });
        }

        // ── Keyboard shortcuts ───────────────────────────────────
        document.addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target?.id === 'password') loginForm?.requestSubmit();
            if (e.key === 'Escape' && document.activeElement) {
                document.activeElement.value = '';
                document.activeElement.classList.remove('is-valid', 'is-invalid');
            }
        });
    </script>
</body>
</html>
