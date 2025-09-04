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
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            padding: 3rem 2.5rem;
            box-sizing: border-box;
        }



        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            line-height: 1.2;
        }
        
        .login-header p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
            color: #6b7280;
            line-height: 1.5;
        }

        .logo-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
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
            border-radius: 0.75rem;
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
            border-radius: 0.75rem;
            height: 3rem;
            padding: 0 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 8rem;
            text-transform: none;
            letter-spacing: normal;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
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
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
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



        /* Mobile and touch optimizations */
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 1rem;
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
            }

            .password-toggle {
                padding: 0.75rem;
                right: 0.75rem;
            }

            .btn-login {
                height: 3.5rem;
                font-size: 1.1rem;
                min-width: 10rem;
            }

            .form-check-input {
                width: 1.5rem;
                height: 1.5rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .password-toggle {
                padding: 0.75rem;
            }

            .btn-login:hover {
                transform: none;
            }

            .form-control:focus {
                transform: none;
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

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-tree logo-icon"></i>
                <h1>Se connecter</h1>
                <p>Accédez à votre compte SylvaNet</p>
            </div>
            
            <div class="login-body">
                @if ($errors->any())
                    <div class="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" id="loginForm">
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
                    
                    <div class="button-container">
                        <button type="submit" class="btn-login" id="loginBtn">
                            <span class="btn-text">Se connecter</span>
                        </button>
                    </div>
                </form>
                
                <div class="security-note">
                    <i class="fas fa-shield-alt me-1"></i>
                    Accès sécurisé au système de gestion forestière
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
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            
            // Validate fields before submission
            const pprValid = validateField(pprField);
            const passwordValid = validateField(passwordField);
            
            if (!pprValid || !passwordValid) {
                e.preventDefault();
                
                if (!pprValid) {
                    pprField.classList.add('is-invalid');
                    pprField.focus();
                } else if (!passwordValid) {
                    passwordField.classList.add('is-invalid');
                    passwordField.focus();
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