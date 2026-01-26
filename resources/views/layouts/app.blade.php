<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'defatp - Gestion Forestière')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Component Styles -->
    @stack('styles')
    
    <!-- Custom Styles -->
    <style>
        :root {
            /* Theme palette - primary gradient */
            --primary: #059669;
            --primary-hover: #047857;
            --primary-gradient: linear-gradient(135deg, #059669, #047857);
            --secondary: #9AB3A3;
            --background: #F2F6F3;
            --card-bg: #FFFFFF;
            --text-on-dark: #FFFFFF;
            --text-primary: #1F2D24;
            --text-secondary: #6B7C72;
            --text-on-light: #1F2D24;
            /* Semantic tokens */
            --primary-color: #059669;
            --secondary-color: #9AB3A3;
            --success-color: #059669;
            --warning-color: #9AB3A3;
            --danger-color: #1F2D24;
            --info-color: #9AB3A3;
            --light-color: #F2F6F3;
            --dark-color: #1F2D24;
            --border-color: rgba(154, 179, 163, 0.4);
            --shadow-color: rgba(0, 0, 0, 0.06);
            
            /* Spacing & radii - soft rounded (16–20px) */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
            --radius-sm: 0.5rem;      /* 8px */
            --radius-md: 0.75rem;     /* 12px */
            --radius-card: 1rem;      /* 16px */
            --radius-xl: 1.25rem;     /* 20px */
            --border-radius-sm: 0.5rem;
            --border-radius-md: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.25rem;
            /* Subtle card elevation */
            --shadow-card: 0 2px 8px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-card-hover: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px -2px rgba(0, 0, 0, 0.06), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 12px 24px -4px rgba(0, 0, 0, 0.08), 0 4px 8px -2px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 20px 40px -8px rgba(0, 0, 0, 0.08);
            /* Layout gaps & radii for floating panels */
            --layout-gap: 16px;
            --sidebar-width: 260px;
            --layout-radius: 1.25rem;
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--background);
            min-height: 100vh;
            line-height: 1.6;
            color: var(--text-on-light);
        }

        /* Global UX Enhancements */
        * {
            box-sizing: border-box;
        }

        /* Smooth scrolling - only for anchor links, not general scrolling */
        html {
            scroll-behavior: auto; /* Changed from smooth to auto for better performance */
        }
        
        /* Smooth scroll only for programmatic scrolling (anchor links) */
        html:has(a[href^="#"]:target) {
            scroll-behavior: smooth;
        }

        /* Focus management */
        *:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Session alerts - white card, soft radius */
        .alert.alert-success {
            background: var(--card-bg);
            border: 1px solid var(--primary);
            border-left-width: 4px;
            color: var(--text-primary);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
        }
        .alert.alert-danger {
            background: var(--card-bg);
            border: 1px solid var(--danger-color);
            border-left-width: 4px;
            color: var(--text-primary);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
        }
        .alert.alert-warning {
            background: var(--card-bg);
            border: 1px solid var(--secondary);
            border-left-width: 4px;
            color: var(--text-primary);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
        }

        /* Skip to content link for accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            padding: 8px;
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            z-index: 10000;
            transition: var(--transition-fast);
        }

        .skip-link:focus {
            top: 6px;
        }

        /* Loading states */
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 1rem 1.25rem;
            border-left: 4px solid var(--primary-color);
            transform: translateX(100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-width: 320px;
        }

        .toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .toast.show {
            transform: translateX(0);
            animation: slideInRight 0.4s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast.success {
            border-left-color: var(--primary);
            background: var(--background);
        }

        .toast.error {
            border-left-color: var(--danger-color);
            background: var(--background);
        }

        .toast.warning {
            border-left-color: var(--secondary);
            background: var(--background);
        }

        .toast.info {
            border-left-color: var(--secondary);
            background: var(--background);
        }

        .toast-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .toast-title {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-on-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: var(--transition-fast);
            font-size: 1rem;
        }

        .toast-close:hover {
            color: var(--text-on-light);
            background: rgba(0, 0, 0, 0.05);
        }

        .toast-message {
            font-size: 0.875rem;
            color: var(--text-on-light);
            line-height: 1.4;
        }

        .toast-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.75rem;
        }

        .toast.success .toast-icon {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
        }

        .toast.error .toast-icon {
            background: var(--danger-color);
            color: var(--text-on-dark);
        }

        .toast.warning .toast-icon {
            background: var(--secondary);
            color: var(--text-on-dark);
        }

        .toast.info .toast-icon {
            background: var(--secondary);
            color: var(--text-on-dark);
        }

        /* Enhanced form styles */
        .form-control, .form-select {
            border-radius: var(--border-radius-md);
            border: 2px solid var(--border-color);
            transition: var(--transition-normal);
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 82, 57, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-control.is-valid {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 82, 57, 0.15);
        }

        /* Enhanced button styles */
        .btn {
            border-radius: var(--border-radius-md);
            font-weight: 500;
            transition: var(--transition-normal);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            padding: 0.75rem 1.5rem;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--text-on-light);
            padding: 0.75rem 1.5rem;
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--primary-hover);
            color: var(--text-on-dark);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 0.75rem 1.5rem;
        }

        .btn-outline:hover:not(:disabled) {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
        }

        .btn-success {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            border: none;
            padding: 0.75rem 1.5rem;
        }

        .btn-success:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Card styles - white, soft radius, subtle elevation */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-color);
            transition: var(--transition-normal);
        }

        .card:hover {
            box-shadow: var(--shadow-card-hover);
        }

        .section-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: rgba(242, 246, 243, 0.6);
        }

        /* Enhanced table styles */
        .table {
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table th {
            background: rgba(242, 246, 243, 0.8);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
            color: var(--text-on-light);
            border-bottom: 2px solid var(--border-color);
        }

        .table td {
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background-color: rgba(46, 82, 57, 0.06);
        }

        /* Enhanced modal styles */
        .modal-content {
            border-radius: var(--radius-xl);
            border: none;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            border-bottom: none;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        @media (prefers-contrast: high) {
            .btn, .form-control, .card {
                border-width: 2px;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .btn {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }

            .form-control, .form-select {
                padding: 1rem;
                font-size: 1rem;
            }

            .toast-container {
                left: 20px;
                right: 20px;
                max-width: none;
            }
        }

        /* Sidebar - floating panel with gap and radius */
        .sidebar {
            background: var(--background);
            border: 1px solid var(--border-color);
            position: fixed !important;
            top: var(--layout-gap) !important;
            left: var(--layout-gap) !important;
            bottom: var(--layout-gap) !important;
            height: auto !important;
            min-height: calc(100vh - 2 * var(--layout-gap));
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            width: var(--sidebar-width);
            border-radius: var(--layout-radius);
            box-shadow: var(--shadow-card);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            background: transparent;
            color: var(--text-on-light);
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-on-dark);
            font-size: 1.25rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .sidebar-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-on-light);
        }

        .sidebar-header p {
            display: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
            background: transparent;
        }

        .nav-section-label {
            padding: 0.5rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-top: 1rem;
        }

        .nav-section-label:first-child {
            margin-top: 0;
        }

        .nav-item {
            margin: 0.25rem 0.75rem;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-on-light);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
            position: relative;
            background: transparent;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: var(--primary-gradient);
            border-radius: 0 2px 2px 0;
            transition: height 0.2s ease;
        }

        .nav-link:hover {
            color: var(--text-on-light);
            background: rgba(46, 82, 57, 0.08);
        }

        .nav-link.active {
            color: var(--text-on-light);
            font-weight: 600;
            background: transparent;
        }

        .nav-link.active::before {
            height: 60%;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .nav-link.active i {
            color: var(--primary);
        }

        /* Submenu styling */
        .submenu {
            margin-left: 1rem;
            margin-top: 0.5rem;
            border-left: 2px solid var(--border-color);
            padding-left: 1rem;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-on-light);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            margin: 0.25rem 0;
            position: relative;
        }

        .submenu-item:hover {
            background: rgba(46, 82, 57, 0.08);
            color: var(--primary);
            transform: translateX(4px);
        }

        .submenu-item.active {
            background: rgba(46, 82, 57, 0.12);
            color: var(--primary);
            font-weight: 600;
        }

        .submenu-item i {
            margin-right: 0.75rem;
            width: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Sidebar scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

        /* Logo styling */
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-on-dark);
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            font-size: 2rem;
            color: var(--primary);
        }

        /* Sidebar toggle button */
        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: -2.5rem;
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            background: var(--primary-hover);
            box-shadow: var(--shadow-lg);
        }

        /* Responsive sidebar behavior */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                height: 100vh !important;
                min-height: 100vh;
                width: 280px;
                border-radius: 0;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .sidebar.open {
                transform: translateX(0);
                border-radius: 0 var(--layout-radius) var(--layout-radius) 0;
            }
            
            .content-wrapper {
                margin-left: 0;
                min-height: calc(100vh - 2 * var(--layout-gap));
            }

            .nav-item {
                margin: 0.25rem 0.75rem;
            }

            .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .breadcrumb-section {
                max-width: 100%;
            }

            .user-info {
                display: none;
            }
        }

        @media (min-width: 1024px) {
            .sidebar {
                position: fixed !important;
                transform: translateX(0) !important;
            }
        }

        /* Sidebar backdrop overlay for mobile */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* Main layout - padding creates gap around sidebar & content */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
            padding: var(--layout-gap);
            box-sizing: border-box;
            background: #FFFFFF;
        }

        .content-wrapper {
            flex: 1;
            background: #FFFFFF;
            margin-left: calc(var(--sidebar-width) + var(--layout-gap));
            margin-top: 0;
            margin-right: 0;
            margin-bottom: 0;
            min-height: calc(100vh - 2 * var(--layout-gap));
            padding: 0;
            border-radius: var(--layout-radius);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-color);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        /* Hide sidebar on article create/show/edit pages */
        .content-wrapper.no-sidebar {
            margin-left: 0;
            max-width: 100%;
        }

        .sidebar.hidden {
            display: none;
        }

        /* Content section - balanced spacing, grid-friendly */
        .content-area {
            flex: 1;
            min-width: 0;
            padding: 2rem;
            min-height: calc(100vh - 56px - 2 * var(--layout-gap));
            background: #FFFFFF;
            overflow-x: hidden;
        }

        /* Header Styles */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .greeting-section h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .greeting-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Card styles - white, soft corners, subtle elevation */
        .glassmorphism-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            transition: var(--transition-normal);
        }

        .glassmorphism-card:hover {
            box-shadow: var(--shadow-card-hover);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--text-on-light);
        }

        .btn-secondary:hover {
            background: var(--primary-hover);
            color: var(--text-on-dark);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
        }

        .btn-success:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--text-on-dark);
        }

        .btn-danger:hover {
            background: var(--text-on-light);
            color: var(--text-on-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            transform: translateY(-2px);
        }

        /* Form Styles */
        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 82, 57, 0.15);
            background: var(--background);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Section Titles */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        /* Stats Cards */
        .stats-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Quick Action Icons */
        .quick-action-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        /* Mobile Responsiveness */
        @media (max-width: 1023px) {
            .content-wrapper {
                margin: var(--layout-gap);
                margin-left: var(--layout-gap);
                border-radius: var(--layout-radius);
            }

            .content-area {
                padding: 1rem;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (max-width: 768px) {
            .greeting-section h1 {
                font-size: 1.5rem;
            }

            .greeting-subtitle {
                font-size: 1rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }

            .glassmorphism-card {
                margin: 0.5rem 0;
            }
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        .slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--primary-color);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Notification System */
        .notification-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
            max-width: 24rem;
        }

        .notification {
            background: white;
            border-left: 4px solid;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transform: translateX(0);
            transition: all 0.3s ease-in-out;
            animation: slideInRight 0.3s ease-out;
        }

        .notification.success {
            border-color: var(--primary);
            background-color: var(--background);
        }

        .notification.error {
            border-color: var(--danger-color);
            background-color: var(--background);
        }

        .notification.warning {
            border-color: var(--secondary);
            background-color: var(--background);
        }

        .notification.info {
            border-color: var(--secondary);
            background-color: var(--background);
        }

        /* Form Wizard */
        .form-wizard {
            margin-bottom: 1.5rem;
        }

        .wizard-step {
            display: none;
        }

        .wizard-step.active {
            display: block;
        }

        .wizard-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 0.5rem;
        }

        .wizard-step-indicator {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            border: 2px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .wizard-step-indicator.active {
            border-color: var(--primary);
            background: var(--primary-gradient);
            color: var(--text-on-dark);
        }

        .wizard-step-indicator.completed {
            border-color: var(--primary);
            background: var(--primary-gradient);
            color: var(--text-on-dark);
        }

        .wizard-step-indicator.pending {
            border-color: var(--border-color);
            background-color: var(--background);
            color: var(--text-on-light);
        }

        .wizard-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        /* Tooltip System */
        .tooltip-container {
            position: relative;
            display: inline-block;
        }

        .tooltip {
            position: absolute;
            z-index: 10;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            color: var(--text-on-dark);
            background-color: var(--text-on-light);
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: var(--text-on-light);
        }

        .tooltip-container:hover .tooltip {
            opacity: 1;
        }

        /* Help System */
        .help-icon {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            color: var(--primary);
            cursor: help;
            margin-left: 0.25rem;
        }

        .field-help {
            font-size: 0.75rem;
            color: var(--text-on-light);
            margin-top: 0.25rem;
            font-style: italic;
        }

        /* Welcome Guide */
        .welcome-guide {
            background-color: var(--background);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Top bar - inside content panel, rounded top via parent */
        .top-header {
            background: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 56px;
            flex-shrink: 0;
        }

        .breadcrumb-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            min-width: 0;
        }

        .breadcrumb-nav {
            flex: 1;
            min-width: 0;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.25rem 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        /* separator: use only Bootstrap's ::before to avoid double slash */
        .breadcrumb-item:not(:last-child)::after {
            display: none;
        }

        .breadcrumb-item a {
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .breadcrumb-item.active,
        .breadcrumb-item:last-child {
            color: var(--text-secondary);
        }

        .breadcrumb-item.active a,
        .breadcrumb-item:last-child a {
            color: var(--text-secondary);
            pointer-events: none;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-icon-btn {
            position: relative;
            padding: 0.375rem 0.5rem;
            color: var(--text-on-light);
            cursor: pointer;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            background: transparent;
            border: none;
            font-size: 1rem;
        }

        .header-icon-btn:hover {
            background: var(--secondary);
            color: var(--text-on-light);
        }

        .notification-dot {
            position: absolute;
            top: 0.125rem;
            right: 0.125rem;
            width: 0.375rem;
            height: 0.375rem;
            background: var(--primary-gradient);
            border-radius: 50%;
            border: 1.5px solid var(--background);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .user-profile:hover {
            background: var(--border-color);
        }

        .profile-pic {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-on-light);
            font-size: 0.8125rem;
            line-height: 1.2;
        }

        .user-email {
            font-size: 0.6875rem;
            color: var(--text-secondary);
            line-height: 1.2;
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        /* Category Cards */
        .category-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .category-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition-normal);
        }

        .category-card:hover {
            box-shadow: var(--shadow-card-hover);
        }

        .category-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .category-icon.design { background: rgba(154, 179, 163, 0.3); color: var(--text-on-light); }
        .category-icon.documents { background: rgba(46, 82, 57, 0.15); color: var(--primary); }
        .category-icon.music { background: rgba(46, 82, 57, 0.2); color: var(--primary); }
        .category-icon.images { background: rgba(154, 179, 163, 0.4); color: var(--text-on-light); }

        .category-info h3 {
            font-weight: 600;
            color: var(--text-on-light);
            margin: 0 0 0.25rem 0;
        }

        .category-info p {
            color: var(--text-secondary);
            margin: 0 0 0.25rem 0;
        }

        .category-size {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .category-options {
            margin-left: auto;
            color: var(--secondary);
            cursor: pointer;
        }

        /* Quick Access Section */
        .quick-access-section {
            margin-bottom: 2rem;
        }

        .quick-access-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .quick-access-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition-normal);
        }

        .quick-access-card:hover {
            box-shadow: var(--shadow-card-hover);
        }

        .quick-access-preview {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .quick-access-info {
            flex: 1;
        }

        .quick-access-info h4 {
            font-weight: 600;
            color: var(--text-on-light);
            margin: 0 0 0.25rem 0;
        }

        .quick-access-info p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.875rem;
        }

        .quick-access-btn {
            padding: 0.5rem 1rem;
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .quick-access-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Recently Added Section */
        .recently-added-section {
            margin-bottom: 2rem;
        }

        .recent-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }

        .recent-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-card);
        }

        .recent-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(154, 179, 163, 0.25);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-on-light);
        }

        .recent-info h4 {
            font-weight: 600;
            color: var(--text-on-light);
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
        }

        .recent-info p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.75rem;
        }

        .recent-size {
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* File Manager Section */
        .file-manager-section {
            margin-bottom: 2rem;
        }

        .file-manager-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .file-manager-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1rem;
            text-align: center;
            box-shadow: var(--shadow-card);
            transition: var(--transition-normal);
        }

        .file-manager-card:hover {
            box-shadow: var(--shadow-card-hover);
        }

        .file-manager-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(154, 179, 163, 0.25);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem auto;
            color: var(--text-on-light);
        }

        .file-manager-info h4 {
            font-weight: 600;
            color: var(--text-on-light);
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
        }

        .file-manager-info p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.75rem;
        }

        .open-file-manager-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .open-file-manager-btn:hover {
            background: var(--primary-hover);
            text-decoration: none;
            color: var(--text-on-dark);
        }

        /* Data Table Section */
        .data-table-section {
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-actions {
            display: flex;
            gap: 0.75rem;
        }

        .data-table-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1.5rem;
            overflow-x: auto;
            box-shadow: var(--shadow-card);
        }

        /* Quick Create Section */
        .quick-create-section {
            margin-bottom: 2rem;
        }

        .quick-create-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-create-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            box-shadow: var(--shadow-card);
            transition: var(--transition-normal);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .quick-create-card:hover {
            box-shadow: var(--shadow-card-hover);
            text-decoration: none;
            color: inherit;
        }

        .quick-create-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 1.5rem;
        }

        .quick-create-icon.essence { background: rgba(46, 82, 57, 0.15); color: var(--primary); }
        .quick-create-icon.foret { background: rgba(154, 179, 163, 0.4); color: var(--text-on-light); }
        .quick-create-icon.situation { background: rgba(154, 179, 163, 0.35); color: var(--text-on-light); }
        .quick-create-icon.exploitant { background: rgba(31, 45, 36, 0.1); color: var(--text-on-light); }
        .quick-create-icon.nature { background: rgba(46, 82, 57, 0.2); color: var(--primary); }

        .quick-create-card h4 {
            font-weight: 600;
            color: var(--text-on-light);
            margin: 0 0 0.5rem 0;
        }

        .quick-create-card p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.875rem;
        }

        /* Tab button links styling */
        .tabs-section .btn {
            text-decoration: none;
            color: inherit;
        }

        .tabs-section .btn:hover {
            text-decoration: none;
            color: inherit;
        }

        /* Entities Tabs Section */
        .entities-data-section .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }

        .entities-data-section .card-header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0;
        }

        .entities-data-section .nav-tabs {
            border-bottom: none;
            margin: 0;
        }

        .entities-data-section .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            padding: 1rem 1.5rem;
            color: var(--text-on-light);
            font-weight: 500;
            transition: all 0.3s ease;
            background: transparent;
        }

        .entities-data-section .nav-tabs .nav-link:hover {
            color: var(--primary);
            background: rgba(46, 82, 57, 0.08);
            border: none;
        }

        .entities-data-section .nav-tabs .nav-link.active {
            color: var(--primary);
            background: rgba(46, 82, 57, 0.12);
            border: none;
            border-bottom: 3px solid var(--primary);
        }

        .entities-data-section .card-body {
            padding: 1.5rem;
        }

        .entities-data-section .tab-pane {
            padding: 0;
        }

        .entities-data-section .tab-pane h5 {
            color: var(--text-on-light);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Entity Data Cards Styling */
        .entities-data-section {
            margin-bottom: 2rem;
        }

        .entity-data-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transition: var(--transition-normal);
        }

        .entity-data-card:hover {
            box-shadow: var(--shadow-card-hover);
        }

        .entity-data-card .card-header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        .entity-data-card .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-on-light);
        }

        .entity-data-card .card-body {
            padding: 1.5rem;
        }

        .entity-data-card .table {
            margin-bottom: 0;
        }

        .entity-data-card .table th {
            background: var(--background);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-on-light);
        }

        .entity-data-card .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            vertical-align: middle;
        }

        .entity-data-card .table tbody tr:hover {
            background-color: rgba(46, 82, 57, 0.06);
        }

        /* Stats Section */
        .stats-section {
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stats-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-card);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-on-light);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Welcome Section */
        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-icon {
            margin-bottom: 1rem;
        }

        /* Quick Tips Section */
        .quick-tips-section {
            margin-bottom: 2rem;
        }

        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .tip-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-card);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
        }

        .tip-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .tip-icon.blue { background: rgba(46, 82, 57, 0.2); color: var(--primary); }
        .tip-icon.green { background: rgba(46, 82, 57, 0.15); color: var(--primary); }
        .tip-icon.purple { background: rgba(154, 179, 163, 0.4); color: var(--text-on-light); }
        .tip-icon.orange { background: rgba(154, 179, 163, 0.35); color: var(--text-on-light); }

        .tip-card h3 {
            font-weight: 600;
            color: var(--text-on-light);
            margin: 0 0 0.5rem 0;
        }

        .tip-card p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.875rem;
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Utility Classes */
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .backdrop-blur {
            /* backdrop-filter: blur(10px); Removed for better scroll performance */
            background: rgba(255, 255, 255, 0.95); /* Fallback for removed blur */
        }
        
        /* Performance optimization: Disable backdrop-filter for better scroll performance */
        .backdrop-blur-lg,
        .backdrop-blur-md,
        .backdrop-blur-sm,
        [class*="backdrop-blur"] {
            backdrop-filter: none !important;
        }
        
        /* Optimize scrolling performance */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Force hardware acceleration for scrollable elements */
        body,
        .content-wrapper,
        .sidebar,
        main {
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
        }

        /* Enhanced Focus States */
        .focus-ring {
            outline: none;
            box-shadow: 0 0 0 2px var(--background), 0 0 0 4px var(--primary);
        }

        /* Smooth Transitions */
        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.2s ease-in-out;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Sidebar Backdrop */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile and Tablet Sidebar Styles */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed !important;
                top: 0 !important;
                left: -280px;
                z-index: 1050;
                height: 100vh !important;
                overflow-y: auto;
                overflow-x: hidden;
                transition: left 0.3s ease-in-out;
                width: 280px;
            }
            
            .sidebar.open {
                left: 0 !important;
            }
            
            .content-wrapper {
                margin-left: 0 !important;
            }
        }

        /* Desktop Sidebar Styles - Simple Fixed Layout */
        @media (min-width: 1024px) {
            .sidebar {
                position: fixed !important;
                left: var(--layout-gap) !important;
                top: var(--layout-gap) !important;
                bottom: var(--layout-gap) !important;
                width: var(--sidebar-width);
                min-height: calc(100vh - 2 * var(--layout-gap));
                overflow-y: auto;
                overflow-x: hidden;
                z-index: 1000;
                border-radius: var(--layout-radius);
            }
            
            .sidebar-backdrop {
                display: none;
            }
            
            .content-wrapper {
                margin-left: calc(var(--sidebar-width) + var(--layout-gap));
                min-height: calc(100vh - 2 * var(--layout-gap));
                border-radius: var(--layout-radius);
            }
        }

        /* Mobile Header Improvements */
        @media (max-width: 768px) {
            .content-wrapper {
                padding-top: 0;
            }
            
            .top-header {
                padding: 0.5rem 1rem;
                min-height: 52px;
            }
            
            .content-area {
                padding: 1rem;
                min-height: calc(100vh - 52px);
            }
        }

        /* Tablet Header Improvements */
        @media (min-width: 769px) and (max-width: 1023px) {
            .content-wrapper {
                padding-top: 0;
            }
            
            .top-header {
                padding: 0.625rem 1.25rem;
            }
            
            .content-area {
                padding: 1.25rem;
            }
        }

        /* Mobile Table Responsiveness */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table-responsive table {
                min-width: 600px;
            }
        }

        /* Mobile Form Improvements */
        @media (max-width: 640px) {
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .btn {
                min-height: 44px; /* Touch-friendly button size */
                padding: 0.75rem 1rem;
            }
        }

        /* Mobile Navigation Improvements */
        @media (max-width: 768px) {
            .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            
            .nav-link i {
                margin-right: 0.5rem;
                width: 20px;
                text-align: center;
            }
        }

        /* Tablet Navigation Improvements */
        @media (min-width: 769px) and (max-width: 1023px) {
            .nav-link {
                padding: 0.875rem 1rem;
                font-size: 0.95rem;
            }
            
            .nav-link i {
                margin-right: 0.75rem;
                width: 22px;
                text-align: center;
            }
        }

        /* Mobile Sidebar Navigation */
        @media (max-width: 1023px) {
            .sidebar-nav {
                padding: 1rem 0;
            }
            
            .nav-item {
                margin: 0.25rem 0;
            }
            
            .nav-link {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                margin: 0.25rem 0.5rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
            }
            
            .nav-link:hover {
                background: rgba(74, 124, 89, 0.1);
            }
            
            .nav-link.active {
                background: var(--primary-gradient);
                color: white;
            }
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .print-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>
    
    <!-- Toast notifications container -->
    <div class="toast-container" id="toastContainer"></div>
    
    @php
        // Always show sidebar on all pages
        $hideSidebar = false;
    @endphp

    <!-- Sidebar Backdrop Overlay -->
    @if(!$hideSidebar)
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>
    @endif
    
    <div class="main-wrapper">
        <!-- Left Sidebar -->
        <aside class="sidebar {{ $hideSidebar ? 'hidden' : '' }}" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-tree" style="font-size: 1.25rem;"></i>
                </div>
                <h1>DEFATP</h1>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label">Menu</div>
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        Dashboard
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('articles.index') }}" class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        Exploitation
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('exploitants.index') }}" class="nav-link {{ request()->routeIs('exploitants.*') ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        Exploitant Forêstier
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('entity-data.index') }}" class="nav-link {{ request()->routeIs('entity-data.*') || request()->routeIs('essences.*') || request()->routeIs('forets.*') || request()->routeIs('situations.*') || request()->routeIs('natures.*') || request()->routeIs('vocations.*') || request()->routeIs('coperatives.*') || request()->routeIs('products.*') || request()->routeIs('prestations.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        Données des Entités
                    </a>
                </div>

                <div class="nav-section-label">General</div>
                <div class="nav-item">
                    <a href="{{ route('auth.profile') }}" class="nav-link {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-question-circle"></i>
                        Help
                    </a>
                </div>
                <div class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-link w-full text-left" style="background: none; border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="content-wrapper {{ $hideSidebar ? 'no-sidebar' : '' }}">
            <!-- Top Header with Search and User Profile -->
            <header class="top-header">
                <div class="breadcrumb-section">
                    @if(!$hideSidebar)
                    <button class="header-icon-btn lg:hidden" onclick="toggleSidebar()" title="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    @endif
                    <nav aria-label="Fil d'Ariane" class="breadcrumb-nav">
                        <ol class="breadcrumb">
                            @if(request()->routeIs('dashboard'))
                            <li class="breadcrumb-item active">Tableau de bord</li>
                            @else
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                            </li>
                            @yield('breadcrumb')
                            @endif
                        </ol>
                    </nav>
                </div>
                
                <div class="header-actions">
                    <!-- Notifications -->
                    @auth
                    <button class="header-icon-btn" title="Messages">
                        <i class="far fa-envelope"></i>
                    </button>
                    <button class="header-icon-btn" title="Notifications">
                        <i class="far fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    @endauth
                    
                    <!-- User Profile -->
                    <div class="user-profile" x-data="{ open: false }">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=10b981&color=fff" 
                             alt="Profile" class="profile-pic">
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="user-email">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content-area" id="main-content">
                @if(session('success'))
                    <div class="alert alert-success mb-5 p-5 rounded-2xl">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-5 p-5 rounded-2xl">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning mb-5 p-5 rounded-2xl">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Global Modal Manager - Load early for availability -->
    <script src="{{ asset('js/modal.js') }}"></script>

    <!-- Additional Scripts -->
    <!-- Excel-style Filter CSS -->
    <style>
        /* Excel-style filter dropdown */
        .filter-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 2px 4px;
            transition: all 0.2s;
        }
        
        .filter-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 3px;
        }
        
        .filter-btn.active {
            color: #3b82f6 !important;
        }
        
        .filter-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 1000;
            min-width: 250px;
            max-width: 350px;
            max-height: 400px;
            display: none;
            font-size: 13px;
        }
        
        .filter-dropdown.show {
            display: block;
        }
        
        .filter-dropdown-header {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border-color);
            background: var(--background);
        }
        
        .filter-dropdown-body {
            padding: 8px;
            max-height: 280px;
            overflow-y: auto;
        }
        
        .filter-search {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .filter-search:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(46, 82, 57, 0.15);
        }
        
        .filter-options {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .filter-option {
            padding: 4px 8px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.15s;
        }
        
        .filter-option:hover {
            background-color: rgba(46, 82, 57, 0.06);
        }
        
        .filter-option input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
        }
        
        .filter-option label {
            cursor: pointer;
            flex: 1;
            user-select: none;
        }
        
        .filter-dropdown-footer {
            padding: 8px 12px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        
        .filter-btn-ok {
            background: var(--primary-gradient);
            color: var(--text-on-dark);
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
        }
        
        .filter-btn-ok:hover {
            background-color: var(--primary-hover);
        }
        
        .filter-btn-cancel {
            background-color: var(--background);
            color: var(--text-on-light);
            border: 1px solid var(--border-color);
            padding: 6px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        
        .filter-btn-cancel:hover {
            background-color: rgba(154, 179, 163, 0.2);
        }
        
        .filter-clear {
            color: var(--primary);
            text-decoration: none;
            font-size: 12px;
            padding: 4px 8px;
            display: inline-block;
            margin-bottom: 8px;
        }
        
        .filter-clear:hover {
            text-decoration: underline;
        }
    </style>

    @stack('scripts')
    
    <!-- Excel-style Filter JavaScript -->
    <script src="{{ asset('js/excel-filters.js') }}"></script>

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            // Only work on mobile and tablet devices
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('active');
            }
        }

        // Close sidebar
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            sidebar.classList.remove('open');
            backdrop.classList.remove('active');
        }

        // Close sidebar when clicking outside on mobile/tablet
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('.lg\\:hidden');
            
            if (window.innerWidth < 1024 && 
                sidebar.classList.contains('open') &&
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target)) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });


        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                });
            }, 5000);
        });

        // Global UX Enhancement Functions
        window.UXUtils = {
            // Enhanced Toast notification system
            showToast: function(message, type = 'info', options = {}) {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                
                const defaults = {
                    duration: 5000,
                    title: null,
                    closable: true,
                    position: 'top-right',
                    sound: false,
                    action: null
                };
                
                const config = { ...defaults, ...options };
                
                toast.className = `toast ${type}`;
                toast.id = toastId;
                
                const icons = {
                    success: 'fas fa-check-circle',
                    error: 'fas fa-exclamation-circle',
                    warning: 'fas fa-exclamation-triangle',
                    info: 'fas fa-info-circle'
                };
                
                const titles = {
                    success: 'Succès',
                    error: 'Erreur',
                    warning: 'Attention',
                    info: 'Information'
                };
                
                const icon = icons[type] || icons.info;
                const title = config.title || titles[type] || titles.info;
                
                toast.innerHTML = `
                    <div class="toast-header">
                        <div class="toast-title">
                            <div class="toast-icon">
                                <i class="${icon}"></i>
                            </div>
                            ${title}
                        </div>
                        ${config.closable ? '<button class="toast-close" onclick="UXUtils.closeToast(\'' + toastId + '\')"><i class="fas fa-times"></i></button>' : ''}
                    </div>
                    <div class="toast-message">${message}</div>
                    ${config.action ? '<div class="toast-action mt-2"><button class="btn btn-sm btn-outline-primary" onclick="' + config.action + '">Action</button></div>' : ''}
                `;
                
                container.appendChild(toast);
                
                // Trigger animation
                setTimeout(() => toast.classList.add('show'), 100);
                
                // Play sound if enabled
                if (config.sound) {
                    this.playNotificationSound(type);
                }
                
                // Auto remove
                if (config.duration > 0) {
                    setTimeout(() => {
                        this.closeToast(toastId);
                    }, config.duration);
                }
                
                return toastId;
            },

            // Close specific toast
            closeToast: function(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }
            },

            // Close all toasts
            closeAllToasts: function() {
                const toasts = document.querySelectorAll('.toast');
                toasts.forEach(toast => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                });
            },

            // Play notification sound
            playNotificationSound: function(type) {
                const sounds = {
                    success: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT',
                    error: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT',
                    warning: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT',
                    info: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT'
                };
                
                try {
                    const audio = new Audio(sounds[type] || sounds.info);
                    audio.volume = 0.3;
                    audio.play().catch(() => {}); // Ignore errors if audio is blocked
                } catch (e) {
                    // Ignore audio errors
                }
            },

            // Loading state management
            setLoading: function(element, loading = true) {
                if (loading) {
                    element.classList.add('loading');
                    element.disabled = true;
                } else {
                    element.classList.remove('loading');
                    element.disabled = false;
                }
            },

            // Form validation helper
            validateForm: function(form) {
                const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });
                
                return isValid;
            },

            // Enhanced form submission
            submitForm: function(form, options = {}) {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn ? submitBtn.innerHTML : '';
                
                // Show loading state
                if (submitBtn) {
                    this.setLoading(submitBtn, true);
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi...';
                }
                
                // Validate form
                if (!this.validateForm(form)) {
                    this.showToast('Veuillez remplir tous les champs obligatoires', 'error');
                    if (submitBtn) {
                        this.setLoading(submitBtn, false);
                        submitBtn.innerHTML = originalText;
                    }
                    return false;
                }
                
                // Submit form
                form.submit();
                return true;
            },

            // Smooth scroll to element
            scrollTo: function(element, offset = 0) {
                const target = typeof element === 'string' ? document.querySelector(element) : element;
                if (target) {
                    const top = target.offsetTop - offset;
                    window.scrollTo({
                        top: top,
                        behavior: 'smooth'
                    });
                }
            },

            // Copy to clipboard
            copyToClipboard: function(text) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showToast('Copié dans le presse-papiers', 'success', 2000);
                }).catch(() => {
                    this.showToast('Erreur lors de la copie', 'error');
                });
            },

            // Debounce function for search inputs
            debounce: function(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            },

            // Confirmation dialog
            confirm: function(message, options = {}) {
                return new Promise((resolve) => {
                    const defaults = {
                        title: 'Confirmation',
                        confirmText: 'Confirmer',
                        cancelText: 'Annuler',
                        type: 'warning',
                        icon: 'fas fa-question-circle'
                    };
                    
                    const config = { ...defaults, ...options };
                    
                    // Create modal overlay
                    const overlay = document.createElement('div');
                    overlay.className = 'modal-overlay';
                    overlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0, 0, 0, 0.5);
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        animation: fadeIn 0.3s ease;
                    `;
                    
                    // Create modal
                    const modal = document.createElement('div');
                    modal.className = 'confirmation-modal';
                    modal.style.cssText = `
                        background: white;
                        border-radius: 12px;
                        padding: 2rem;
                        max-width: 400px;
                        width: 90%;
                        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
                        animation: slideIn 0.3s ease;
                        text-align: center;
                    `;
                    
                    modal.innerHTML = `
                        <div class="modal-icon" style="font-size: 3rem; color: var(--warning-color); margin-bottom: 1rem;">
                            <i class="${config.icon}"></i>
                        </div>
                        <h3 style="margin-bottom: 1rem; color: #1F2D24;">${config.title}</h3>
                        <p style="margin-bottom: 2rem; color: #1F2D24; line-height: 1.5; opacity: 0.9;">${message}</p>
                        <div style="display: flex; gap: 1rem; justify-content: center;">
                            <button class="btn btn-secondary cancel-btn" style="padding: 0.75rem 1.5rem;">
                                ${config.cancelText}
                            </button>
                            <button class="btn btn-danger confirm-btn" style="padding: 0.75rem 1.5rem;">
                                ${config.confirmText}
                            </button>
                        </div>
                    `;
                    
                    overlay.appendChild(modal);
                    document.body.appendChild(overlay);
                    
                    // Add animations
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes fadeIn {
                            from { opacity: 0; }
                            to { opacity: 1; }
                        }
                        @keyframes slideIn {
                            from { transform: translateY(-20px); opacity: 0; }
                            to { transform: translateY(0); opacity: 1; }
                        }
                        @keyframes fadeOut {
                            from { opacity: 1; }
                            to { opacity: 0; }
                        }
                        @keyframes slideOut {
                            from { transform: translateY(0); opacity: 1; }
                            to { transform: translateY(-20px); opacity: 0; }
                        }
                    `;
                    document.head.appendChild(style);
                    
                    // Event listeners
                    const cancelBtn = modal.querySelector('.cancel-btn');
                    const confirmBtn = modal.querySelector('.confirm-btn');
                    
                    const cleanup = () => {
                        overlay.style.animation = 'fadeOut 0.3s ease';
                        modal.style.animation = 'slideOut 0.3s ease';
                        setTimeout(() => {
                            document.body.removeChild(overlay);
                            document.head.removeChild(style);
                        }, 300);
                    };
                    
                    cancelBtn.addEventListener('click', () => {
                        cleanup();
                        resolve(false);
                    });
                    
                    confirmBtn.addEventListener('click', () => {
                        cleanup();
                        resolve(true);
                    });
                    
                    overlay.addEventListener('click', (e) => {
                        if (e.target === overlay) {
                            cleanup();
                            resolve(false);
                        }
                    });
                    
                    // Keyboard support
                    const handleKeydown = (e) => {
                        if (e.key === 'Escape') {
                            cleanup();
                            resolve(false);
                            document.removeEventListener('keydown', handleKeydown);
                        } else if (e.key === 'Enter') {
                            cleanup();
                            resolve(true);
                            document.removeEventListener('keydown', handleKeydown);
                        }
                    };
                    
                    document.addEventListener('keydown', handleKeydown);
                });
            },

            // Success notification with action
            showSuccess: function(message, action = null) {
                return this.showToast(message, 'success', {
                    duration: 4000,
                    sound: true,
                    action: action
                });
            },

            // Error notification with action
            showError: function(message, action = null) {
                return this.showToast(message, 'error', {
                    duration: 6000,
                    sound: true,
                    action: action
                });
            },

            // Warning notification
            showWarning: function(message, action = null) {
                return this.showToast(message, 'warning', {
                    duration: 5000,
                    sound: true,
                    action: action
                });
            },

            // Info notification
            showInfo: function(message, action = null) {
                return this.showToast(message, 'info', {
                    duration: 4000,
                    action: action
                });
            }
        };

        // Global event listeners for UX enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced form handling
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        UXUtils.setLoading(submitBtn, true);
                    }
                });
            });

            // Enhanced button interactions
            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (this.disabled) {
                        e.preventDefault();
                        return false;
                    }
                });
            });

            // Enhanced table interactions
            document.querySelectorAll('.table tbody tr').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('button, a, input, select')) {
                        const link = this.querySelector('a[href]');
                        if (link) {
                            window.location.href = link.href;
                        }
                    }
                });
            });

            // Auto-hide alerts after 5 seconds and convert to toasts
            document.querySelectorAll('.alert').forEach(alert => {
                const alertType = alert.classList.contains('alert-success') ? 'success' :
                                 alert.classList.contains('alert-danger') ? 'error' :
                                 alert.classList.contains('alert-warning') ? 'warning' :
                                 alert.classList.contains('alert-info') ? 'info' : 'info';
                
                const message = alert.textContent.trim();
                
                // Show toast notification
                UXUtils.showToast(message, alertType, {
                    duration: 5000,
                    sound: true
                });
                
                // Hide original alert
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 1000);
            });

            // Add success alerts for form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const formId = this.id || 'form';
                    const submitBtn = this.querySelector('button[type="submit"]');
                    
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        const btnText = submitBtn.querySelector('.btn-text') || submitBtn;
                        
                        // Show loading state
                        UXUtils.setLoading(submitBtn, true);
                        if (btnText) {
                            btnText.textContent = 'Envoi en cours...';
                        }
                        
                        // Store form data for success detection
                        this.setAttribute('data-submitting', 'true');
                        
                        // Reset after a delay (in case of redirect)
                        setTimeout(() => {
                            if (this.getAttribute('data-submitting') === 'true') {
                                UXUtils.setLoading(submitBtn, false);
                                if (btnText) {
                                    btnText.textContent = originalText;
                                }
                                this.removeAttribute('data-submitting');
                            }
                        }, 10000);
                    }
                });
            });

            // Add confirmation dialogs for destructive actions
            document.querySelectorAll('a[href*="delete"], button[onclick*="delete"], .btn-danger[href*="destroy"]').forEach(element => {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const href = this.href || this.getAttribute('onclick');
                    const message = 'Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.';
                    
                    UXUtils.confirm(message, {
                        title: 'Confirmer la suppression',
                        confirmText: 'Supprimer',
                        cancelText: 'Annuler',
                        type: 'error',
                        icon: 'fas fa-trash-alt'
                    }).then(confirmed => {
                        if (confirmed) {
                            if (href) {
                                if (this.href) {
                                    window.location.href = href;
                                } else if (this.getAttribute('onclick')) {
                                    eval(href);
                                }
                            }
                        }
                    });
                });
            });

            // Add success alerts for navigation actions
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success')) {
                const message = urlParams.get('message') || 'Action effectuée avec succès';
                UXUtils.showSuccess(message);
                
                // Clean URL
                urlParams.delete('success');
                urlParams.delete('message');
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState({}, '', newUrl);
            }

            if (urlParams.get('error')) {
                const message = urlParams.get('message') || 'Une erreur s\'est produite';
                UXUtils.showError(message);
                
                // Clean URL
                urlParams.delete('error');
                urlParams.delete('message');
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState({}, '', newUrl);
            }

            // Enhanced focus management
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });

            document.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });

            // Enhanced mobile menu
            const mobileMenuBtn = document.querySelector('[onclick*="sidebar"]');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    const backdrop = document.getElementById('sidebarBackdrop');
                    
                    sidebar.classList.toggle('open');
                    backdrop.classList.toggle('active');
                });
            }

            // Close mobile menu when clicking backdrop
            const backdrop = document.getElementById('sidebarBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.remove('open');
                    this.classList.remove('active');
                });
            }
        });

        // Global error handling
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
            UXUtils.showToast('Une erreur inattendue s\'est produite', 'error');
        });

        // Global unhandled promise rejection handling
        window.addEventListener('unhandledrejection', function(e) {
            console.error('Unhandled promise rejection:', e.reason);
            UXUtils.showToast('Une erreur réseau s\'est produite', 'error');
        });
    </script>
</body>
</html> 