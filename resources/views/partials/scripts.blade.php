<!-- Material Design Web Components JS -->
<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Mobile Sidebar Functions
    function toggleMobileSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.mobile-sidebar-overlay');
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Prevent body scroll when sidebar is open
        if (sidebar.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    function closeMobileSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.mobile-sidebar-overlay');
        
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close mobile sidebar when clicking on a link
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarLinks = document.querySelectorAll('.sidebar a, .sidebar button');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 991.98) {
                    closeMobileSidebar();
                }
            });
        });
    });

    // Close mobile sidebar on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMobileSidebar();
        }
    });

    // Handle window resize for mobile sidebar
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991.98) {
            closeMobileSidebar();
        }
    });

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        } else {
            sidebar.classList.add('show');
        }
    }

    function toggleSidebarCollapse() {
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
        } else {
            sidebar.classList.add('collapsed');
        }
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

    // Notification functions
    function toggleNotifications() {
        const panel = document.getElementById('notificationPanel');
        panel.classList.toggle('show');
        
        // Close profile panel if open
        const profilePanel = document.getElementById('profilePanel');
        if (profilePanel) {
            profilePanel.classList.remove('show');
        }
    }

    function markAllAsRead() {
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        unreadItems.forEach(item => {
            item.classList.remove('unread');
        });
        
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.style.display = 'none';
        }
    }

    // Profile functions
    function toggleProfile() {
        const panel = document.getElementById('profilePanel');
        panel.classList.toggle('show');
        
        // Close notification panel if open
        const notificationPanel = document.getElementById('notificationPanel');
        if (notificationPanel) {
            notificationPanel.classList.remove('show');
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const notificationDropdown = document.querySelector('.notification-dropdown');
        const profileDropdown = document.querySelector('.profile-dropdown');
        
        // Close notification panel if clicking outside
        if (notificationDropdown && !notificationDropdown.contains(event.target)) {
            const notificationPanel = document.getElementById('notificationPanel');
            if (notificationPanel) {
                notificationPanel.classList.remove('show');
            }
        }
        
        // Close profile panel if clicking outside
        if (profileDropdown && !profileDropdown.contains(event.target)) {
            const profilePanel = document.getElementById('profilePanel');
            if (profilePanel) {
                profilePanel.classList.remove('show');
            }
        }
    });

    // Close dropdowns when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const notificationPanel = document.getElementById('notificationPanel');
            const profilePanel = document.getElementById('profilePanel');
            
            if (notificationPanel) {
                notificationPanel.classList.remove('show');
            }
            if (profilePanel) {
                profilePanel.classList.remove('show');
            }
        }
    });

    // Dark Mode Toggle
    function toggleDarkMode() {
        const body = document.body;
        const isDarkMode = body.classList.contains('dark-mode');
        
        if (isDarkMode) {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
            updateDarkModeIcons(false);
        } else {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
            updateDarkModeIcons(true);
        }
    }

    function updateDarkModeIcons(isDarkMode) {
        const sidebarIcon = document.getElementById('dark-mode-icon');
        const topBarIcon = document.getElementById('top-dark-mode-icon');
        
        if (sidebarIcon) {
            sidebarIcon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
        }
        if (topBarIcon) {
            topBarIcon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
        }
    }

    // Initialize dark mode from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
            updateDarkModeIcons(true);
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
