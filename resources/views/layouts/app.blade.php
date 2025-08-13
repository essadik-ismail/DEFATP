<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.head')
    @include('partials.styles')
    
    <style>
        :root {
            /* Enhanced Material Design Colors */
            --mdc-theme-primary: #4a7c59;
            --mdc-theme-secondary: #f59e0b;
            --mdc-theme-background: #ffffff;
            --mdc-theme-surface: #ffffff;
            --mdc-theme-error: #dc2626;
            --mdc-theme-on-primary: #ffffff;
            --mdc-theme-on-secondary: #ffffff;
            --mdc-theme-on-surface: #1f2937;
            --mdc-theme-on-error: #ffffff;
            
            /* Enhanced Material Design Elevation */
            --mdc-elevation-1: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --mdc-elevation-2: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --mdc-elevation-4: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --mdc-elevation-8: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --mdc-elevation-16: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            
            /* Modern Color Palette */
            --primary-color: #4a7c59;
            --primary-dark: #2d5a27;
            --primary-light: #6b8e5a;
            --secondary-color: #f8fafc;
            --accent-color: #f59e0b;
            --accent-dark: #d97706;
            --success-color: #10b981;
            --success-dark: #059669;
            --warning-color: #f59e0b;
            --warning-dark: #d97706;
            --danger-color: #ef4444;
            --danger-dark: #dc2626;
            --info-color: #3b82f6;
            --info-dark: #2563eb;
            
            /* Text Colors */
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --text-light: #f9fafb;
            
            /* Background Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-dark: #1f2937;
            
            /* Border Colors */
            --border-color: #e5e7eb;
            --border-light: #f3f4f6;
            --border-dark: #d1d5db;
            
            /* Special Colors */
            --purple-color: #8b5cf6;
            --purple-dark: #7c3aed;
            --blue-color: #3b82f6;
            --blue-dark: #2563eb;
            --orange-color: #f97316;
            --orange-dark: #ea580c;
            --green-color: #10b981;
            --green-dark: #059669;
            --pink-color: #ec4899;
            --pink-dark: #db2777;
            
            /* Legacy Support */
            --anef-green: #4a7c59;
            --anef-orange: #f59e0b;
            --anef-dark-green: #2d5a27;
            --google-gray: #6b7280;
            --google-light-gray: #f8fafc;
            --google-border: #e5e7eb;
            --google-text: #1f2937;
            --google-blue: #3b82f6;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            
            /* Gradients - No Blue Colors */
            --gradient-primary: linear-gradient(135deg, #2d5a27 0%, #4a7c59 50%, #e67e22 100%);
            --gradient-secondary: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --gradient-purple: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        }

        /* Dark Mode Variables */
        body.dark-mode {
            --mdc-theme-background: #1a1a1a;
            --mdc-theme-surface: #2d2d2d;
            --mdc-theme-on-surface: #ffffff;
            --text-primary: #ffffff;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --bg-tertiary: #374151;
            --border-color: #4b5563;
            --border-light: #374151;
            --border-dark: #6b7280;
            --google-gray: #9ca3af;
            --google-light-gray: #374151;
            --google-border: #4b5563;
            --google-text: #ffffff;
        }

        /* Dark Mode Body Background */
        body.dark-mode {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #374151 100%);
        }

        /* Dark Mode Dashboard Container */
        body.dark-mode .dashboard-container::before {
            background: 
                radial-gradient(circle at 20% 80%, rgba(45, 90, 39, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(74, 124, 89, 0.1) 0%, transparent 50%);
        }

        /* Dark Mode Sidebar */
        body.dark-mode .sidebar {
            background: linear-gradient(180deg, rgba(45, 45, 45, 0.95) 0%, rgba(55, 65, 81, 0.95) 100%);
            border-right: 1px solid rgba(75, 85, 99, 0.3);
        }

        body.dark-mode .sidebar::before {
            background: 
                radial-gradient(circle at 20% 20%, rgba(45, 90, 39, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
        }

        /* Dark Mode Logo */
        body.dark-mode .logo {
            color: var(--text-primary);
            border-bottom: 1px solid rgba(75, 85, 99, 0.3);
        }

        /* Dark Mode Navigation */
        body.dark-mode .nav-link {
            color: var(--text-primary);
            background: rgba(45, 45, 45, 0.1);
        }

        body.dark-mode .nav-link:hover {
            background: linear-gradient(135deg, rgba(74, 124, 89, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
        }

        body.dark-mode .nav-link.active {
            background: linear-gradient(135deg, rgba(74, 124, 89, 0.3) 0%, rgba(245, 158, 11, 0.3) 100%);
        }

        body.dark-mode .submenu {
            background: linear-gradient(135deg, rgba(55, 65, 81, 0.9) 0%, rgba(45, 45, 45, 0.95) 100%);
        }

        body.dark-mode .submenu-item {
            background: rgba(45, 45, 45, 0.1);
        }

        body.dark-mode .submenu-item:hover {
            background: linear-gradient(135deg, rgba(74, 124, 89, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
        }

        /* Dark Mode Top Bar */
        body.dark-mode .top-bar {
            background: linear-gradient(135deg, rgba(45, 45, 45, 0.98) 0%, rgba(55, 65, 81, 0.98) 100%);
            border-bottom: 1px solid rgba(75, 85, 99, 0.3);
            left: 280px;
            right: 0;
        }

        body.dark-mode .sidebar.collapsed ~ .content-wrapper .top-bar {
            left: 70px;
        }

        /* Dark Mode Content Header */
        body.dark-mode .content-header {
            background: linear-gradient(135deg, rgba(45, 45, 45, 0.95) 0%, rgba(55, 65, 81, 0.95) 100%);
            border-bottom: 1px solid rgba(75, 85, 99, 0.3);
        }

        /* Dark Mode Top Bar Buttons */
        body.dark-mode .top-bar-btn {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(75, 85, 99, 0.3);
            color: var(--text-primary);
        }

        body.dark-mode .top-bar-btn:hover {
            background: rgba(55, 65, 81, 0.95);
            border-color: var(--primary-color);
        }

        /* Dark Mode Dropdowns */
        body.dark-mode .notification-panel,
        body.dark-mode .profile-panel {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
        }

        body.dark-mode .notification-header {
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-secondary);
        }

        body.dark-mode .notification-item {
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .notification-item:hover {
            background: var(--bg-secondary);
        }

        body.dark-mode .notification-footer {
            border-top: 1px solid var(--border-color);
        }

        body.dark-mode .profile-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .profile-menu-item:hover {
            background: var(--bg-secondary);
        }

        body.dark-mode .profile-menu-divider {
            background: var(--border-color);
        }

        /* Dark Mode Breadcrumbs */
        body.dark-mode .breadcrumb-item {
            color: var(--text-primary);
        }

        body.dark-mode .breadcrumb-separator {
            color: var(--text-muted);
        }

        /* Dark Mode Main Content */
        body.dark-mode .content-wrapper::before {
            background: 
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
        }

        /* Dark Mode Cards and Components */
        body.dark-mode .card,
        body.dark-mode .dashboard-card,
        body.dark-mode .settings-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        body.dark-mode .card-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .card-title {
            color: var(--text-primary);
        }

        body.dark-mode .card-subtitle {
            color: var(--text-secondary);
        }

        /* Dark Mode Tables */
        body.dark-mode .table,
        body.dark-mode .data-table {
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        body.dark-mode .table th,
        body.dark-mode .data-table th {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .table td,
        body.dark-mode .data-table td {
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .table-row:hover {
            background: var(--bg-secondary);
        }

        /* Dark Mode Forms */
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 124, 89, 0.25);
        }

        body.dark-mode .form-label {
            color: var(--text-primary);
        }

        /* Dark Mode Buttons */
        body.dark-mode .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        body.dark-mode .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        body.dark-mode .btn-outline {
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        body.dark-mode .btn-outline:hover {
            background: var(--bg-secondary);
            border-color: var(--primary-color);
        }

        /* Dark Mode Alerts */
        body.dark-mode .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }

        body.dark-mode .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid var(--danger-color);
            color: var(--danger-color);
        }

        /* Dark Mode Stats Grid */
        body.dark-mode .stats-grid {
            background: var(--bg-primary);
        }

        body.dark-mode .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }

        body.dark-mode .stat-number {
            color: var(--text-primary);
        }

        body.dark-mode .stat-label {
            color: var(--text-secondary);
        }

        /* Dark Mode Activity Items */
        body.dark-mode .activity-item {
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .activity-title {
            color: var(--text-primary);
        }

        body.dark-mode .activity-description {
            color: var(--text-secondary);
        }

        body.dark-mode .activity-time {
            color: var(--text-muted);
        }

        /* Dark Mode Empty States */
        body.dark-mode .empty-state {
            color: var(--text-secondary);
        }

        body.dark-mode .empty-state h4 {
            color: var(--text-primary);
        }

        /* Dark Mode Quick Actions */
        body.dark-mode .quick-action-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        body.dark-mode .quick-action-item:hover {
            background: var(--bg-tertiary);
            border-color: var(--primary-color);
        }

        /* Dark Mode Status Items */
        body.dark-mode .status-item {
            border-bottom: 1px solid var(--border-color);
        }

        body.dark-mode .status-title {
            color: var(--text-primary);
        }

        body.dark-mode .status-description {
            color: var(--text-secondary);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            font-family: 'Inter', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: transparent;
            position: relative;
            overflow-x: hidden;
        }

        .dashboard-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(45, 90, 39, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(74, 124, 89, 0.05) 0%, transparent 50%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="15" cy="15" r="0.8" fill="rgba(45,90,39,0.1)"/><circle cx="85" cy="25" r="0.5" fill="rgba(245,158,11,0.1)"/><circle cx="25" cy="85" r="0.6" fill="rgba(74,124,89,0.1)"/><circle cx="75" cy="75" r="0.4" fill="rgba(230,126,34,0.1)"/></svg>');
            pointer-events: none;
            z-index: -1;
            animation: float 30s ease-in-out infinite;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 
                4px 0 20px rgba(0, 0, 0, 0.1),
                1px 0 3px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
            will-change: transform, width;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(45, 90, 39, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.05) 0%, transparent 50%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="15" cy="15" r="0.8" fill="rgba(45,90,39,0.1)"/><circle cx="85" cy="25" r="0.5" fill="rgba(245,158,11,0.1)"/><circle cx="25" cy="85" r="0.6" fill="rgba(74,124,89,0.1)"/><circle cx="75" cy="75" r="0.4" fill="rgba(245,158,11,0.1)"/></svg>');
            pointer-events: none;
            z-index: -1;
            animation: float 25s ease-in-out infinite;
        }

        .sidebar.collapsed {
            width: 70px;
            min-width: 70px;
            box-shadow: 
                4px 0 20px rgba(0, 0, 0, 0.15),
                1px 0 3px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed .sidebar-content {
            overflow: hidden;
        }

        .sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
            padding-bottom: 80px; /* Add space for the toggle button */
        }

        /* Custom scrollbar for sidebar */
        .sidebar-content::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 3px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 3px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        /* Enhanced Logo Design */
        .logo {
             display: flex;
             align-items: center;
            gap: 12px;
            padding: 24px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .logo::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.8s ease;
        }

        .logo:hover::before {
            left: 100%;
        }

        .logo:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.2);
        }

        .sidebar.collapsed .logo {
            justify-content: center;
            padding: 20px 8px;
            min-width: 70px;
            width: 70px;
            box-sizing: border-box;
        }

        .sidebar.collapsed .logo span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .logo i {
            font-size: 28px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 4px rgba(37, 99, 235, 0.2));
            transition: all 0.3s ease;
        }

        .logo:hover i {
            transform: scale(1.1) rotate(5deg);
            filter: drop-shadow(0 4px 8px rgba(37, 99, 235, 0.3));
        }

        /* Enhanced Navigation Menu */
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
            position: relative;
        }

        .nav-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.2), transparent);
        }

        .nav-item {
            margin: 0;
            width: 100%;
            box-sizing: border-box;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 14px;
            position: relative;
            border-radius: 0 12px 12px 0;
            margin: 4px 0;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            cursor: pointer;
            user-select: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 16px 8px;
            min-width: 70px;
            width: 70px;
            box-sizing: border-box;
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
            overflow: hidden;
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
        }

        .sidebar.collapsed .nav-link.has-submenu::after {
            display: none;
        }

        .sidebar.collapsed .submenu {
            position: absolute;
            left: 100%;
            top: 0;
            width: 200px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            opacity: 0;
            visibility: hidden;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .nav-item:hover .submenu {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0 12px 12px 0;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(245, 158, 11, 0.15) 100%);
            color: var(--primary-color);
            transform: translateX(6px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .nav-link:hover::before {
            width: 4px;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
            color: var(--primary-color);
            transform: translateX(6px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        .nav-link.active::before {
            width: 4px;
        }

        /* Ensure active states work properly */
        .nav-link.active i {
            color: var(--primary-color);
        }

        .submenu-item.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
            color: var(--primary-color);
            transform: translateX(6px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        .submenu-item.active::before {
            width: 3px;
        }

        .submenu-item.active i {
            color: var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }

        .nav-link:hover i {
            transform: scale(1.2) rotate(5deg);
            color: var(--primary-color);
        }

        .nav-link.active i {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        /* Enhanced Submenu Styling */
        .nav-item.has-submenu {
            position: relative;
        }

        .nav-link.has-submenu {
            cursor: pointer;
            position: relative;
        }

        .nav-link.has-submenu::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 12px;
            color: var(--text-secondary);
        }

        .nav-link.has-submenu.expanded::after {
            transform: translateY(-50%) rotate(180deg);
            color: var(--primary-color);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(255, 255, 255, 0.95) 100%);
            border-radius: 0 0 12px 12px;
            margin: 0 8px;
            opacity: 0;
            transform: translateY(-10px);
            backdrop-filter: blur(10px);
        }

        .submenu.expanded {
            max-height: 500px;
            opacity: 1;
            transform: translateY(0);
            animation: slideDown 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px 12px 48px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 13px;
            border-left: 3px solid transparent;
            position: relative;
            margin: 2px 0;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
        }

        .submenu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .submenu-item:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(245, 158, 11, 0.15) 100%);
            color: var(--primary-color);
            transform: translateX(6px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .submenu-item:hover::before {
            width: 3px;
        }

        .submenu-item.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
            color: var(--primary-color);
            transform: translateX(6px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        .submenu-item.active::before {
            width: 3px;
        }

        .submenu-item i {
            width: 16px;
            text-align: center;
            font-size: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .submenu-item:hover i {
            transform: scale(1.2) rotate(5deg);
            color: var(--primary-color);
        }

        .submenu-item.active i {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        /* Enhanced Sidebar Toggle */
        .sidebar-toggle {
            position: absolute;
            bottom: 20px;
            right: 50%;
            transform: translateX(50%);
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 10px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1002;
            box-sizing: border-box;
        }

        /* Ensure sidebar toggle is always visible */
        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .sidebar-toggle:active {
            transform: scale(0.95);
        }

        .sidebar.collapsed .sidebar-toggle {
            right: 50%;
            transform: translateX(50%);
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .sidebar-toggle i {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

                 .sidebar-footer {
             margin-top: auto;
             padding: 20px;
             border-top: 1px solid rgba(255, 255, 255, 0.2);
             background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(241, 245, 249, 0.9) 100%);
             backdrop-filter: blur(10px);
            position: relative;
        }

         .sidebar-footer::before {
            content: '';
            position: absolute;
             top: 0;
            left: 0;
             right: 0;
             height: 1px;
             background: linear-gradient(90deg, transparent, rgba(74, 124, 89, 0.3), transparent);
         }

                   .user-info-card {
              /* background: rgba(255, 255, 255, 0.9); */
              backdrop-filter: blur(15px);
              /* border-radius: 20px; */
              padding: 20px;
              border: 1px solid rgba(255, 255, 255, 0.3);
              /* box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); */
            display: flex;
            align-items: center;
            gap: 16px;
              transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

          .user-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
              /* background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); */
              transition: left 0.6s ease;
          }

          .user-info-card:hover::before {
            left: 100%;
        }

                   .user-info-card:hover {
              transform: translateY(-4px) scale(1.02);
              /* box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15); */
          }

        .user-avatar {
              width: 48px;
              height: 48px;
              border-radius: 50%;
              background: linear-gradient(135deg, #4a7c59, #e67e22);
              display: flex;
              align-items: center;
              justify-content: center;
              color: white;
              font-size: 18px;
              box-shadow: 0 4px 12px rgba(74, 124, 89, 0.3);
              transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
              overflow: hidden;
        }

          .user-avatar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
              bottom: 0;
              background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
              transform: translateX(-100%);
              transition: transform 0.6s ease;
          }

          .user-avatar:hover::before {
              transform: translateX(100%);
          }

                   .user-avatar:hover {
              transform: scale(1.15) rotate(5deg);
              box-shadow: 0 8px 20px rgba(74, 124, 89, 0.4);
          }

                   .user-details {
              flex: 1;
              min-width: 0;
              position: relative;
              z-index: 1;
          }

                   .user-name {
              font-size: 15px;
              font-weight: 700;
              color: var(--google-text);
              margin-bottom: 4px;
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
              position: relative;
              z-index: 1;
          }

                   .user-email {
              font-size: 13px;
              color: var(--google-gray);
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
              position: relative;
              z-index: 1;
          }

                   .user-actions {
              display: flex;
              gap: 8px;
              align-items: center;
              position: relative;
              z-index: 1;
          }
         
                   .action-btn {
              width: 32px;
              height: 32px;
              border-radius: 10px;
              border: 1px solid rgba(255, 255, 255, 0.3);
              background: rgba(255, 255, 255, 0.9);
              color: var(--google-text);
              display: flex;
              align-items: center;
              justify-content: center;
              font-size: 13px;
              cursor: pointer;
              transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
              text-decoration: none;
              backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

          .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
              left: -100%;
              width: 100%;
              height: 100%;
              background: linear-gradient(90deg, transparent, rgba(74, 124, 89, 0.1), transparent);
              transition: left 0.5s ease;
          }

          .action-btn:hover::before {
              left: 100%;
          }

                   .action-btn:hover {
              background: linear-gradient(135deg, rgba(74, 124, 89, 0.15) 0%, rgba(230, 126, 34, 0.15) 100%);
              border-color: var(--anef-green);
              color: var(--anef-green);
              transform: translateY(-2px) scale(1.05);
              box-shadow: 0 4px 12px rgba(74, 124, 89, 0.3);
          }

                   .action-btn:active {
              transform: translateY(0) scale(0.95);
          }



        /* Main content styles moved to styles.blade.php */

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Content wrapper positioning handled in styles.blade.php */
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1.25rem 1.5rem 2rem 1.5rem; /* Better mobile padding */
            }
        }

        /* Small Mobile Responsive */
        @media (max-width: 480px) {
            .content-wrapper {
                padding: 1rem 1.25rem 1.5rem 1.25rem; /* Better small mobile padding */
            }
        }

        /* Content wrapper styles moved to styles.blade.php */

        /* Creative Header Design */
        .content-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, var(--background-light) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem 3rem;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .content-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                var(--primary-color) 0%, 
                var(--accent-color) 25%, 
                var(--secondary-color) 50%, 
                var(--success-color) 75%, 
                var(--info-color) 100%);
            animation: shimmer 3s ease-in-out infinite;
        }

        /* @keyframes shimmer {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        } */

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .greeting-section {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px var(--shadow-medium);
            position: relative;
            overflow: hidden;
        }

        .mobile-menu-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .mobile-menu-toggle:hover::before {
            left: 100%;
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow-heavy);
        }

        .greeting {
            position: relative;
        }

        .greeting h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            position: relative;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .greeting h1::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
            animation: expandWidth 1s ease-out 0.5s both;
        }

        @keyframes expandWidth {
            from { width: 0; }
            to { width: 80px; }
        }

        .greeting p {
            color: var(--text-secondary);
            margin: 1rem 0 0 0;
            font-size: 1.1rem;
            font-weight: 500;
            position: relative;
            padding-left: 2rem;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .greeting p::before {
            content: '📅';
            position: absolute;
            left: 0;
            top: 0;
            font-size: 1.2rem;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-3px); }
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        /* Enhanced Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 16px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            color: white;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px var(--shadow-medium);
            backdrop-filter: blur(10px);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            box-shadow: 0 8px 24px var(--shadow-heavy);
            transform: translateY(-3px) scale(1.02);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(0.98);
        }

        /* Creative Content Area */
        .content-area {
            padding: 1rem 2rem;
            padding-top: 1rem; /* Reduced top padding since main-content already has padding-top */
            position: relative;
        }

        /* Enhanced Alert Styles */
        .alert {
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(22, 163, 74, 0.1) 100%);
            color: var(--success-dark);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: var(--danger-dark);
            border-left: 4px solid var(--danger-color);
        }

        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
            animation: shimmer 2s ease-in-out infinite;
        }

        /* Enhanced Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 4px 20px var(--shadow-light),
                0 1px 3px var(--shadow-lighter);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            animation: fadeInUp 0.6s ease-out;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            box-shadow: 
                0 12px 40px var(--shadow-medium),
                0 4px 12px var(--shadow-light);
            transform: translateY(-8px) scale(1.02);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            background: linear-gradient(135deg, var(--background-light) 0%, var(--background-medium) 100%);
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem 2rem;
            position: relative;
        }

        .card-body {
            padding: 2rem;
        }

        /* Enhanced Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--purple-color), var(--accent-color));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.15),
                0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, #7c3aed, #8b5cf6);
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        .stat-card.orange::before {
            background: linear-gradient(90deg, #ea580c, #f97316);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #059669, #10b981);
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--anef-green), var(--anef-orange));
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(74, 124, 89, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fab:hover {
            transform: translateY(-4px) scale(1.1);
            box-shadow: 0 12px 32px rgba(74, 124, 89, 0.5);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .content-header {
                padding: 1.5rem 2rem;
            }
            
            .content-area {
                padding: 1.5rem 2rem;
            }
            
            .greeting h1 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            
            .content-header {
                padding: 1rem 1.5rem;
            }
            
            .content-area {
                padding: 1rem 1.5rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .greeting-section {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .greeting h1 {
                font-size: 1.75rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stat-card {
                padding: 2rem;
            }
            
            .fab {
                bottom: 1rem;
                right: 1rem;
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            /* Mobile Sidebar Enhancements */
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                width: 100%;
                max-width: 280px;
                z-index: 1001;
                overflow: hidden;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.show .sidebar-content {
                overflow-y: auto;
            }

            /* Mobile backdrop overlay */
            .sidebar::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
            }

            .sidebar.show::after {
                opacity: 1;
                pointer-events: auto;
            }
            
            .content-wrapper {
                margin-left: 0;
                padding: 0;
                padding-top: 80px; /* Smaller padding for mobile */
            }
            
            .top-bar {
                left: 0;
                padding: 1rem;
            }
        
            .sidebar-toggle {
                display: none;
            }

            .sidebar-footer {
                padding: 16px;
            }

            .user-info-card {
                padding: 16px;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
        }

        /* Loading Animation */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Scrollbar Styling */
        .content-wrapper::-webkit-scrollbar {
            width: 8px;
        }

        .content-wrapper::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .content-wrapper::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--anef-green), var(--anef-orange));
            border-radius: 4px;
        }

        .content-wrapper::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--anef-dark-green), var(--anef-green));
        }

        /* Top Bar Styles moved to styles.blade.php */
            display: flex;
            align-items: center;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .top-bar-btn {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .top-bar-btn:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 124, 89, 0.2);
        }

        /* Notification Styles */
        .notification-dropdown {
            position: relative;
        }

        .notification-btn {
            position: relative;
            min-width: auto;
            padding: 0.75rem;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, var(--danger-color), var(--danger-dark));
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid white;
        }

        .notification-panel {
            position: absolute;
            top: 100%;
            right: 0;
            width: 380px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .notification-panel.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-primary);
        }

        .mark-all-read {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 0.875rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .mark-all-read:hover {
            background: rgba(74, 124, 89, 0.1);
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: var(--bg-secondary);
        }

        .notification-item.unread {
            background: rgba(74, 124, 89, 0.05);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bg-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .notification-text {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .notification-time {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .notification-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-light);
            text-align: center;
        }

        .view-all-notifications {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .view-all-notifications:hover {
            text-decoration: underline;
        }

        /* Dark Mode Button */
        .dark-mode-btn {
            min-width: auto;
            padding: 0.75rem;
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            min-width: auto;
            padding: 0.75rem 1rem;
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .profile-name {
            color: var(--text-primary);
        }

        .profile-panel {
            position: absolute;
            top: 100%;
            right: 0;
            width: 280px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .profile-panel.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-secondary);
            border-radius: 16px 16px 0 0;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-avatar-large {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.125rem;
        }

        .profile-details {
            flex: 1;
            min-width: 0;
        }

        .profile-name-large {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .profile-email {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .profile-menu {
            padding: 0.5rem 0;
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .profile-menu-item:hover {
            background: var(--bg-secondary);
            color: var(--primary-color);
        }

        .profile-menu-item i {
            width: 16px;
            text-align: center;
        }

        .profile-menu-divider {
            height: 1px;
            background: var(--border-light);
            margin: 0.5rem 0;
        }

        .logout-btn {
            color: var(--danger-color);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        /* Responsive Design - Top bar styles moved to styles.blade.php */
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle Button -->
    <button class="mobile-sidebar-toggle" onclick="toggleMobileSidebar()" aria-label="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-sidebar-overlay" onclick="closeMobileSidebar()"></div>

    <div class="dashboard-container">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="content-wrapper">
            @include('partials.top-bar')

            <!-- <div class="content-header">
                <div class="header-content">
                    <div class="greeting-section">
                <button class="mobile-menu-toggle d-md-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                        <div class="greeting">
                <h1>Bonjour, {{ auth()->user()->name }}</h1>
                            <p>Aujourd'hui nous sommes {{ now()->format('d/m/Y') }}</p>
                        </div>
            </div> -->
            <!-- <div class="header-actions">
                @yield('page-actions')
            </div> -->
            <!-- </div> -->

            @include('partials.content-wrapper')
        </div>
    </div>

    @include('partials.scripts')
</body>
</html> 