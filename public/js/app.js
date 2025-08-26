// Enhanced Notification System
class NotificationManager {
    constructor() {
        this.container = this.createContainer();
        this.notifications = new Map();
        this.counter = 0;
    }

    createContainer() {
        const container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
        return container;
    }

    show(type, title, message, duration = 5000) {
        const id = ++this.counter;
        const notification = this.createNotification(type, title, message, id);
        
        this.container.appendChild(notification);
        this.notifications.set(id, notification);

        // Auto-remove after duration
        setTimeout(() => {
            this.remove(id);
        }, duration);

        return id;
    }

    createNotification(type, title, message, id) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-header">
                <span class="notification-title">${title}</span>
                <button class="notification-close" onclick="window.notificationManager.remove(${id})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="notification-message">${message}</div>
        `;

        return notification;
    }

    remove(id) {
        const notification = this.notifications.get(id);
        if (notification) {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
                this.notifications.delete(id);
            }, 300);
        }
    }

    success(message, title = 'Succès') {
        return this.show('success', title, message);
    }

    error(message, title = 'Erreur') {
        return this.show('error', title, message);
    }

    warning(message, title = 'Attention') {
        return this.show('warning', title, message);
    }

    info(message, title = 'Information') {
        return this.show('info', title, message);
    }
}

// Confirmation Dialog System
class ConfirmationDialog {
    static async show(title, message, confirmText = 'Confirmer', cancelText = 'Annuler') {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.className = 'confirmation-overlay';
            overlay.innerHTML = `
                <div class="confirmation-dialog">
                    <div class="confirmation-title">${title}</div>
                    <div class="confirmation-message">${message}</div>
                    <div class="confirmation-actions">
                        <button class="btn btn-secondary" id="cancel-btn">${cancelText}</button>
                        <button class="btn btn-danger" id="confirm-btn">${confirmText}</button>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);

            const confirmBtn = overlay.querySelector('#confirm-btn');
            const cancelBtn = overlay.querySelector('#cancel-btn');

            const cleanup = () => {
                document.body.removeChild(overlay);
            };

            confirmBtn.addEventListener('click', () => {
                cleanup();
                resolve(true);
            });

            cancelBtn.addEventListener('click', () => {
                cleanup();
                resolve(false);
            });

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    cleanup();
                    resolve(false);
                }
            });
        });
    }
}

// Enhanced Form Validation
class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = new Map();
        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validate()) {
                e.preventDefault();
                this.showErrors();
            }
        });

        // Real-time validation
        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });

            field.addEventListener('input', () => {
                this.clearFieldError(field);
            });
        });
    }

    validate() {
        this.errors.clear();
        let isValid = true;

        this.form.querySelectorAll('[required]').forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const rules = this.getFieldRules(field);
        let isValid = true;

        // Required validation
        if (rules.required && !value) {
            this.addFieldError(field, 'Ce champ est requis');
            isValid = false;
        }

        // Email validation
        if (rules.email && value && !this.isValidEmail(value)) {
            this.addFieldError(field, 'Format d\'email invalide');
            isValid = false;
        }

        // Min length validation
        if (rules.minLength && value && value.length < rules.minLength) {
            this.addFieldError(field, `Minimum ${rules.minLength} caractères requis`);
            isValid = false;
        }

        // Number validation
        if (rules.number && value && isNaN(value)) {
            this.addFieldError(field, 'Ce champ doit être un nombre');
            isValid = false;
        }

        return isValid;
    }

    getFieldRules(field) {
        const rules = {};
        
        if (field.hasAttribute('required')) rules.required = true;
        if (field.type === 'email') rules.email = true;
        if (field.hasAttribute('minlength')) rules.minLength = parseInt(field.getAttribute('minlength'));
        if (field.hasAttribute('data-number')) rules.number = true;

        return rules;
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    addFieldError(field, message) {
        this.errors.set(field, message);
        field.classList.add('border-red-500');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        errorDiv.id = `error-${field.id || field.name}`;
        
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        this.errors.delete(field);
        field.classList.remove('border-red-500');
        
        const errorDiv = field.parentNode.querySelector(`#error-${field.id || field.name}`);
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    showErrors() {
        if (this.errors.size > 0) {
            window.notificationManager.error(
                'Veuillez corriger les erreurs dans le formulaire',
                'Erreur de validation'
            );
        }
    }
}

// Form Wizard System
class FormWizard {
    constructor(container, steps) {
        this.container = container;
        this.steps = steps;
        this.currentStep = 0;
        this.init();
    }

    init() {
        this.createProgressBar();
        this.showStep(0);
        this.updateNavigation();
    }

    createProgressBar() {
        const progressBar = document.createElement('div');
        progressBar.className = 'wizard-progress mb-6';
        
        this.steps.forEach((step, index) => {
            const indicator = document.createElement('div');
            indicator.className = 'wizard-step-indicator pending';
            indicator.textContent = index + 1;
            indicator.dataset.step = index;
            progressBar.appendChild(indicator);
        });

        this.container.insertBefore(progressBar, this.container.firstChild);
    }

    showStep(stepIndex) {
        // Hide all steps
        this.container.querySelectorAll('.wizard-step').forEach((step, index) => {
            step.classList.remove('active');
            if (index === stepIndex) {
                step.classList.add('active');
            }
        });

        // Update progress indicators
        this.container.querySelectorAll('.wizard-step-indicator').forEach((indicator, index) => {
            indicator.className = 'wizard-step-indicator';
            if (index < stepIndex) {
                indicator.classList.add('completed');
            } else if (index === stepIndex) {
                indicator.classList.add('active');
            } else {
                indicator.classList.add('pending');
            }
        });

        this.currentStep = stepIndex;
        this.updateNavigation();
    }

    nextStep() {
        if (this.currentStep < this.steps.length - 1) {
            this.showStep(this.currentStep + 1);
        }
    }

    previousStep() {
        if (this.currentStep > 0) {
            this.showStep(this.currentStep - 1);
        }
    }

    updateNavigation() {
        const navigation = this.container.querySelector('.wizard-navigation');
        if (!navigation) return;

        const prevBtn = navigation.querySelector('.btn-previous');
        const nextBtn = navigation.querySelector('.btn-next');
        const submitBtn = navigation.querySelector('.btn-submit');

        if (prevBtn) {
            prevBtn.disabled = this.currentStep === 0;
        }

        if (nextBtn) {
            nextBtn.style.display = this.currentStep === this.steps.length - 1 ? 'none' : 'inline-flex';
        }

        if (submitBtn) {
            submitBtn.style.display = this.currentStep === this.steps.length - 1 ? 'inline-flex' : 'none';
        }
    }
}

// Enhanced Data Table
class EnhancedDataTable {
    constructor(table, options = {}) {
        this.table = table;
        this.options = {
            searchable: true,
            sortable: true,
            exportable: true,
            pagination: true,
            ...options
        };
        
        this.init();
    }

    init() {
        if (this.options.searchable) {
            this.addSearch();
        }
        
        if (this.options.sortable) {
            this.addSorting();
        }
        
        if (this.options.exportable) {
            this.addExport();
        }
    }

    addSearch() {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'mb-4';
        searchContainer.innerHTML = `
            <div class="relative">
                <input type="text" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       placeholder="Rechercher...">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        `;

        const searchInput = searchContainer.querySelector('input');
        searchInput.addEventListener('input', (e) => {
            this.filterTable(e.target.value);
        });

        this.table.parentNode.insertBefore(searchContainer, this.table);
    }

    filterTable(searchTerm) {
        const rows = this.table.querySelectorAll('tbody tr');
        const term = searchTerm.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    addSorting() {
        const headers = this.table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(header.dataset.sort);
            });
        });
    }

    sortTable(column) {
        // Implementation for sorting
        console.log(`Sorting by column: ${column}`);
    }

    addExport() {
        const exportContainer = document.createElement('div');
        exportContainer.className = 'mb-4 flex justify-end';
        exportContainer.innerHTML = `
            <button class="btn btn-outline" onclick="this.exportTable()">
                <i class="fas fa-download me-2"></i>Exporter
            </button>
        `;

        this.table.parentNode.insertBefore(exportContainer, this.table);
    }

    exportTable() {
        // Implementation for export
        console.log('Exporting table...');
    }
}

// Loading State Manager
class LoadingManager {
    static show(element, text = 'Chargement...') {
        element.classList.add('loading');
        
        const spinner = document.createElement('div');
        spinner.className = 'loading-spinner';
        spinner.innerHTML = `
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-sm text-gray-600">${text}</span>
            </div>
        `;
        
        element.appendChild(spinner);
    }

    static hide(element) {
        element.classList.remove('loading');
        const spinner = element.querySelector('.loading-spinner');
        if (spinner) {
            spinner.remove();
        }
    }
}

// Tooltip Manager
class TooltipManager {
    constructor() {
        this.init();
    }

    init() {
        document.addEventListener('mouseover', (e) => {
            const element = e.target;
            if (element.hasAttribute('data-tooltip')) {
                this.showTooltip(element, element.getAttribute('data-tooltip'));
            }
        });

        document.addEventListener('mouseout', (e) => {
            const element = e.target;
            if (element.hasAttribute('data-tooltip')) {
                this.hideTooltip();
            }
        });
    }

    showTooltip(element, text) {
        this.hideTooltip();
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = text;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
        
        this.currentTooltip = tooltip;
    }

    hideTooltip() {
        if (this.currentTooltip) {
            this.currentTooltip.remove();
            this.currentTooltip = null;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification manager
    window.notificationManager = new NotificationManager();
    
    // Initialize tooltip manager
    window.tooltipManager = new TooltipManager();
    
    // Initialize form validators
    document.querySelectorAll('form').forEach(form => {
        new FormValidator(form);
    });
    
    // Initialize enhanced data tables
    document.querySelectorAll('.data-table').forEach(table => {
        new EnhancedDataTable(table);
    });
    
    // Initialize form wizards
    document.querySelectorAll('.form-wizard').forEach(wizard => {
        const steps = Array.from(wizard.querySelectorAll('.wizard-step'));
        new FormWizard(wizard, steps);
    });
    
    // Add confirmation for delete buttons
    document.querySelectorAll('[data-confirm]').forEach(button => {
        button.addEventListener('click', async (e) => {
            const message = button.getAttribute('data-confirm');
            const confirmed = await ConfirmationDialog.show(
                'Confirmation',
                message,
                'Supprimer',
                'Annuler'
            );
            
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
    
    // Add loading states for forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                LoadingManager.show(submitBtn, 'Envoi en cours...');
            }
        });
    });
    
    // Show success messages from session
    if (window.successMessage) {
        window.notificationManager.success(window.successMessage);
    }
    
    // Show error messages from session
    if (window.errorMessage) {
        window.notificationManager.error(window.errorMessage);
    }
    
    // Add mobile menu toggle
    const mobileMenuToggle = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Export classes for global use
window.NotificationManager = NotificationManager;
window.ConfirmationDialog = ConfirmationDialog;
window.FormValidator = FormValidator;
window.FormWizard = FormWizard;
window.EnhancedDataTable = EnhancedDataTable;
window.LoadingManager = LoadingManager;
window.TooltipManager = TooltipManager;
