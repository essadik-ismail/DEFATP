<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Exploitation')</title>
    
    <!-- Material Design Web Components -->
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS (for compatibility) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
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
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 
                4px 0 20px rgba(0, 0, 0, 0.1),
                1px 0 3px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
            position: relative;
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
            overflow: hidden;
            min-width: 70px;
            box-shadow: 
                4px 0 20px rgba(0, 0, 0, 0.15),
                1px 0 3px rgba(0, 0, 0, 0.1);
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

        .sidebar.collapsed .nav-link.has-submenu::after {
            display: none;
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

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
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
            right: -12px;
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
            z-index: 1001;
            box-sizing: border-box;
        }

        .sidebar.collapsed .sidebar-toggle {
            right: -15px;
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



        .main-content {
            flex: 1;
            margin-left: 0;
            /* padding-left: 280px; */
            background: linear-gradient(135deg, var(--background-light) 0%, var(--background-medium) 50%, var(--background-dark) 100%);
            min-height: 100vh;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
        }

        .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100vh;
            background: 
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(168, 85, 247, 0.05) 0%, transparent 50%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="1" fill="rgba(37,99,235,0.1)"/><circle cx="90" cy="20" r="0.5" fill="rgba(245,158,11,0.1)"/><circle cx="30" cy="80" r="0.8" fill="rgba(37,99,235,0.1)"/><circle cx="70" cy="70" r="0.6" fill="rgba(245,158,11,0.1)"/><circle cx="50" cy="30" r="0.4" fill="rgba(168,85,247,0.1)"/></svg>');
            pointer-events: none;
            z-index: 0;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .main-content.expanded {
            padding-left: 70px;
        }

        .main-content.expanded::before {
            left: 70px;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
            padding: 0;
            min-height: 100vh;
        }

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
            padding: 2rem 3rem;
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
            }

            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                padding-left: 0;
            }

            .main-content.expanded {
                padding-left: 0;
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
        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--anef-green), var(--anef-orange));
            border-radius: 4px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--anef-dark-green), var(--anef-green));
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="sidebar-content">
                <div class="logo">
                <i class="fas fa-tree logo-icon"></i>
                    <span>SylvaNet</span>
                </div>
                
                <ul class="nav-menu">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" data-title="Tableau de Bord">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Tableau de Bord</span>
                        </a>
                    </li>

                    <!-- Exploitation Régulière -->
                    <li class="nav-item has-submenu">
                        <a class="nav-link has-submenu {{ request()->routeIs('articles.*') ? 'active' : '' }}" onclick="toggleSubmenu(this)" data-title="Exploitation régulière">
                            <i class="fas fa-industry"></i>
                            <span>Exploitation régulière</span>
                        </a>
                        <div class="submenu">
                            <a class="submenu-item {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                                <i class="fas fa-list"></i> <span>Articles</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('articles.create') ? 'active' : '' }}" href="{{ route('articles.create') }}">
                                <i class="fas fa-plus"></i> <span>Nouvel Article</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('settings.essences') ? 'active' : '' }}" href="{{ route('settings.essences') }}">
                                <i class="fas fa-seedling"></i> <span>Essences</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('settings.forets') ? 'active' : '' }}" href="{{ route('settings.forets') }}">
                                <i class="fas fa-mountain"></i> <span>Forêts</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('settings.nature-de-coupes') ? 'active' : '' }}" href="{{ route('settings.nature-de-coupes') }}">
                                <i class="fas fa-axe"></i> <span>Nature de Coupes</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('settings.situation-administratives') ? 'active' : '' }}" href="{{ route('settings.situation-administratives') }}">
                                <i class="fas fa-building"></i> <span>Situations Administratives</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('settings.session-adjudications') ? 'active' : '' }}" href="{{ route('settings.session-adjudications') }}">
                                <i class="fas fa-hammer"></i> <span>Sessions d'Adjudication</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('settings.exploitants') ? 'active' : '' }}" href="{{ route('settings.exploitants') }}">
                                <i class="fas fa-user-tie"></i> <span>Exploitants</span>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-title="Contract De Partenariat">
                            <i class="fas fa-handshake"></i>
                            <span>Contract De Partenariat</span>
                        </a>
                    </li>

                    <li class="nav-item has-submenu">
                        <a class="nav-link has-submenu" onclick="toggleSubmenu(this)" data-title="Carte Professionnelle">
                            <i class="fas fa-id-card"></i>
                            <span>Carte Professionnelle</span>
                        </a>
                        <div class="submenu">
                            <a class="submenu-item {{ request()->routeIs('settings.exploitants') ? 'active' : '' }}" href="{{ route('settings.exploitants') }}">
                                <i class="fas fa-user-tie"></i> <span>Exploitants</span>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-title="Bois Particulier">
                            <i class="fas fa-tree"></i>
                            <span>Bois Particulier</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}" data-title="Rapports">
                            <i class="fas fa-chart-line"></i>
                            <span>Rapports</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('excel.*') ? 'active' : '' }}" href="{{ route('excel.index') }}" data-title="Import/Export Excel">
                            <i class="fas fa-file-excel"></i>
                            <span>Import/Export Excel</span>
                        </a>
                    </li>

                    <li class="nav-item has-submenu">
                        <a class="nav-link has-submenu" onclick="toggleSubmenu(this)" data-title="Utilisateurs">
                            <i class="fas fa-user-shield"></i>
                            <span>Utilisateurs</span>
                        </a>
                        <div class="submenu">
                            <a class="submenu-item {{ request()->routeIs('auth.users.*') ? 'active' : '' }}" href="{{ route('auth.users.index') }}">
                                <i class="fas fa-users-cog"></i> <span>Gestion des Utilisateurs</span>
                            </a>
                            <a class="submenu-item {{ request()->routeIs('auth.profile') ? 'active' : '' }}" href="{{ route('auth.profile') }}">
                                <i class="fas fa-user-circle"></i> <span>Mon Profil</span>
                            </a>
                        </div>
                    </li>
                    
                </ul>
                                 <div class="sidebar-footer">
                     <div class="user-info-card">
                         <!-- <div class="user-avatar">
                             <i class="fas fa-tree"></i>
                         </div> -->
                         <!-- <div class="user-details">
                             <div class="user-name">{{ auth()->user()->name }}</div>
                             <div class="user-email">{{ auth()->user()->ppr }}</div>
                         </div> -->
                         <div class="user-actions">
                             <button class="action-btn" title="Mode Sombre" onclick="toggleDarkMode()">
                                 <i class="fas fa-moon" id="dark-mode-icon"></i>
                             </button>
                             <a href="{{ route('auth.profile') }}" class="action-btn" title="Mon Profil">
                                 <i class="fas fa-user"></i>
                             </a>
                             <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                 @csrf
                                 <button type="submit" class="action-btn" title="Déconnexion">
                                     <i class="fas fa-sign-out-alt"></i>
                                 </button>
                             </form>
                         </div>
                     </div>
                 </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
            <div class="content-header">
                    <div class="header-content">
                        <div class="greeting-section">
                    <button class="mobile-menu-toggle d-md-none" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                            <div class="greeting">
                    <h1>Bonjour, {{ auth()->user()->name }}</h1>
                                <p>Aujourd'hui nous sommes {{ now()->format('d/m/Y') }}</p>
                            </div>
                </div>
                <div class="header-actions">
                    @yield('page-actions')
                        </div>
                </div>
            </div>

                <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Material Design Web Components JS -->
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        function toggleSidebarCollapse() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        function toggleSubmenu(element) {
            const navItem = element.closest('.nav-item');
            const submenu = navItem.querySelector('.submenu');
            const isExpanded = element.classList.contains('expanded');
            
            // Close all other submenus
            document.querySelectorAll('.nav-link.has-submenu').forEach(link => {
                if (link !== element) {
                    link.classList.remove('expanded');
                    const otherSubmenu = link.closest('.nav-item').querySelector('.submenu');
                    if (otherSubmenu) {
                        otherSubmenu.classList.remove('expanded');
                    }
                }
            });
            
            // Toggle current submenu
            element.classList.toggle('expanded');
            if (submenu) {
                submenu.classList.toggle('expanded');
            }
        }
        
        // Auto-expand submenu if current page is active
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.submenu-item.active');
            if (activeSubmenuItem) {
                const submenu = activeSubmenuItem.closest('.submenu');
                const navLink = submenu.previousElementSibling;
                navLink.classList.add('expanded');
                submenu.classList.add('expanded');
            }
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileToggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    
    @stack('scripts')
    
    <script>
        // Initialize Material Design Components globally
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all Material Design buttons
            const buttons = document.querySelectorAll('.mdc-button');
            buttons.forEach(button => {
                mdc.ripple.MDCRipple.attachTo(button);
            });
            
            // Initialize all Material Design text fields
            const textFields = document.querySelectorAll('.mdc-text-field');
            textFields.forEach(field => {
                new mdc.textField.MDCTextField(field);
            });
            
            // Initialize all Material Design selects
            const selects = document.querySelectorAll('.mdc-select');
            selects.forEach(select => {
                new mdc.select.MDCSelect(select);
            });
            
            // Initialize all Material Design data tables
            const dataTables = document.querySelectorAll('.mdc-data-table');
            dataTables.forEach(table => {
                new mdc.dataTable.MDCDataTable(table);
            });
            
            // Initialize all Material Design cards
            const cards = document.querySelectorAll('.mdc-card');
            cards.forEach(card => {
                new mdc.card.MDCCard(card);
            });
        });
    </script>
</body>
</html> 