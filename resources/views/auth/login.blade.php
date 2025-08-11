<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Forestière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --anef-green: #4a7c59;
            --anef-orange: #e67e22;
            --anef-dark-green: #2d5016;
            --anef-light-orange: #f39c12;
            --anef-light-green: #7fb069;
            --anef-soft-green: #e8f5e8;
            --anef-soft-orange: #fef7f0;
            --google-gray: #5f6368;
            --google-light-gray: #f8f9fa;
            --google-border: #dadce0;
            --google-text: #202124;
            --google-blue: #1a73e8;
        }

        body {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Google Sans', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid var(--google-border);
            max-width: 450px;
            width: 100%;
            padding: 48px 40px 36px;
            box-sizing: border-box;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-header h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 400;
            color: var(--google-text);
            line-height: 1.3333;
        }
        
        .login-header p {
            margin: 0;
            font-size: 16px;
            font-weight: 400;
            color: var(--google-gray);
            line-height: 1.5;
        }

        .logo-icon {
            font-size: 24px;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-body {
            margin-top: 24px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--google-text);
            margin-bottom: 8px;
        }
        
        .form-control {
            width: 100%;
            height: 56px;
            padding: 13px 15px;
            font-size: 16px;
            border: 1px solid var(--google-border);
            border-radius: 4px;
            background: white;
            box-sizing: border-box;
            transition: border-color 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--google-blue);
            outline: none;
            border-width: 2px;
        }
        
        .form-control::placeholder {
            color: var(--google-gray);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
            border: none;
            border-radius: 4px;
            height: 36px;
            padding: 0 24px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
            min-width: 88px;
            text-transform: none;
            letter-spacing: normal;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #0f172a 0%, #365a3f 50%, #d35400 100%);
            box-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .btn-login:active {
            box-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .form-check {
            margin: 24px 0;
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            accent-color: var(--anef-green);
        }

        .form-check-label {
            font-size: 14px;
            color: var(--google-text);
            cursor: pointer;
        }
        
        .alert {
            border-radius: 4px;
            border: 1px solid #fce8e6;
            background: #fce8e6;
            color: #d93025;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .invalid-feedback {
            font-size: 12px;
            color: #d93025;
            margin-top: 4px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 32px;
        }

        .security-note {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: var(--google-gray);
        }

        @media (max-width: 480px) {
            body {
                padding: 16px;
            }
            
            .login-card {
                padding: 24px 20px;
            }
            
            .login-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-tree logo-icon"></i>
            <h1>Se connecter</h1>
            <p>Utilisez votre compte ANEF</p>
        </div>
        
        <div class="login-body">
            @if ($errors->any())
                <div class="alert">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="ppr" class="form-label">PPR</label>
                    <input type="text" 
                           class="form-control @error('ppr') is-invalid @enderror" 
                           id="ppr" 
                           name="ppr" 
                           value="{{ old('ppr') }}" 
                           placeholder="Entrez votre PPR" 
                           required 
                           autofocus>
                    @error('ppr')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Entrez votre mot de passe" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                    <div></div>
                    <button type="submit" class="btn-login">
                        Se connecter
                    </button>
                </div>
            </form>
            
            <div class="security-note">
                Accès sécurisé au système de gestion forestière
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 