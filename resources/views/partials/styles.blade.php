<style>
    /* CSS Variables */
    :root {
        --primary-color: #4a7c59;
        --primary-dark: #3d6b4a;
        --accent-color: #e67e22;
        --secondary-color: #6b7280;
        --success-color: #10b981;
        --success-dark: #059669;
        --danger-color: #ef4444;
        --danger-dark: #dc2626;
        --info-color: #3b82f6;
        --warning-color: #f59e0b;
        --purple-color: #8b5cf6;
        
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        
        --background-light: #ffffff;
        --background-medium: #f9fafb;
        --background-dark: #f3f4f6;
        
        --border-light: #e5e7eb;
        --border-medium: #d1d5db;
        
        --shadow-light: rgba(0, 0, 0, 0.05);
        --shadow-medium: rgba(0, 0, 0, 0.1);
        --shadow-heavy: rgba(0, 0, 0, 0.15);
        --shadow-lighter: rgba(0, 0, 0, 0.025);
        
        --anef-green: #4a7c59;
        --anef-orange: #e67e22;
        --anef-dark-green: #3d6b4a;
        
        --google-text: #202124;
        --google-gray: #5f6368;
    }

    /* Dark Mode Variables */
    .dark-mode {
        --text-primary: #f9fafb;
        --text-secondary: #d1d5db;
        --text-muted: #9ca3af;
        
        --background-light: #1f2937;
        --background-medium: #111827;
        --background-dark: #0f172a;
        
        --border-light: #374151;
        --border-medium: #4b5563;
        
        --shadow-light: rgba(0, 0, 0, 0.2);
        --shadow-medium: rgba(0, 0, 0, 0.3);
        --shadow-heavy: rgba(0, 0, 0, 0.4);
        --shadow-lighter: rgba(0, 0, 0, 0.1);
    }

    /* Content Container - Depends on App Overflow */
    .content-scroll-container {
        height: auto;
        min-height: auto;
        overflow-y: auto; /* Enable vertical scrolling with custom style */
        overflow-x: hidden; /* Hide horizontal overflow */
        padding: 0.5rem 0; /* Add some internal spacing */
        margin: 0 -0.5rem; /* Compensate for padding */
        position: relative;
        scrollbar-width: none; /* Hide default Firefox scrollbar */
        -ms-overflow-style: none; /* Hide default IE scrollbar */
    }

    /* Custom Sidebar-Style Scrollbar */
    .content-scroll-container::-webkit-scrollbar {
        width: 6px; /* Thin scrollbar like sidebar */
    }

    .content-scroll-container::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 3px;
    }

    .content-scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        border-radius: 3px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-scroll-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        transform: scaleX(1.2);
        box-shadow: 0 2px 8px rgba(74, 124, 89, 0.3);
    }

    .content-scroll-container::-webkit-scrollbar-corner {
        background: transparent;
    }

    /* Dark Mode Sidebar-Style Scrollbar */
    .dark-mode .content-scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .dark-mode .content-scroll-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        box-shadow: 0 2px 8px rgba(74, 124, 89, 0.5);
    }

    /* Top Bar Fixed Sizing */
    .top-bar {
        height: 70px; /* Fixed height */
        min-height: 70px;
        max-height: 70px;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.98) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        position: fixed;
        top: 0;
        left: 280px; /* Perfect alignment with sidebar */
        right: 0;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: left 0.3s cubic-bezier(0.4, 0, 2, 1);
    }

    .sidebar.collapsed + .main-content .top-bar {
        left: 70px; /* Perfect alignment with collapsed sidebar */
    }

    .top-bar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Top Bar Elements Styling */
    .top-bar-left {
        display: flex;
        align-items: center;
    }

    .breadcrumbs {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .breadcrumb-item {
        color: var(--text-primary);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb-separator {
        color: var(--text-muted);
    }

    .top-bar-right {
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
        height: 44px; /* Fixed height for consistency */
        min-width: 44px;
    }

    .top-bar-btn:hover {
        background: rgba(255, 255, 255, 0.95);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 124, 89, 0.2);
    }

    .notification-btn {
        position: relative;
        min-width: 44px;
        justify-content: center;
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

    .dark-mode-btn {
        min-width: 44px;
        justify-content: center;
    }

    .profile-btn {
        min-width: auto;
        padding: 0.75rem 1rem;
        height: 44px;
    }

    .profile-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .profile-name {
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Content Area Styling */
    .content-area {
        /* padding: 1.5rem 2rem; /* Match top bar padding
        padding-top: 1.5rem; Better top spacing for content */
        height: 100%;
        box-sizing: border-box;
        max-width: 1400px; /* Match top bar max-width */
        margin: 0 auto; /* Center content on wide screens */
        background: transparent; /* Inherit from main-content */
        backdrop-filter: none; /* No additional blur */
    }

    .main-content {
        /* width: 100%; */
        margin-left: 280px; /* Perfect alignment with sidebar */
        padding: 0;
        margin-top: 85px; /* Increased top spacing for better breathing room */
        min-height: 100vh;
        position: relative;
        transition: margin-left 0.3s ease;
        box-sizing: border-box;
        /* overflow: visible; Default overflow for cards */
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.98) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .main-content.expanded {
        margin-left: 70px; /* Perfect alignment with collapsed sidebar */
    }

    /* Content Header Styling */
    .content-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, var(--background-light) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1.5rem 3rem;
        margin-bottom: 2rem;
        position: relative;
        border-radius: 16px;
        margin: 0 0 2rem 0;
    }

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

    .greeting h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
    }

    .greeting p {
        color: var(--text-secondary);
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
        font-weight: 500;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 0;
            padding-top: 85px; /* Consistent with desktop */
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .top-bar {
            left: 0;
            padding: 0 1rem;
        }

        .content-area {
            overflow: none;
            padding: 1rem 1.5rem; /* Match top bar mobile padding */
            max-width: 100%; /* Full width on mobile */
        }

        .content-header {
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }

        .content-scroll-container {
            height: auto;
            min-height: auto;
            overflow-y: auto; /* Maintain sidebar-style scrollbar on mobile */
            overflow-x: hidden;
            padding: 0.25rem 0; /* Reduced padding on mobile */
            scrollbar-width: none; /* Hide default Firefox scrollbar */
            -ms-overflow-style: none; /* Hide default IE scrollbar */
        }

        .greeting h1 {
            font-size: 1.5rem;
        }

        .greeting p {
            font-size: 0.9rem;
        }

        .top-bar-btn {
            height: 40px;
            min-width: 40px;
            padding: 0.5rem;
        }

        .profile-btn {
            height: 40px;
            padding: 0.5rem 0.75rem;
        }

        .profile-avatar {
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }

        .profile-name {
            font-size: 0.8rem;
        }
    }

    /* Small Mobile Responsive */
    @media (max-width: 480px) {
        .main-content {
            padding: 0;
            padding-top: 80px; /* Better small mobile spacing */
        }
        
        .content-area {
            padding: 0.75rem 1rem; /* Match top bar small mobile padding */
        }
        
        .content-scroll-container {
            padding: 0.125rem 0; /* Minimal padding on small mobile */
            overflow-y: auto; /* Maintain sidebar-style scrollbar on small mobile */
            overflow-x: hidden;
            scrollbar-width: none; /* Hide default Firefox scrollbar */
            -ms-overflow-style: none; /* Hide default IE scrollbar */
        }
    }

    /* Content Container inherits app scrolling behavior */
    .content-scroll-container {
        /* Inherits app overflow and scrolling behavior */
    }

    /* Main Content - Default Overflow */
    .main-content {
        /* overflow: visible; Default overflow for cards */
    }

    /* Enhanced Content Spacing */
    .content-scroll-container > * {
        margin-bottom: 1.5rem; /* Consistent spacing between content blocks */
    }
    
    /* Main Content Visual Integration with Top Bar */
    .main-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(255, 255, 255, 0.5) 20%, 
            rgba(255, 255, 255, 0.8) 50%, 
            rgba(255, 255, 255, 0.5) 80%, 
            transparent 100%);
        z-index: 1;
    }
    
    /* Content Area Visual Enhancement */
    .content-area::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, 
            var(--primary-color) 0%, 
            var(--accent-color) 25%, 
            var(--success-color) 50%, 
            var(--info-color) 75%, 
            var(--purple-color) 100%);
        opacity: 0.1;
        border-radius: 0 0 2px 2px;
    }
    
    .content-scroll-container > *:last-child {
        margin-bottom: 0; /* No margin on last element */
    }
    
    /* Card and Component Spacing */
    .card, .x-card, [class*="card"] {
        margin-bottom: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card:hover, .x-card:hover, [class*="card"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    /* Stats Grid Spacing */
    .stats-grid, [class*="stats"] {
        margin-bottom: 2rem;
        gap: 1.5rem;
    }
    
    /* Filter Section Spacing */
    .filter-section, [class*="filter"] {
        margin-bottom: 2rem;
    }
    
    /* Data Table Spacing */
    .data-table, [class*="table"] {
        margin-bottom: 2rem;
    }
    
    /* Import Export Section Spacing */
    .import-export-section, [class*="import"], [class*="export"] {
        margin-bottom: 2rem;
    }
    
    /* Alert Spacing */
    .alert, [class*="alert"] {
        margin-bottom: 1.5rem;
        border-radius: 12px;
    }
    
    /* Form Spacing */
    form {
        margin-bottom: 1.5rem;
    }
    
    /* Button Group Spacing */
    .btn-group, .button-group, [class*="btn"], [class*="button"] {
        margin-bottom: 1rem;
    }
    
    /* Print Styles */
    @media print {
        .content-scroll-container {
            height: auto;
            overflow: visible;
        }
    }
</style>
