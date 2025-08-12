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

    /* Content Overflow Styling */
    .content-scroll-container {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: calc(100vh - 200px); /* Adjusted for proper spacing */
        padding-right: 8px; /* Space for scrollbar */
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }

    /* Custom Scrollbar Styling */
    .content-scroll-container::-webkit-scrollbar {
        width: 8px;
    }

    .content-scroll-container::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 4px;
    }

    .content-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .content-scroll-container::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.8);
    }

    .content-scroll-container::-webkit-scrollbar-corner {
        background: transparent;
    }

    /* Dark Mode Scrollbar */
    .dark-mode .content-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.3);
    }

    .dark-mode .content-scroll-container::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.6);
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
        left: 280px;
        right: 0;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.collapsed + .main-content .top-bar {
        left: 70px;
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
        padding: 2rem 3rem;
        padding-top: 1rem; /* Reduced top padding since top bar is fixed */
        position: relative;
        height: 100%;
    }

    .main-content {
        margin-left: 280px;
        padding-top: 70px; /* Match top bar height */
        min-height: 100vh;
        position: relative;
        transition: margin-left 0.3s ease;
    }

    .main-content.expanded {
        margin-left: 70px;
    }

    /* Content Header Styling */
    .content-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, var(--background-light) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1.5rem 3rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
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
            padding-top: 70px;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .top-bar {
            left: 0;
            padding: 0 1rem;
        }

        .content-area {
            padding: 1rem;
            padding-top: 1rem;
        }

        .content-header {
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }

        .content-scroll-container {
            max-height: calc(100vh - 180px);
            padding-right: 4px;
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

    /* Smooth Scrolling */
    .content-scroll-container {
        scroll-behavior: smooth;
    }

    /* Focus Styles for Accessibility */
    .content-scroll-container:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Print Styles */
    @media print {
        .content-scroll-container {
            overflow: visible;
            max-height: none;
        }
    }
</style>
