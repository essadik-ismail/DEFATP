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

    // Select Search Component Functions
    // Global state for select components
    window.selectSearchComponents = window.selectSearchComponents || {};

    // Initialize select search component
    function initSelectSearch(selectId) {
        if (window.selectSearchComponents[selectId]) {
            return; // Already initialized
        }

        const component = {
            id: selectId,
            isOpen: false,
            selectedValues: [],
            selectedLabels: [],
            multiple: false,
            maxItems: null
        };

        // Get component configuration
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const hiddenInput = document.getElementById(selectId);
        const display = wrapper.querySelector('.select-search-display');
        const dropdown = wrapper.querySelector('.select-search-dropdown');
        const options = wrapper.querySelectorAll('.option-item');

        // Check if multiple selection is enabled
        component.multiple = wrapper.querySelector('.select-search-display')?.classList.contains('multiple') || false;
        
        // Get max items if specified
        const maxItemsAttr = wrapper.querySelector('.select-search-container')?.getAttribute('data-max-items');
        component.maxItems = maxItemsAttr ? parseInt(maxItemsAttr) : null;

        // Initialize selected values
        if (hiddenInput.value) {
            if (component.multiple) {
                component.selectedValues = hiddenInput.value.split(',').filter(v => v.trim());
            } else {
                component.selectedValues = [hiddenInput.value];
            }
        }

        // Update selected labels
        component.selectedLabels = [];
        options.forEach(option => {
            if (component.selectedValues.includes(option.dataset.value)) {
                component.selectedLabels.push(option.dataset.label);
            }
        });

        // Store component reference
        window.selectSearchComponents[selectId] = component;

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!wrapper.contains(event.target)) {
                closeSelectSearch(selectId);
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSelectSearch(selectId);
            }
        });
    }

    // Toggle select dropdown
    function toggleSelectSearch(selectId) {
        const component = window.selectSearchComponents[selectId];
        if (!component) {
            initSelectSearch(selectId);
        }

        if (component.isOpen) {
            closeSelectSearch(selectId);
        } else {
            openSelectSearch(selectId);
        }
    }

    // Open select dropdown
    function openSelectSearch(selectId) {
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const display = wrapper.querySelector('.select-search-display');
        const dropdown = wrapper.querySelector('.select-search-dropdown');
        const searchInput = dropdown.querySelector('.search-input');

        // Close other open dropdowns
        Object.keys(window.selectSearchComponents).forEach(id => {
            if (id !== selectId) {
                closeSelectSearch(id);
            }
        });

        // Open this dropdown
        display.classList.add('open');
        dropdown.style.display = 'block';
        window.selectSearchComponents[selectId].isOpen = true;

        // Focus search input
        setTimeout(() => {
            searchInput.focus();
        }, 100);
    }

    // Close select dropdown
    function closeSelectSearch(selectId) {
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const display = wrapper.querySelector('.select-search-display');
        const dropdown = wrapper.querySelector('.select-search-dropdown');

        display.classList.remove('open');
        dropdown.style.display = 'none';
        window.selectSearchComponents[selectId].isOpen = false;
    }

    // Filter options based on search input
    function filterSelectOptions(selectId, searchTerm) {
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const options = wrapper.querySelectorAll('.option-item');
        const searchLower = searchTerm.toLowerCase();

        options.forEach(option => {
            const text = option.dataset.label.toLowerCase();
            if (text.includes(searchLower)) {
                option.classList.remove('hidden');
            } else {
                option.classList.add('hidden');
            }
        });
    }

    // Select an option
    function selectOption(selectId, value, label, multiple = false) {
        const component = window.selectSearchComponents[selectId];
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const hiddenInput = document.getElementById(selectId);
        const selectedItems = wrapper.querySelector('.selected-items');
        const placeholder = wrapper.querySelector('.placeholder-text');

        if (multiple) {
            // Multiple selection
            if (component.selectedValues.includes(value)) {
                // Remove if already selected
                component.selectedValues = component.selectedValues.filter(v => v !== value);
                component.selectedLabels = component.selectedLabels.filter(l => l !== label);
            } else {
                // Add if not selected
                if (component.maxItems && component.selectedValues.length >= component.maxItems) {
                    alert(`Vous ne pouvez sélectionner que ${component.maxItems} éléments maximum.`);
                    return;
                }
                component.selectedValues.push(value);
                component.selectedLabels.push(label);
            }
        } else {
            // Single selection
            component.selectedValues = [value];
            component.selectedLabels = [label];
            closeSelectSearch(selectId);
        }

        // Update hidden input
        hiddenInput.value = component.selectedValues.join(',');

        // Update display
        updateSelectDisplay(selectId);

        // Update option visual states
        updateOptionStates(selectId);
    }

    // Remove selected item (for multiple selection)
    function removeSelectedItem(selectId, label) {
        const component = window.selectSearchComponents[selectId];
        const value = component.selectedValues[component.selectedLabels.indexOf(label)];

        component.selectedValues = component.selectedValues.filter(v => v !== value);
        component.selectedLabels = component.selectedLabels.filter(l => l !== label);

        const hiddenInput = document.getElementById(selectId);
        hiddenInput.value = component.selectedValues.join(',');

        updateSelectDisplay(selectId);
        updateOptionStates(selectId);
    }

    // Clear selection
    function clearSelection(selectId) {
        const component = window.selectSearchComponents[selectId];
        component.selectedValues = [];
        component.selectedLabels = [];

        const hiddenInput = document.getElementById(selectId);
        hiddenInput.value = '';

        updateSelectDisplay(selectId);
        updateOptionStates(selectId);
        closeSelectSearch(selectId);
    }

    // Update select display
    function updateSelectDisplay(selectId) {
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const selectedItems = wrapper.querySelector('.selected-items');
        const placeholder = wrapper.querySelector('.placeholder-text');

        if (window.selectSearchComponents[selectId].selectedValues.length > 0) {
            // Show selected items
            if (window.selectSearchComponents[selectId].multiple) {
                selectedItems.innerHTML = '';
                window.selectSearchComponents[selectId].selectedLabels.forEach(label => {
                    const itemSpan = document.createElement('span');
                    itemSpan.className = 'selected-item';
                    itemSpan.innerHTML = `
                        ${label}
                        <button type="button" class="remove-item" onclick="removeSelectedItem('${selectId}', '${label}')">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    selectedItems.appendChild(itemSpan);
                });
                placeholder.style.display = 'none';
            } else {
                selectedItems.innerHTML = `<span class="selected-text">${window.selectSearchComponents[selectId].selectedLabels[0]}</span>`;
                placeholder.style.display = 'none';
            }
        } else {
            // Show placeholder
            selectedItems.innerHTML = '';
            placeholder.style.display = 'inline';
        }
    }

    // Update option visual states
    function updateOptionStates(selectId) {
        const wrapper = document.getElementById(`select-wrapper-${selectId}`);
        const options = wrapper.querySelectorAll('.option-item');
        const component = window.selectSearchComponents[selectId];

        options.forEach(option => {
            const value = option.dataset.value;
            if (component.selectedValues.includes(value)) {
                option.classList.add('selected');
                option.querySelector('.selected-icon')?.remove();
                const icon = document.createElement('i');
                icon.className = 'fas fa-check selected-icon';
                option.appendChild(icon);
            } else {
                option.classList.remove('selected');
                option.querySelector('.selected-icon')?.remove();
            }
        });
    }

    // Initialize all select search components on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectWrappers = document.querySelectorAll('.select-search-wrapper');
        selectWrappers.forEach(wrapper => {
            const selectId = wrapper.querySelector('[id]').id;
            initSelectSearch(selectId);
        });
    });
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
