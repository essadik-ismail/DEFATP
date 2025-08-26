<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SylvaNet - Gestion Forestière')</title>

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
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            /* background: linear-gradient(135deg, #059669 0%, #14532d 50%, #7c2d12 100%); */
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--dark-color);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(5, 150, 105, 0.1);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
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
    <div class="main-wrapper">
        <!-- Left Sidebar -->
        <aside class="sidebar w-64 min-h-screen" id="sidebar">
            <div class="sidebar-header">
                <div class="flex items-center justify-center">
                    <i class="fas fa-tree text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold">SylvaNet</h1>
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
                    <a href="{{ route('articles.index') }}" class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        Articles
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        Paramètres
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        Rapports
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('excel.index') }}" class="nav-link {{ request()->routeIs('excel.*') ? 'active' : '' }}">
                        <i class="fas fa-file-excel"></i>
                        Import/Export
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('auth.users.index') }}" class="nav-link {{ request()->routeIs('auth.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Utilisateurs
                    </a>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-4">
                    <div class="nav-item">
                        <a href="{{ route('auth.profile') }}" class="nav-link {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                            <i class="fas fa-user"></i>
                            Mon Profil
                        </a>
                    </div>

                    <div class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="nav-link w-full text-left bg-transparent border-0">
                                <i class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="content-wrapper flex-1">
            <!-- Top Header with Mobile Navigation -->
            <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 mr-3" 
                            onclick="document.getElementById('sidebar').classList.toggle('open')">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <h2 class="text-lg font-semibold text-gray-900">@yield('title', 'SylvaNet')</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="sr-only">Notifications</span>
                    </button>
                    
                    <!-- User menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-md hover:bg-gray-100">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=8b5cf6&color=fff" 
                                 alt="Profile" class="w-8 h-8 rounded-full">
                            <span class="hidden md:block text-sm font-medium text-gray-900">
                                {{ auth()->user()->name ?? 'Utilisateur' }}
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
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
            <main class="content-area">
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
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('.lg\\:hidden');
            
            if (window.innerWidth < 1024 && 
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('open');
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
    </script>
</body>
</html> 