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
            
            /* UX Enhancement Variables */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
            --border-radius-sm: 0.375rem;
            --border-radius-md: 0.5rem;
            --border-radius-lg: 0.75rem;
            --border-radius-xl: 1rem;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            line-height: 1.6;
            color: #374151;
        }

        /* Global UX Enhancements */
        * {
            box-sizing: border-box;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Focus management */
        *:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Skip to content link for accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--primary-color);
            color: white;
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
            border-left-color: var(--success-color);
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }

        .toast.error {
            border-left-color: var(--danger-color);
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        }

        .toast.warning {
            border-left-color: var(--warning-color);
            background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
        }

        .toast.info {
            border-left-color: var(--info-color);
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
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
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: var(--transition-fast);
            font-size: 1rem;
        }

        .toast-close:hover {
            color: #6b7280;
            background: rgba(0, 0, 0, 0.05);
        }

        .toast-message {
            font-size: 0.875rem;
            color: #6b7280;
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
            background: var(--success-color);
            color: white;
        }

        .toast.error .toast-icon {
            background: var(--danger-color);
            color: white;
        }

        .toast.warning .toast-icon {
            background: var(--warning-color);
            color: white;
        }

        .toast.info .toast-icon {
            background: var(--info-color);
            color: white;
        }

        /* Enhanced form styles */
        .form-control, .form-select {
            border-radius: var(--border-radius-md);
            border: 2px solid #e5e7eb;
            transition: var(--transition-normal);
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-control.is-valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
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
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
        }

        .btn-primary:hover:not(:disabled) {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.75rem 1.5rem;
        }

        .btn-outline:hover:not(:disabled) {
            background: var(--primary-color);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
        }

        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Enhanced card styles */
        .card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid #e5e7eb;
            transition: var(--transition-normal);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Enhanced table styles */
        .table {
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table th {
            background: #f9fafb;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:hover {
            background-color: rgba(5, 150, 105, 0.05);
        }

        /* Enhanced modal styles */
        .modal-content {
            border-radius: var(--border-radius-xl);
            border: none;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            border-bottom: none;
            border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;
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

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            width: 280px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .sidebar-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .sidebar-header p {
            font-size: 0.875rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
            position: relative;
            z-index: 1;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            background: rgba(255, 255, 255, 0.5);
        }

        .nav-item {
            margin: 0.5rem 1rem;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
            background: transparent;
            border: 1px solid transparent;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(5, 150, 105, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 12px;
        }

        .nav-link:hover {
            color: #059669;
            background: rgba(5, 150, 105, 0.08);
            transform: translateX(6px);
            border-color: rgba(5, 150, 105, 0.2);
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.15);
        }

        .nav-link:hover::before {
            opacity: 1;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
            transform: translateX(6px);
            border-color: #047857;
        }

        .nav-link.active::before {
            opacity: 0;
        }

        .nav-link i {
            margin-right: 1rem;
            width: 1.5rem;
            text-align: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        .nav-link.active i {
            transform: scale(1.1);
        }

        /* Submenu styling */
        .submenu {
            margin-left: 1rem;
            margin-top: 0.5rem;
            border-left: 2px solid rgba(5, 150, 105, 0.2);
            padding-left: 1rem;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            margin: 0.25rem 0;
            position: relative;
        }

        .submenu-item:hover {
            background: rgba(5, 150, 105, 0.08);
            color: #059669;
            transform: translateX(4px);
        }

        .submenu-item.active {
            background: rgba(5, 150, 105, 0.15);
            color: #059669;
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
            background: rgba(5, 150, 105, 0.3);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(5, 150, 105, 0.5);
        }

        /* Logo styling */
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            font-size: 2rem;
            color: #10b981;
        }

        /* Sidebar toggle button */
        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: -2.5rem;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }

        /* Responsive sidebar behavior */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content-wrapper {
                margin-left: 1rem;
            }

            .nav-item {
                margin: 0.25rem 0.75rem;
            }

            .nav-link {
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (min-width: 1025px) {
            .sidebar {
                transform: translateX(0);
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

        /* Main Content */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            margin: 1rem;
            margin-left: calc(16rem + 1rem); /* 16rem (w-64) + 1rem margin */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .content-area {
            padding: 2rem;
            min-height: calc(100vh - 2rem);
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
            color: var(--secondary-color);
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Card Styles */
        .glassmorphism-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glassmorphism-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
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
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            background: white;
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
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                height: 100vh;
                z-index: 50;
                transition: left 0.3s ease;
            }

            .sidebar.open {
                left: 0;
            }

            .content-wrapper {
                margin: 0.5rem;
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
            border-color: #10b981;
            background-color: #f0fdf4;
        }

        .notification.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .notification.warning {
            border-color: #f59e0b;
            background-color: #fffbeb;
        }

        .notification.info {
            border-color: #0891b2;
            background-color: #f0fdfa;
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
            border-color: #3b82f6;
            background-color: #3b82f6;
            color: white;
        }

        .wizard-step-indicator.completed {
            border-color: #10b981;
            background-color: #10b981;
            color: white;
        }

        .wizard-step-indicator.pending {
            border-color: #d1d5db;
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .wizard-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
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
            color: white;
            background-color: #1f2937;
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
            border-top-color: #1f2937;
        }

        .tooltip-container:hover .tooltip {
            opacity: 1;
        }

        /* Help System */
        .help-icon {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            color: #3b82f6;
            cursor: help;
            margin-left: 0.25rem;
        }

        .field-help {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
            font-style: italic;
        }

        /* Welcome Guide */
        .welcome-guide {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Top Header */
        .top-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: #6b7280;
        }

        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            width: 300px;
            background: rgba(255, 255, 255, 0.9);
        }

        .filter-btn {
            padding: 0.75rem 1rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-icon {
            position: relative;
            padding: 0.5rem;
            cursor: pointer;
        }

        .notification-dot {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            width: 0.5rem;
            height: 0.5rem;
            background: #ef4444;
            border-radius: 50%;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        .profile-pic {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
        }

        .user-email {
            font-size: 0.875rem;
            color: #6b7280;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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

        .category-icon.design { background: #fef3c7; color: #7c2d12; }
        .category-icon.documents { background: #d1fae5; color: #059669; }
        .category-icon.music { background: #bbf7d0; color: #16a34a; }
        .category-icon.images { background: #fef3c7; color: #ca8a04; }

        .category-info h3 {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
        }

        .category-info p {
            color: #6b7280;
            margin: 0 0 0.25rem 0;
        }

        .category-size {
            font-size: 0.875rem;
            color: #9ca3af;
        }

        .category-options {
            margin-left: auto;
            color: #9ca3af;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .quick-access-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
            color: #1f2937;
            margin: 0 0 0.25rem 0;
        }

        .quick-access-info p {
            color: #6b7280;
            margin: 0;
            font-size: 0.875rem;
        }

        .quick-access-btn {
            padding: 0.5rem 1rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .quick-access-btn:hover {
            background: #2563eb;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .recent-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }

        .recent-info h4 {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
        }

        .recent-info p {
            color: #6b7280;
            margin: 0;
            font-size: 0.75rem;
        }

        .recent-size {
            margin-left: auto;
            font-size: 0.875rem;
            color: #6b7280;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-manager-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .file-manager-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem auto;
            color: #6b7280;
        }

        .file-manager-info h4 {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
        }

        .file-manager-info p {
            color: #6b7280;
            margin: 0;
            font-size: 0.75rem;
        }

        .open-file-manager-btn {
            width: 100%;
            padding: 0.75rem;
            background: #3b82f6;
            color: white;
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
            background: #2563eb;
            text-decoration: none;
            color: white;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
            overflow-x: auto;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .quick-create-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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

        .quick-create-icon.essence { background: #d1fae5; color: #059669; }
        .quick-create-icon.foret { background: #fef3c7; color: #7c2d12; }
        .quick-create-icon.localisation { background: #bbf7d0; color: #16a34a; }
        .quick-create-icon.situation { background: #fef3c7; color: #ca8a04; }
        .quick-create-icon.exploitant { background: #fce7f3; color: #dc2626; }
        .quick-create-icon.nature { background: #d1fae5; color: #0891b2; }

        .quick-create-card h4 {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }

        .quick-create-card p {
            color: #6b7280;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            overflow: hidden;
        }

        .entities-data-section .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
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
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s ease;
            background: transparent;
        }

        .entities-data-section .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background: rgba(5, 150, 105, 0.05);
            border: none;
        }

        .entities-data-section .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: rgba(5, 150, 105, 0.1);
            border: none;
            border-bottom: 3px solid var(--primary-color);
        }

        .entities-data-section .card-body {
            padding: 1.5rem;
        }

        .entities-data-section .tab-pane {
            padding: 0;
        }

        .entities-data-section .tab-pane h5 {
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Entity Data Cards Styling */
        .entities-data-section {
            margin-bottom: 2rem;
        }

        .entity-data-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .entity-data-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .entity-data-card .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 1.5rem;
        }

        .entity-data-card .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .entity-data-card .card-body {
            padding: 1.5rem;
        }

        .entity-data-card .table {
            margin-bottom: 0;
        }

        .entity-data-card .table th {
            background: rgba(255, 255, 255, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
            color: #374151;
        }

        .entity-data-card .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            vertical-align: middle;
        }

        .entity-data-card .table tbody tr:hover {
            background-color: rgba(5, 150, 105, 0.05);
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #6b7280;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
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

        .tip-icon.blue { background: #bbf7d0; color: #16a34a; }
        .tip-icon.green { background: #d1fae5; color: #059669; }
        .tip-icon.purple { background: #fef3c7; color: #7c2d12; }
        .tip-icon.orange { background: #fef3c7; color: #ca8a04; }

        .tip-card h3 {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }

        .tip-card p {
            color: #6b7280;
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
            backdrop-filter: blur(10px);
        }

        /* Enhanced Focus States */
        .focus-ring {
            outline: none;
            ring: 2px;
            ring-color: #3b82f6;
            ring-offset: 2px;
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
                position: fixed;
                top: 0;
                left: -280px;
                z-index: 50;
                height: 100vh;
                overflow-y: auto;
                transition: left 0.3s ease-in-out;
                width: 280px;
            }
            
            .sidebar.open {
                left: 0;
            }
            
            .content-wrapper {
                margin-left: 0 !important;
            }
        }

        /* Desktop Sidebar Styles - Simple Fixed Layout */
        @media (min-width: 1024px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                width: 280px;
                height: 100vh;
                overflow-y: auto;
                z-index: 30;
            }
            
            .sidebar-backdrop {
                display: none;
            }
            
            .content-wrapper {
                margin-left: 280px;
                min-height: 100vh;
            }
        }

        /* Mobile Header Improvements */
        @media (max-width: 768px) {
            .content-wrapper {
                padding-top: 0;
            }
            
            header {
                position: sticky;
                top: 0;
                z-index: 30;
                background: white;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .content-area {
                padding: 1rem;
            }
        }

        /* Tablet Header Improvements */
        @media (min-width: 769px) and (max-width: 1023px) {
            .content-wrapper {
                padding-top: 0;
            }
            
            header {
                position: sticky;
                top: 0;
                z-index: 30;
                background: white;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .content-area {
                padding: 1.5rem;
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
                background: var(--primary-color);
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
    
    <!-- Sidebar Backdrop Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>
    
    <div class="main-wrapper">
        <!-- Left Sidebar -->
        <aside class="sidebar w-64 min-h-screen" id="sidebar">
            <div class="sidebar-header">
                <div class="flex items-center justify-center">
                    <i class="fas fa-tree text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold">DEFATP</h1>
                </div>
                <p class="text-sm opacity-90 mt-2">Gestion Forestière</p>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Tableau de Bord
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Rapports
                    </a>
                </div>
                
                
                <div class="nav-item">
                    <a href="{{ route('articles.index') }}" class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        Exploitation régulière
                    </a>
                </div>

                <div class="nav-item {{ request()->routeIs('contracts.*') ? 'has-submenu' : '' }}">
                    <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i>
                        Contrat de Partenariat
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('exploitants.index') }}" class="nav-link {{ request()->routeIs('exploitants.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        Exploitant Forêstier
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('coperatives.index') }}" class="nav-link {{ request()->routeIs('coperatives.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        Coopératives
                    </a>
                </div>

                
               
                
                <!-- <div class="nav-item">
                    <a href="{{ route('odfs.index') }}" class="nav-link {{ request()->routeIs('odfs.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Organisation Forestière
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('pdfcs.index') }}" class="nav-link {{ request()->routeIs('pdfcs.*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram"></i>
                        Plan DFC
                    </a>
                </div> -->
               
                <div class="nav-item">
                    <a href="{{ route('entity-data.index') }}" class="nav-link {{ request()->routeIs('entity-data.*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        Données des Entités
                    </a>
                </div>
            

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="content-wrapper flex-1">
            <!-- Top Header with Mobile Navigation -->
            <header class="bg-white border-b border-gray-200 px-3 sm:px-4 py-3 flex items-center justify-between">
                <div class="flex items-center min-w-0 flex-1">
                    <!-- Mobile menu button -->
                    <button class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 mr-2 sm:mr-3 flex-shrink-0" 
                            onclick="toggleSidebar()">
                        <i class="fas fa-bars text-lg sm:text-xl"></i>
                    </button>
                    
                    <!-- Navigation buttons (hidden on dashboard) -->
                    @if(!request()->routeIs('dashboard'))
                    <div class="flex items-center gap-1 sm:gap-2 mr-2 sm:mr-3 flex-shrink-0">
                        <button onclick="window.history.back(); return false;" 
                           class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-sm hover:shadow-md"
                           title="Retour en arrière">
                            <i class="fas fa-arrow-left text-sm sm:text-base"></i>
                        </button>
                        
                        <!-- <button onclick="window.history.forward(); return false;" 
                           class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-sm hover:shadow-md"
                           title="Avancer">
                            <i class="fas fa-arrow-right text-sm sm:text-base"></i>
                        </button>
                        
                        <button onclick="window.location.reload(); return false;" 
                           class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-sm hover:shadow-md"
                           title="Rafraîchir la page">
                            <i class="fas fa-sync-alt text-sm sm:text-base"></i>
                        </button> -->
                    </div>
                    @endif
                    
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 truncate">@yield('title', 'DEFATP')</h2>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-bell text-lg sm:text-xl"></i>
                        <span class="sr-only">Notifications</span>
                    </button>
                    
                    <!-- User menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1 sm:space-x-2 p-1 sm:p-2 rounded-md hover:bg-gray-100">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=8b5cf6&color=fff" 
                                 alt="Profile" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full">
                            <span class="hidden sm:block text-sm font-medium text-gray-900">
                                {{ auth()->user()->name ?? 'Utilisateur' }}
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden sm:block"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('auth.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>Mon Profil
                            </a>
                            
                            @can('users.view')
                            <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-users mr-2"></i>Utilisateurs
                            </a>
                            @endcan
                            
                            @can('activity-logs.view')
                            <a href="{{ route('activity-logs.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-history mr-2"></i>Journal d'Activités
                            </a>
                            @endcan
                            
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content-area" id="main-content">
                @if(session('success'))
                    <div class="alert alert-success mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
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

    <!-- Additional Scripts -->
    @stack('scripts')

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
                        <h3 style="margin-bottom: 1rem; color: #374151;">${config.title}</h3>
                        <p style="margin-bottom: 2rem; color: #6b7280; line-height: 1.5;">${message}</p>
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