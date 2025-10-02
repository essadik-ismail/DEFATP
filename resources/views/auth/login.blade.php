<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SylvaNet</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary-color: #059669;
            --secondary-color: #7c2d12;
            --success-color: #16a34a;
            --warning-color: #ca8a04;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --light-color: #f0fdf4;
            --dark-color: #14532d;
            --border-color: #bbf7d0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem 2.5rem;
            box-sizing: border-box;
        }



        .login-header {
            margin-bottom: 2.5rem;
        }
        
        .login-body {
            margin-top: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            width: 100%;
            height: 3.5rem;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            background: white;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: white;
        }
        
        .form-control:hover {
            border-color: #9ca3af;
        }

        /* Password field with toggle */
        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(5, 150, 105, 0.1);
        }

        .password-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
        }

        .password-field .form-control {
            padding-right: 3.5rem;
        }
        
        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            border: none;
            border-radius: 1rem;
            height: 3.5rem;
            padding: 0 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 8rem;
            text-transform: none;
            letter-spacing: normal;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #047857, #0f3d1a);
            box-shadow: 0 12px 35px rgba(5, 150, 105, 0.4);
            transform: translateY(-3px) scale(1.02);
        }

        .btn-login:active {
            transform: translateY(-1px) scale(0.98);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        
        .form-check {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            accent-color: var(--primary-color);
            border-radius: 0.25rem;
        }

        .form-check-label {
            font-size: 0.875rem;
            color: var(--dark-color);
            cursor: pointer;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 0.75rem;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert i {
            font-size: 1rem;
        }
        
        .invalid-feedback {
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 0.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Enhanced form validation states */
        .form-control.is-valid {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .valid-feedback {
            font-size: 0.75rem;
            color: #10b981;
            margin-top: 0.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Help text */
        .form-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
            font-weight: 400;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 2rem;
        }

        .security-note {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Captcha styles */
        .captcha-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            width: 100%;
        }

        .captcha-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 2px solid #bbf7d0;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            min-height: 3.5rem;
            box-sizing: border-box;
            width: 100%;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .captcha-question:hover {
            border-color: #86efac;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .captcha-question span {
            flex: 1;
            text-align: center;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.1em;
            word-break: keep-all;
            white-space: nowrap;
        }

        .captcha-refresh {
            background: linear-gradient(135deg, var(--primary-color), #047857);
            border: none;
            border-radius: 0.75rem;
            color: white;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 1rem;
            flex-shrink: 0;
            min-width: 2.5rem;
            min-height: 2.5rem;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .captcha-refresh:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            transform: rotate(180deg) scale(1.05);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }

        .captcha-refresh:active {
            transform: rotate(180deg) scale(0.95);
        }

        .captcha-refresh:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
        }

        .captcha-refresh i {
            font-size: 1rem;
        }

        /* Captcha validation states */
        .captcha-question.valid {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .captcha-question.invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }



        /* Tablet optimizations */
        @media (max-width: 768px) {
            .login-container {
                max-width: 500px;
            }
            
            .login-card {
                padding: 2.5rem 2rem;
            }
            
            .captcha-question {
                font-size: 1.1rem;
                padding: 0.875rem 1rem;
            }
            
            .captcha-refresh {
                width: 2.25rem;
                height: 2.25rem;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 480px) {
            body {
                padding: 0.75rem;
            }
            
            .login-container {
                max-width: 100%;
            }
            
            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 1rem;
                margin: 0;
            }
            
            .login-header h1 {
                font-size: 1.75rem;
            }

            .logo-icon {
                font-size: 2.5rem;
            }

            /* Larger touch targets for mobile */
            .form-control {
                height: 3.75rem;
                font-size: 1.1rem;
                padding: 1rem 1.25rem;
            }

            .password-toggle {
                padding: 0.75rem;
                right: 0.75rem;
                width: 3rem;
                height: 3rem;
            }

            .btn-login {
                height: 3.5rem;
                font-size: 1.1rem;
                min-width: 100%;
                width: 100%;
            }

            .form-check-input {
                width: 1.5rem;
                height: 1.5rem;
            }
            
            /* Captcha mobile optimizations */
            .captcha-container {
                gap: 1rem;
            }
            
            .captcha-question {
                flex-direction: column;
                gap: 0.75rem;
                padding: 1.25rem 1rem;
                min-height: auto;
                text-align: center;
            }
            
            .captcha-question span {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }
            
            .captcha-refresh {
                width: 3rem;
                height: 3rem;
                margin-left: 0;
                align-self: center;
            }
            
            .captcha-refresh i {
                font-size: 1.25rem;
            }
            
            /* Form group spacing */
            .form-group {
                margin-bottom: 1.75rem;
            }
            
            /* Button container mobile */
            .button-container {
                margin-top: 2.5rem;
            }
        }

        /* Extra small mobile devices */
        @media (max-width: 360px) {
            .login-card {
                padding: 1.5rem 1rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .logo-icon {
                font-size: 2rem;
            }
            
            .captcha-question {
                padding: 1rem 0.75rem;
            }
            
            .captcha-question span {
                font-size: 1.25rem;
            }
            
            .form-control {
                height: 3.5rem;
                font-size: 1rem;
            }
            
            .btn-login {
                height: 3.25rem;
                font-size: 1rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .password-toggle {
                padding: 0.75rem;
                width: 3rem;
                height: 3rem;
            }

            .captcha-refresh {
                width: 3rem;
                height: 3rem;
            }

            .btn-login:hover {
                transform: none;
                box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            }

            .form-control:focus {
                transform: none;
            }
            
            .captcha-refresh:hover {
                transform: none;
                background: #047857;
            }
            
            .captcha-refresh:active {
                transform: scale(0.95);
                background: #065f46;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .form-control {
                border-width: 3px;
            }

            .btn-login {
                border: 2px solid var(--dark-color);
            }
        }

        /* Landscape orientation support */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 0.5rem;
            }
            
            .login-card {
                padding: 1.5rem 2rem;
            }
            
            .login-header {
                margin-bottom: 1.5rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .logo-icon {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            
            .form-group {
                margin-bottom: 1rem;
            }
            
            .form-control {
                height: 3rem;
            }
            
            .captcha-question {
                padding: 0.75rem 1rem;
                min-height: 3rem;
            }
            
            .captcha-question span {
                font-size: 1.1rem;
            }
            
            .button-container {
                margin-top: 1.5rem;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .captcha-refresh:hover {
                transform: none;
            }
            
            .btn-login:hover {
                transform: none;
            }
        }

        /* Loading animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Focus management for better accessibility */
        .form-control:focus,
        .captcha-refresh:focus,
        .btn-login:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Better text selection */
        .captcha-question span {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        /* Improved button states */
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .captcha-refresh:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Better error state visibility */
        .form-control.is-invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .captcha-question.invalid {
            border-color: #dc2626;
            background: #fef2f2;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Improved success state visibility */
        .form-control.is-valid {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .captcha-question.valid {
            border-color: #10b981;
            background: #f0fdf4;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Modern Header Section -->
            <div class="login-header">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-tree text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            Se connecter
                        </h1>
                        <p class="text-gray-600 text-lg mt-2">Accédez à votre compte SylvaNet</p>
                    </div>
                </div>
            </div>
            
            <div class="login-body">
                @if ($errors->any())
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                            <div>
                                <h3 class="font-semibold text-lg">Erreur de connexion!</h3>
                                <ul class="mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6">
                    @csrf
                    
                    <div class="form-group">
                        <label for="ppr" class="form-label">
                            PPR
                            <span class="form-text">(Numéro de personnel)</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('ppr') is-invalid @enderror" 
                               id="ppr" 
                               name="ppr" 
                               value="{{ old('ppr') }}" 
                               placeholder="Entrez votre PPR" 
                               required 
                               autofocus
                               autocomplete="username"
                               aria-describedby="ppr-help">
                        @error('ppr')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div id="ppr-help" class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Votre numéro de personnel unique
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Entrez votre mot de passe" 
                                   required
                                   autocomplete="current-password"
                                   aria-describedby="password-help">
                            <button type="button" 
                                    class="password-toggle" 
                                    id="passwordToggle"
                                    aria-label="Afficher le mot de passe"
                                    tabindex="-1">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div id="password-help" class="form-text">
                            <i class="fas fa-shield-alt"></i>
                            Mot de passe sécurisé requis
                        </div>
                    </div>
                    
                    <!-- Security Verification Section -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-green-900">Vérification de sécurité</h3>
                        </div>
                        <div class="form-group">
                            <label for="captcha" class="form-label">
                                Résolvez cette addition simple
                                <span class="form-text">(Protection contre les attaques automatisées)</span>
                            </label>
                            <div class="captcha-container">
                                <div class="captcha-question">
                                    <span id="captchaQuestion">{{ $captcha_question ?? '5 + 3' }}</span>
                                    <button type="button" class="captcha-refresh" id="refreshCaptcha" title="Nouvelle question">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <input type="number" 
                                       class="form-control @error('captcha') is-invalid @enderror" 
                                       id="captcha" 
                                       name="captcha" 
                                       placeholder="Votre réponse" 
                                       min="1"
                                       max="10"
                                       required
                                       autocomplete="off"
                                       aria-describedby="captcha-help">
                            </div>
                            @error('captcha')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="captcha-help" class="form-text">
                                <i class="fas fa-info-circle"></i>
                                Réponse attendue entre 1 et 10
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="remember" 
                               name="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <button type="submit" class="btn-login" id="loginBtn">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="btn-text">Se connecter</span>
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 rounded-xl border border-green-200">
                        <i class="fas fa-shield-alt"></i>
                        <span class="text-sm font-medium">Accès sécurisé au système de gestion forestière</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        document.getElementById('passwordToggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
                this.setAttribute('aria-label', 'Masquer le mot de passe');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
                this.setAttribute('aria-label', 'Afficher le mot de passe');
            }
        });

        // Captcha functionality
        let currentCaptchaAnswer = {{ $captcha_answer ?? 8 }};
        
        function generateCaptcha() {
            // Generate captcha question with sum between 1-10
            const maxSum = 10;
            const num1 = Math.floor(Math.random() * (maxSum - 1)) + 1;
            const num2 = Math.floor(Math.random() * (maxSum - num1)) + 1;
            const question = `${num1} + ${num2}`;
            const answer = num1 + num2;
            
            document.getElementById('captchaQuestion').textContent = question;
            document.getElementById('captcha').value = '';
            currentCaptchaAnswer = answer;
            
            // Reset validation states
            const captchaQuestion = document.querySelector('.captcha-question');
            const captchaInput = document.getElementById('captcha');
            captchaQuestion.classList.remove('valid', 'invalid');
            captchaInput.classList.remove('is-valid', 'is-invalid');
        }
        
        function validateCaptcha() {
            const captchaInput = document.getElementById('captcha');
            const captchaQuestion = document.querySelector('.captcha-question');
            const userAnswer = parseInt(captchaInput.value);
            
            captchaQuestion.classList.remove('valid', 'invalid');
            captchaInput.classList.remove('is-valid', 'is-invalid');
            
            if (captchaInput.value === '') {
                return false;
            }
            
            // Check if answer is within valid range (1-10)
            if (userAnswer < 1 || userAnswer > 10) {
                captchaQuestion.classList.add('invalid');
                captchaInput.classList.add('is-invalid');
                return false;
            }
            
            if (userAnswer === currentCaptchaAnswer) {
                captchaQuestion.classList.add('valid');
                captchaInput.classList.add('is-valid');
                return true;
            } else {
                captchaQuestion.classList.add('invalid');
                captchaInput.classList.add('is-invalid');
                return false;
            }
        }
        
        // Captcha refresh button
        document.getElementById('refreshCaptcha').addEventListener('click', function() {
            // Show loading state
            const refreshBtn = this;
            const originalContent = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            refreshBtn.disabled = true;
            
            // Fetch new captcha from server
            fetch('/captcha/refresh')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captchaQuestion').textContent = data.question;
                    document.getElementById('captcha').value = '';
                    currentCaptchaAnswer = data.answer;
                    
                    // Reset validation states
                    const captchaQuestion = document.querySelector('.captcha-question');
                    const captchaInput = document.getElementById('captcha');
                    captchaQuestion.classList.remove('valid', 'invalid');
                    captchaInput.classList.remove('is-valid', 'is-invalid');
                })
                .catch(error => {
                    console.error('Error refreshing captcha:', error);
                    // Fallback to client-side generation
                    generateCaptcha();
                })
                .finally(() => {
                    // Restore button state
                    refreshBtn.innerHTML = originalContent;
                    refreshBtn.disabled = false;
                });
        });
        
        // Captcha input validation
        document.getElementById('captcha').addEventListener('input', function() {
            validateCaptcha();
        });
        
        document.getElementById('captcha').addEventListener('blur', function() {
            validateCaptcha();
        });

        // Real-time form validation
        function validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;
            
            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            if (value === '') {
                return false;
            }
            
            // PPR validation (basic check for non-empty)
            if (fieldName === 'ppr' && value.length >= 3) {
                field.classList.add('is-valid');
                return true;
            }
            
            // Password validation (basic check for minimum length)
            if (fieldName === 'password' && value.length >= 6) {
                field.classList.add('is-valid');
                return true;
            }
            
            return false;
        }

        // Add real-time validation to form fields
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                validateField(this);
            });
            
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        // Enhanced form submission with validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const pprField = document.getElementById('ppr');
            const passwordField = document.getElementById('password');
            const captchaField = document.getElementById('captcha');
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            
            // Validate fields before submission
            const pprValid = validateField(pprField);
            const passwordValid = validateField(passwordField);
            const captchaValid = validateCaptcha();
            
            if (!pprValid || !passwordValid || !captchaValid) {
                e.preventDefault();
                
                if (!pprValid) {
                    pprField.classList.add('is-invalid');
                    pprField.focus();
                } else if (!passwordValid) {
                    passwordField.classList.add('is-invalid');
                    passwordField.focus();
                } else if (!captchaValid) {
                    captchaField.classList.add('is-invalid');
                    captchaField.focus();
                }
                return;
            }
            
            // Add loading state
            btn.classList.add('loading');
            btnText.textContent = 'Connexion...';
            btn.disabled = true;
            
            // Re-enable button after 10 seconds (fallback)
            setTimeout(() => {
                btn.classList.remove('loading');
                btnText.textContent = 'Se connecter';
                btn.disabled = false;
            }, 10000);
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Enter key on password field should submit form
            if (e.key === 'Enter' && e.target.id === 'password') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
            
            // Escape key to clear password field
            if (e.key === 'Escape' && e.target.id === 'password') {
                e.target.value = '';
                e.target.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Auto-focus management
        document.addEventListener('DOMContentLoaded', function() {
            const pprField = document.getElementById('ppr');
            if (pprField && !pprField.value) {
                pprField.focus();
            }
        });

        // Add focus effects to form inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Accessibility improvements
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.setAttribute('aria-expanded', 'true');
            });
            
            input.addEventListener('blur', function() {
                this.setAttribute('aria-expanded', 'false');
            });
        });
    </script>
</body>
</html> 