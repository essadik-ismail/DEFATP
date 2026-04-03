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
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #2563eb;
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
            /* Responsive spacing system */
            --spacing-8: 8px;
            --spacing-16: 16px;
            --spacing-24: 24px;
            --spacing-32: 32px;
        }

        html {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--background);
            min-height: 100%;
            height: 100%;
            overflow: hidden;
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
            background: #fef2f2;
            border: 1px solid #dc2626;
            border-left-width: 4px;
            color: #991b1b;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
        }
        .alert.alert-warning {
            background: #fffbeb;
            border: 1px solid #d97706;
            border-left-width: 4px;
            color: #92400e;
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
            border-left-color: #dc2626;
            background: #fef2f2;
        }

        .toast.warning {
            border-left-color: #d97706;
            background: #fffbeb;
        }

        .toast.info {
            border-left-color: #2563eb;
            background: #eff6ff;
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
            background: #dc2626;
            color: var(--text-on-dark);
        }

        .toast.warning .toast-icon {
            background: #d97706;
            color: var(--text-on-dark);
        }

        .toast.info .toast-icon {
            background: #2563eb;
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
            background: #f6f9f7;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 0.7rem;
            color: #6b7c72;
            border-bottom: 1px solid rgba(154,179,163,0.25);
            padding: 0.625rem 0.75rem;
        }

        .table td {
            vertical-align: middle;
            border-bottom: 1px solid rgba(154,179,163,0.15);
            color: #1F2D24;
        }

        .table tbody tr:hover {
            background-color: rgba(52, 211, 153, 0.04);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
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

        /* Modal responsive - full width on small screens */
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: calc(100vw - var(--spacing-32));
                margin: var(--spacing-16) auto;
            }
        }
        @media (max-width: 480px) {
            .modal-dialog {
                max-width: calc(100vw - var(--spacing-16));
                margin: var(--spacing-8) auto;
            }
        }

        /* Container responsive - prevent overflow */
        .page-content .container {
            max-width: 100%;
            padding-left: var(--spacing-16);
            padding-right: var(--spacing-16);
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
            background: #FFFFFF;
            border: 1px solid rgba(154, 179, 163, 0.4);
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
            color: var(--text-primary);
            padding: 1.5rem 1.25rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(154, 179, 163, 0.25);
            margin-bottom: 0.5rem;
        }

        .sidebar-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #34d399, #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(52,211,153,0.35);
        }

        .sidebar-header h1 {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
            letter-spacing: 0.02em;
        }

        .sidebar-header p {
            display: none;
        }

        .sidebar-nav {
            padding: 0.5rem 0 1rem;
            background: transparent;
        }

        .nav-section-label {
            padding: 0.5rem 1.25rem;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(107, 124, 114, 0.8);
            margin-top: 0.75rem;
        }

        .nav-section-label:first-child {
            margin-top: 0;
        }

        .nav-item {
            margin: 0.15rem 0.75rem;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
            position: relative;
            background: transparent;
        }

        .nav-link::before {
            content: none;
        }

        .nav-link:hover {
            color: var(--primary);
            background: rgba(5, 150, 105, 0.08);
        }

        .nav-link.active {
            color: var(--primary);
            font-weight: 600;
            background: rgba(52, 211, 153, 0.12);
            box-shadow: inset 3px 0 0 #34d399;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            color: inherit;
            opacity: 0.8;
        }

        .nav-link:hover i,
        .nav-link.active i {
            opacity: 1;
            color: #34d399;
        }

        /* Nav group title (parent with submenu) - clickable toggle */
        .nav-item.has-submenu .nav-group-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.625rem 1rem;
            color: var(--text-primary);
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            background: none;
            border: none;
            text-align: left;
            transition: all 0.2s ease;
        }

        .nav-item.has-submenu .nav-group-title:hover {
            color: var(--primary);
            background: rgba(5, 150, 105, 0.08);
        }

        .nav-group-title .nav-group-label {
            display: flex;
            align-items: center;
        }

        .nav-group-title .nav-group-label i:first-child {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            font-size: 0.9375rem;
            opacity: 0.8;
        }

        .nav-item.has-submenu .nav-group-title:hover .nav-group-label i:first-child {
            opacity: 1;
            color: #34d399;
        }

        .nav-group-title .nav-group-chevron {
            margin-left: auto;
            font-size: 0.6875rem;
            transition: transform 0.25s ease;
            opacity: 0.5;
        }

        .nav-item.has-submenu.expanded .nav-group-chevron {
            transform: rotate(90deg);
        }

        /* Submenu styling */
        .submenu {
            margin-left: 1rem;
            margin-top: 0.25rem;
            border-left: 1px solid rgba(154, 179, 163, 0.35);
            padding-left: 0.75rem;
            overflow: hidden;
            max-height: 500px;
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }

        .nav-item.has-submenu .submenu.collapsed {
            max-height: 0;
            margin-top: 0;
            opacity: 0;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.8125rem;
            margin: 0.1rem 0;
            position: relative;
        }

        .submenu-item:hover {
            background: rgba(5, 150, 105, 0.08);
            color: var(--primary);
            transform: translateX(3px);
        }

        .submenu-item.active {
            background: rgba(52, 211, 153, 0.12);
            color: var(--primary);
            font-weight: 600;
        }

        .submenu-item i {
            margin-right: 0.625rem;
            width: 1rem;
            text-align: center;
            font-size: 0.8125rem;
            opacity: 0.8;
        }

        .submenu-item:hover i,
        .submenu-item.active i {
            opacity: 1;
            color: #34d399;
        }

        /* Sidebar scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(154, 179, 163, 0.55);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 124, 114, 0.55);
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

        /* Main layout - padding creates gap around sidebar & content. Only content scrolls, sidebar stays fixed. */
        .main-wrapper {
            display: flex;
            height: 100vh;
            min-height: 100vh;
            padding: var(--layout-gap);
            box-sizing: border-box;
            background: #EEF2EF;
            overflow: hidden;
        }

        .content-wrapper {
            flex: 1;
            min-width: 0;
            min-height: 0;
            background: #FFFFFF;
            margin-left: calc(var(--sidebar-width) + var(--layout-gap));
            margin-top: 0;
            margin-right: 0;
            margin-bottom: 0;
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

        /* Content section - balanced spacing, grid-friendly. This is the only scrollable area so sidebar stays fixed. */
        .content-area {
            flex: 1;
            min-width: 0;
            min-height: 0;
            padding: var(--spacing-32);
            background: #FFFFFF;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Responsive typography in page content */
        .page-content h1 {
            font-size: clamp(24px, 4vw, 36px);
            font-weight: 700;
        }
        .page-content h2 {
            font-size: clamp(20px, 3vw, 28px);
            font-weight: 600;
        }
        .page-content p {
            font-size: clamp(14px, 2vw, 16px);
        }

        /* Responsive table wrapper - horizontal scroll on small screens */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }
        .table-responsive table {
            min-width: 280px;
        }

        /* Dashboard / card grids - flexible, responsive */
        .dashboard-cards,
        .page-content .grid[class*="grid-cols"] {
            display: grid;
            gap: var(--spacing-24);
        }
        .dashboard-cards {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: var(--text-on-dark);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
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
            background: #ffffff;
            border-bottom: 1px solid rgba(154,179,163,0.2);
            padding: 0.625rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 54px;
            flex-shrink: 0;
            box-shadow: 0 1px 0 rgba(154,179,163,0.12);
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
            gap: 0;
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: 0.8125rem;
        }

        .breadcrumb-item {
            display: inline-flex;
            align-items: center;
            color: #9AB3A3;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: '›';
            margin: 0 0.4rem;
            color: #c5d5cc;
            font-weight: 400;
            font-size: 0.9375rem;
            line-height: 1;
        }

        /* disable Bootstrap's default separator */
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
            color: #1F2D24;
            font-weight: 600;
        }

        .breadcrumb-item.active a,
        .breadcrumb-item:last-child a {
            color: #1F2D24;
            pointer-events: none;
            font-weight: 600;
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
            ring: 2px;
            outline: 2px solid rgba(5, 150, 105, 0.25);
            outline-offset: 1px;
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
        
        /* Force hardware acceleration only on scrollable/main content - NOT on body/content-wrapper
           so that .sidebar position:fixed stays relative to the viewport when scrolling */
        .sidebar,
        .content-area {
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

        /* Desktop Sidebar Styles - Simple Fixed Layout (sidebar stays fixed while content scrolls) */
        @media (min-width: 1024px) {
            .sidebar {
                position: fixed !important;
                left: var(--layout-gap) !important;
                top: var(--layout-gap) !important;
                bottom: var(--layout-gap) !important;
                width: var(--sidebar-width);
                height: calc(100vh - 2 * var(--layout-gap));
                min-height: 0;
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
                background: rgba(5, 150, 105, 0.08);
            }
            
        .nav-link.active {
            background: rgba(52, 211, 153, 0.15);
            color: var(--primary);
            box-shadow: inset 3px 0 0 #34d399;
        }
    }

        /* ========== Comprehensive responsive breakpoints ========== */

        /* Laptop (1024px) */
        @media (max-width: 1024px) {
            .content-area {
                padding: var(--spacing-24);
            }
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            .top-header {
                padding: var(--spacing-8) var(--spacing-16);
                min-height: 52px;
            }
        }

        /* Tablet (768px) */
        @media (max-width: 768px) {
            .content-area {
                padding: var(--spacing-16);
            }
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-16);
            }
            .header-actions {
                width: 100%;
                justify-content: flex-end;
                flex-wrap: wrap;
            }
            .dashboard-cards {
                grid-template-columns: 1fr;
                gap: var(--spacing-16);
            }
            .top-header {
                padding: var(--spacing-8) var(--spacing-16);
                min-height: 48px;
            }
            .breadcrumb {
                font-size: 0.8125rem;
            }
            .user-profile .user-email {
                display: none;
            }
            .user-profile {
                padding: var(--spacing-8);
            }
            .btn {
                padding: var(--spacing-8) var(--spacing-16);
                font-size: 0.875rem;
            }
            .form-control,
            .form-select,
            .form-input {
                width: 100%;
                max-width: 100%;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-left: calc(-1 * var(--spacing-16));
                margin-right: calc(-1 * var(--spacing-16));
                padding-left: var(--spacing-16);
                padding-right: var(--spacing-16);
            }
            .table-responsive table {
                min-width: 600px;
            }
        }

        /* Mobile (480px) */
        @media (max-width: 480px) {
            :root {
                --layout-gap: 8px;
            }
            .content-area {
                padding: var(--spacing-8);
            }
            .top-header {
                padding: var(--spacing-8);
                min-height: 44px;
            }
            .breadcrumb-section {
                gap: var(--spacing-8);
            }
            .header-actions {
                gap: var(--spacing-8);
            }
            .user-info .user-name {
                font-size: 0.75rem;
            }
            .profile-pic {
                width: 1.75rem;
                height: 1.75rem;
            }
            .dashboard-cards {
                gap: var(--spacing-8);
            }
            .btn {
                padding: var(--spacing-8) var(--spacing-16);
                min-height: 44px;
                font-size: 0.875rem;
            }
            .form-group,
            .page-content form > div[class*="grid"] > * {
                width: 100%;
            }
            .page-content form .grid {
                grid-template-columns: 1fr;
            }
            .table-responsive {
                margin-left: calc(-1 * var(--spacing-8));
                margin-right: calc(-1 * var(--spacing-8));
                padding-left: var(--spacing-8);
                padding-right: var(--spacing-8);
            }
        }

        /* Desktop (>=1200px) - ensure card grid 4 columns where applicable */
        @media (min-width: 1200px) {
            .dashboard-cards {
                grid-template-columns: repeat(4, 1fr);
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

        /* ======= Design Upgrade v2: tables, card-headers, buttons, badges ======= */

        /* ── Card: universal pattern ─────────────────────────────────────── */
        /* Every page uses .rounded-2xl.border.bg-white — this targets their inner headers */
        .rounded-2xl > .border-b:first-child,
        .rounded-2xl > div.border-b:first-child {
            background: #f8faf9 !important;
            padding: 0.875rem 1.25rem !important;
        }

        /* Card header icon boxes — sharper and more colourful */
        .rounded-2xl .w-8.h-8.rounded-lg {
            border-radius: 0.5rem !important;
        }
        .rounded-2xl .bg-emerald-600.rounded-lg { background: linear-gradient(135deg,#059669,#047857) !important; box-shadow: 0 2px 6px rgba(5,150,105,0.3); }
        .rounded-2xl .bg-blue-600.rounded-lg    { background: linear-gradient(135deg,#2563eb,#1d4ed8) !important; box-shadow: 0 2px 6px rgba(37,99,235,0.25); }
        .rounded-2xl .bg-amber-500.rounded-lg   { background: linear-gradient(135deg,#f59e0b,#d97706) !important; box-shadow: 0 2px 6px rgba(245,158,11,0.3); }

        /* ── Table: zebra + sticky thead ─────────────────────────────────── */
        table thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }
        table thead th {
            background: #f4f7f5 !important;
            font-size: 0.6875rem !important;
            letter-spacing: 0.06em !important;
            color: #5a7063 !important;
            font-weight: 700 !important;
            padding: 0.625rem 0.875rem !important;
            border-bottom: 1px solid rgba(154,179,163,0.25) !important;
            white-space: nowrap;
        }
        table tbody tr:nth-child(even) {
            background-color: #fafcfb;
        }
        table tbody tr:hover {
            background-color: rgba(52,211,153,0.05) !important;
        }
        table tbody td {
            padding: 0.625rem 0.875rem !important;
            font-size: 0.8125rem !important;
            color: #1F2D24;
            border-bottom: 1px solid rgba(154,179,163,0.12) !important;
            vertical-align: middle !important;
        }
        table tbody tr:last-child td { border-bottom: none !important; }

        /* ── Primary action button ────────────────────────────────────────── */
        a[style*="primary-gradient"],
        button[style*="primary-gradient"] {
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.01em;
            transition: filter 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease !important;
        }
        a[style*="primary-gradient"]:hover,
        button[style*="primary-gradient"]:hover {
            filter: brightness(1.06);
            transform: translateY(-1px);
        }

        /* ── Outline / secondary buttons ─────────────────────────────────── */
        a.inline-flex.border.border-gray-300,
        button.inline-flex.border.border-gray-300 {
            border-color: rgba(154,179,163,0.5) !important;
            color: #374151 !important;
            font-size: 0.8125rem !important;
            padding: 0.5rem 0.875rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.15s ease !important;
        }
        a.inline-flex.border.border-gray-300:hover,
        button.inline-flex.border.border-gray-300:hover {
            background: #f6f9f7 !important;
            border-color: rgba(5,150,105,0.4) !important;
            color: #059669 !important;
        }

        /* ── Status badges ────────────────────────────────────────────────── */
        .inline-flex.rounded-full.font-semibold,
        .inline-flex.rounded-full.font-medium {
            letter-spacing: 0.02em;
        }

        /* Carnet badges — ensure dot style */
        .bg-green-100.text-green-800  { background: #dcfce7 !important; color: #166534 !important; }
        .bg-amber-100.text-amber-800  { background: #fef3c7 !important; color: #92400e !important; }
        .bg-red-100.text-red-800      { background: #fee2e2 !important; color: #991b1b !important; }
        .bg-gray-100.text-gray-800    { background: #f3f4f6 !important; color: #374151 !important; }

        /* ── Pagination ───────────────────────────────────────────────────── */
        nav[role="navigation"] span[aria-current="page"] > span {
            background: linear-gradient(135deg,#059669,#047857);
            color: #fff;
            border-color: #059669;
            border-radius: 0.375rem;
        }
        nav[role="navigation"] a {
            border-radius: 0.375rem !important;
            transition: all 0.15s ease !important;
        }
        nav[role="navigation"] a:hover {
            border-color: #059669 !important;
            color: #059669 !important;
        }

        /* ── Divider between sections ─────────────────────────────────────── */
        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(154,179,163,0.35), transparent);
            border: none;
            margin: 1.5rem 0;
        }

        /* ── Stat card micro-detail ───────────────────────────────────────── */
        .stat-card {
            border-left: 3px solid transparent;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease !important;
        }
        .stat-card:hover {
            border-left-color: #059669;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 24px rgba(5,150,105,0.1) !important;
        }

        /* ── Dashboard "Voir tout" link ───────────────────────────────────── */
        a.text-emerald-600 { transition: gap 0.15s ease; }
        a.text-emerald-600:hover { text-decoration: underline; }

        /* ── Empty state ─────────────────────────────────────────────────── */
        .empty-state-icon {
            background: #f0fdf7;
            border: 1px solid rgba(5,150,105,0.15);
            border-radius: 50%;
            width: 3.5rem;
            height: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            color: #059669;
            font-size: 1.25rem;
        }

        /* ── Tab nav pills ───────────────────────────────────────────────── */
        nav.flex button.border-b-2 {
            transition: color 0.15s ease, border-color 0.15s ease !important;
        }

        /* ── Universal table action button ──────────────────────────────── */
        .tbl-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.875rem;
            height: 1.875rem;
            border-radius: 0.4rem;
            font-size: 0.6875rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
            background: none;
            border: 1px solid transparent;
        }
        .tbl-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .tbl-action i { pointer-events: none; }
        td .flex.items-center.justify-center.gap-0\.5,
        td .inline-flex.items-center.justify-center.gap-1 { gap: 0.3rem !important; }

        /* ── Form sub-section blocks (articles show/create) ──────────────── */
        .section-header {
            background: #f8faf9 !important;
            border-bottom: 1px solid rgba(154,179,163,0.2) !important;
        }

        /* ── Per-page selector ───────────────────────────────────────────── */
        select#perPageSelect {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%239AB3A3'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            padding-right: 1.5rem !important;
        }

        /* ── Exploitants / entity-data header bar ────────────────────────── */
        .rounded-2xl > .border-b.flex {
            background: #fafcfb;
        }

        /* ── Dashboard action cards ──────────────────────────────────────── */
        a.rounded-xl.border.bg-white {
            transition: all 0.2s ease !important;
        }
        a.rounded-xl.border.bg-white:hover {
            border-color: rgba(5,150,105,0.3) !important;
        }

        /* ── Exploitants pagination footer ───────────────────────────────── */
        .bg-gray-50.px-4.py-2.border-t {
            background: #fafcfb !important;
            border-color: rgba(154,179,163,0.2) !important;
        }

        /* ── Cessions tab active ─────────────────────────────────────────── */
        .border-b-2.border-emerald-600 { font-weight: 600; }

        /* ======= Design Upgrade: stat cards, badges, card headers ======= */

        /* Richer stat card icon containers */
        .stat-card .w-12,
        .stat-card [class*="w-12"] {
            transition: transform 0.2s ease;
        }
        .stat-card:hover .w-12,
        .stat-card:hover [class*="w-12"] {
            transform: scale(1.08);
        }

        /* Card section header accent */
        .rounded-2xl > .border-b,
        .rounded-2xl > div > .border-b {
            background: #f9fbfa;
        }

        /* Badge polish */
        .badge, [class*="badge"] {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        /* Pill status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .status-badge::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.7;
        }

        /* Content area background */
        .content-area {
            background: #ffffff;
        }

        /* Action icon buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            transition: all 0.15s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.12);
        }

        /* Sidebar footer separator */
        .sidebar .nav-section-label[data-section="general"] {
            border-top: 1px solid rgba(154, 179, 163, 0.25);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        /* Page header component polish */
        .page-header-icon {
            background: linear-gradient(135deg, rgba(52,211,153,0.15), rgba(5,150,105,0.12));
            border: 1px solid rgba(5,150,105,0.2);
            color: var(--primary);
        }

        /* Form controls refinement */
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.15);
        }

        /* Rounded table wrapper */
        .table-card {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(154,179,163,0.3);
            box-shadow: var(--shadow-card);
        }

        /* Smooth page entry animation */
        .page-content > * {
            animation: pageFadeIn 0.3s ease-out both;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
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
                    <i class="fas fa-tree"></i>
                </div>
                <div>
                    <h1>DEFATP</h1>
                    <p style="display:block;font-size:0.6875rem;color:rgba(160,210,185,0.55);margin:0;font-weight:400;line-height:1.3;">Gestion Forestière</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label">Menu</div>
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        Dashboard
                    </a>
                </div>

                <div class="nav-item has-submenu {{ request()->routeIs('cessions.*') || request()->routeIs('exploitants.*') ? 'expanded' : '' }}" id="nav-exploitation">
                    <button type="button" class="nav-group-title sidebar-item" aria-expanded="{{ request()->routeIs('cessions.*') || request()->routeIs('exploitants.*') ? 'true' : 'false' }}" aria-controls="submenu-exploitation" id="toggle-exploitation">
                        <span class="nav-group-label">
                            <i class="fas fa-folder"></i>
                            Exploitation
                        </span>
                        <i class="fas fa-chevron-right nav-group-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="submenu {{ request()->routeIs('cessions.*') || request()->routeIs('exploitants.*') || request()->routeIs('carnets.*') ? '' : 'collapsed' }}" id="submenu-exploitation" role="region" aria-label="Sous-menu Exploitation">
                        <a href="{{ route('cessions.index') }}" class="submenu-item sidebar-item {{ request()->routeIs('cessions.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i>
                            Cessions
                        </a>
                        <a href="{{ route('carnets.index') }}" class="submenu-item sidebar-item {{ request()->routeIs('carnets.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            Carnets
                        </a>
                        <a href="{{ route('exploitants.index') }}" class="submenu-item sidebar-item {{ request()->routeIs('exploitants.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i>
                            Exploitant forestier
                        </a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="{{ route('entity-data.index') }}" class="nav-link sidebar-item {{ request()->routeIs('entity-data.*') || request()->routeIs('essences.*') || request()->routeIs('forets.*') || request()->routeIs('situations.*') || request()->routeIs('natures.*') || request()->routeIs('vocations.*') || request()->routeIs('coperatives.*') || request()->routeIs('products.*') || request()->routeIs('prestations.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        Données des Entités
                    </a>
                </div>

                <div class="nav-section-label" data-section="general">Général</div>
                <div class="nav-item">
                    <a href="{{ route('auth.profile') }}" class="nav-link sidebar-item {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i>
                        Paramètres
                    </a>
                </div>
                @can('view activity logs')
                <div class="nav-item">
                    <a href="{{ route('activity-logs.index') }}" class="nav-link sidebar-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        Journal d'activité
                    </a>
                </div>
                @endcan
            </nav>

            <!-- Sidebar footer: user info + logout -->
            <div class="sidebar-footer" style="position:sticky; bottom:0; background:#fff; border-top:1px solid rgba(154,179,163,0.25); padding:0.875rem 1.25rem; margin-top:auto;">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=059669&color=fff&bold=true&size=64"
                         alt="" class="w-8 h-8 rounded-full flex-shrink-0" style="outline: 2px solid rgba(5,150,105,0.2); outline-offset: 1px;">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name ?? 'Utilisateur' }}</p>
                        <p class="text-xs text-gray-400 truncate" style="font-size:0.6875rem;">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit"
                                title="Se déconnecter"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                style="border: none; cursor: pointer; background: transparent;">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
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
                    <x-notifications-dropdown />
                    @endauth

                    <!-- Divider -->
                    <div style="width:1px;height:1.5rem;background:rgba(154,179,163,0.3);flex-shrink:0;"></div>

                    <!-- User Profile Dropdown -->
                    @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                                class="user-profile focus:outline-none"
                                :aria-expanded="open"
                                aria-haspopup="true">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=059669&color=fff&bold=true&size=64"
                                 alt="Photo de profil" class="profile-pic" width="32" height="32">
                            <div class="user-info">
                                <div class="user-name">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                                <div class="user-email">{{ auth()->user()->email ?? '' }}</div>
                            </div>
                            <i class="fas fa-chevron-down transition-transform duration-200"
                               :class="{ 'rotate-180': open }"
                               style="font-size:0.5rem;color:#9AB3A3;margin-left:0.25rem;flex-shrink:0;"></i>
                        </button>

                        <!-- Dropdown panel -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             class="absolute right-0 mt-2 w-56 rounded-xl bg-white border shadow-lg z-50 overflow-hidden"
                             style="border-color: rgba(154,179,163,0.4); box-shadow: 0 12px 28px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.04); display:none;">

                            <!-- User info header -->
                            <div class="px-4 py-3 border-b" style="border-color: rgba(154,179,163,0.2);">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ auth()->user()->name ?? 'Utilisateur' }}</p>
                                <p class="text-xs text-gray-400 truncate mt-0.5">{{ auth()->user()->email ?? '' }}</p>
                            </div>

                            <!-- Menu items -->
                            <div class="py-1">
                                <a href="{{ route('auth.profile') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                    <i class="fas fa-user-circle w-4 text-center text-gray-400"></i>
                                    Mon profil
                                </a>
                                @can('manage users')
                                <a href="{{ route('users.index') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                    <i class="fas fa-users-cog w-4 text-center text-gray-400"></i>
                                    Gestion utilisateurs
                                </a>
                                @endcan
                                <a href="{{ route('settings.index') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                    <i class="fas fa-sliders-h w-4 text-center text-gray-400"></i>
                                    Paramètres
                                </a>
                            </div>

                            <div class="border-t py-1" style="border-color: rgba(154,179,163,0.2);">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                        <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                        Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Page Content -->
            <main class="content-area" id="main-content">
                @if(session('success'))
                    <div class="flex items-center gap-3 mb-5 px-4 py-3.5 rounded-xl border"
                         style="background:#f0fdf9; border-color:#6ee7b7; border-left:3px solid #059669;">
                        <i class="fas fa-check-circle text-emerald-600 flex-shrink-0"></i>
                        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 mb-5 px-4 py-3.5 rounded-xl border"
                         style="background:#fef2f2; border-color:#fca5a5; border-left:3px solid #dc2626;">
                        <i class="fas fa-exclamation-circle text-red-600 flex-shrink-0"></i>
                        <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="flex items-center gap-3 mb-5 px-4 py-3.5 rounded-xl border"
                         style="background:#fffbeb; border-color:#fcd34d; border-left:3px solid #d97706;">
                        <i class="fas fa-exclamation-triangle text-amber-600 flex-shrink-0"></i>
                        <span class="text-sm font-medium text-amber-800">{{ session('warning') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="flex items-center gap-3 mb-5 px-4 py-3.5 rounded-xl border"
                         style="background:#eff6ff; border-color:#93c5fd; border-left:3px solid #2563eb;">
                        <i class="fas fa-info-circle text-blue-600 flex-shrink-0"></i>
                        <span class="text-sm font-medium text-blue-800">{{ session('info') }}</span>
                    </div>
                @endif

                <div class="page-content">
                    @yield('content')
                </div>
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

    <!-- Anime.js (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>

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

        // Exploitation submenu collapse toggle
        document.getElementById('toggle-exploitation')?.addEventListener('click', function() {
            const navItem = document.getElementById('nav-exploitation');
            const submenu = document.getElementById('submenu-exploitation');
            if (!navItem || !submenu) return;
            navItem.classList.toggle('expanded');
            submenu.classList.toggle('collapsed');
            this.setAttribute('aria-expanded', submenu.classList.contains('collapsed') ? 'false' : 'true');
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });


        // Global UX Enhancement Functions
        window.UXUtils = {
            // Enhanced Toast notification system
            showToast: function(message, type = 'info', options = {}) {
                const container = document.getElementById('toastContainer');
                if (!container || !message) {
                    return null;
                }

                const toast = document.createElement('div');
                const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                
                const defaults = {
                    duration: 5000,
                    title: null,
                    closable: true,
                    position: 'top-right',
                    sound: false,
                    action: null,
                    dedupe: true,
                    dedupeWindow: 4000,
                    maxVisible: 3
                };
                
                const config = { ...defaults, ...options };
                const escapeHtml = (value) => {
                    const temp = document.createElement('div');
                    temp.textContent = value == null ? '' : String(value);
                    return temp.innerHTML;
                };
                
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
                const signature = [type, title, message].join('|');
                const now = Date.now();

                this._toastHistory = this._toastHistory || new Map();

                const existingToast = Array.from(container.querySelectorAll('.toast')).find((item) => item.dataset.signature === signature);
                const lastShownAt = this._toastHistory.get(signature);

                if (config.dedupe && (existingToast || (lastShownAt && (now - lastShownAt) < config.dedupeWindow))) {
                    if (existingToast) {
                        if (existingToast._dismissTimer) {
                            clearTimeout(existingToast._dismissTimer);
                        }

                        if (config.duration > 0) {
                            existingToast._dismissTimer = setTimeout(() => {
                                this.closeToast(existingToast.id);
                            }, config.duration);
                        }
                    }

                    return existingToast ? existingToast.id : null;
                }

                while (config.maxVisible > 0 && container.children.length >= config.maxVisible) {
                    const oldestToast = container.firstElementChild;

                    if (!oldestToast) {
                        break;
                    }

                    if (oldestToast._dismissTimer) {
                        clearTimeout(oldestToast._dismissTimer);
                    }

                    oldestToast.remove();
                }

                this._toastHistory.set(signature, now);

                toast.className = `toast ${type}`;
                toast.id = toastId;
                toast.dataset.signature = signature;
                
                toast.innerHTML = `
                    <div class="toast-header">
                        <div class="toast-title">
                            <div class="toast-icon">
                                <i class="${icon}"></i>
                            </div>
                            ${escapeHtml(title)}
                        </div>
                        ${config.closable ? '<button class="toast-close" onclick="UXUtils.closeToast(\'' + toastId + '\')"><i class="fas fa-times"></i></button>' : ''}
                    </div>
                    <div class="toast-message">${escapeHtml(message)}</div>
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
                    toast._dismissTimer = setTimeout(() => {
                        this.closeToast(toastId);
                    }, config.duration);
                }
                
                return toastId;
            },

            // Close specific toast
            closeToast: function(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    if (toast._dismissTimer) {
                        clearTimeout(toast._dismissTimer);
                    }

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

            // Inline alerts manage their own lifecycle; avoid turning every page alert into a toast.

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
            UXUtils.showToast('Une erreur inattendue s\'est produite', 'error', {
                duration: 4000,
                dedupe: true,
                dedupeWindow: 5000,
                maxVisible: 1
            });
        });

        // Global unhandled promise rejection handling
        window.addEventListener('unhandledrejection', function(e) {
            console.error('Unhandled promise rejection:', e.reason);
            UXUtils.showToast('Une erreur réseau s\'est produite', 'error', {
                duration: 4000,
                dedupe: true,
                dedupeWindow: 5000,
                maxVisible: 1
            });
        });

        // Anime.js-powered micro-animations for a modern SaaS feel
        function initUiAnimations() {
            if (!window.anime) return;

            // 1) Page Load: fade + slide main content
            anime({
                targets: '.page-content',
                translateY: [30, 0],
                opacity: [0, 1],
                duration: 700,
                easing: 'easeOutExpo'
            });

            // 2) Dashboard / generic cards
            anime({
                targets: '.card',
                translateY: [40, 0],
                opacity: [0, 1],
                delay: anime.stagger(100),
                duration: 700,
                easing: 'easeOutExpo'
            });

            // 3) Table rows (CRUD listings)
            anime({
                targets: 'tbody tr',
                translateX: [-20, 0],
                opacity: [0, 1],
                delay: anime.stagger(50),
                duration: 500,
                easing: 'easeOutQuad'
            });

            // 4) Form groups on create/edit/show pages
            anime({
                targets: '.form-group',
                translateY: [20, 0],
                opacity: [0, 1],
                delay: anime.stagger(40),
                duration: 500,
                easing: 'easeOutQuad'
            });

            // 5) Sidebar items
            anime({
                targets: '.sidebar-item',
                translateX: [-15, 0],
                opacity: [0, 1],
                delay: anime.stagger(60),
                duration: 500,
                easing: 'easeOutQuad'
            });

            // 6) Button hover micro-interactions (scale on hover)
            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    anime.remove(btn);
                    anime({
                        targets: btn,
                        scale: 1.05,
                        duration: 200,
                        easing: 'easeOutQuad'
                    });
                });
                btn.addEventListener('mouseleave', () => {
                    anime.remove(btn);
                    anime({
                        targets: btn,
                        scale: 1,
                        duration: 200,
                        easing: 'easeOutQuad'
                    });
                });
            });

            // 6) Modals: subtle scale + fade on show (Bootstrap)
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('shown.bs.modal', () => {
                    const dialog = modal.querySelector('.modal-dialog');
                    if (!dialog) return;

                    anime.remove(dialog);
                    anime({
                        targets: dialog,
                        scale: [0.9, 1],
                        opacity: [0, 1],
                        duration: 300,
                        easing: 'easeOutExpo'
                    });
                });
            });

            // 7) Toasts / notifications slide-in
            const toastContainer = document.getElementById('toastContainer');
            if (toastContainer) {
                const observer = new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        mutation.addedNodes.forEach(node => {
                            if (node.nodeType !== 1) return;
                            if (node.classList.contains('toast') || node.classList.contains('notification')) {
                                anime.remove(node);
                                anime({
                                    targets: node,
                                    translateY: [-16, 0],
                                    opacity: [0, 1],
                                    duration: 400,
                                    easing: 'easeOutQuad'
                                });
                            }
                        });
                    });
                });

                observer.observe(toastContainer, { childList: true });
            }
        }

        // Ensure animations run whether DOMContentLoaded already fired or not
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initUiAnimations);
        } else {
            initUiAnimations();
        }
    </script>
</body>
</html> 
