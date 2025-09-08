// Optimized JavaScript for better performance

// Debounce utility for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle utility for performance
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Optimized form validation
const FormValidator = {
    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';

        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Ce champ est requis';
        }
        
        // Email validation
        if (fieldName === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            isValid = false;
            errorMessage = 'Format d\'email invalide';
        }
        
        // Phone validation
        if (fieldName === 'telephone' && value && !/^[0-9+\-\s()]+$/.test(value)) {
            isValid = false;
            errorMessage = 'Format de téléphone invalide';
        }
        
        // CIN validation
        if (fieldName === 'n_cin' && value && !/^[A-Z]{1,2}[0-9]{6}$/.test(value)) {
            isValid = false;
            errorMessage = 'Format CIN invalide (ex: A123456)';
        }

        // Apply validation classes
        field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        
        return isValid;
    },

    validateForm(form) {
        const fields = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
};

// Optimized AJAX form submission
const FormSubmitter = {
    async submitForm(form, options = {}) {
        const {
            showLoading = true,
            showSuccess = true,
            showError = true,
            onSuccess = null,
            onError = null
        } = options;

        if (!FormValidator.validateForm(form)) {
            if (showError) {
                this.showMessage('Veuillez corriger les erreurs dans le formulaire', 'error');
            }
            return false;
        }

        if (showLoading) {
            this.setLoading(form, true);
        }

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                if (showSuccess) {
                    this.showMessage(data.message || 'Opération réussie', 'success');
                }
                if (onSuccess) {
                    onSuccess(data);
                }
                return true;
            } else {
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                } else if (showError) {
                    this.showMessage(data.message || 'Erreur lors de l\'opération', 'error');
                }
                if (onError) {
                    onError(data);
                }
                return false;
            }
        } catch (error) {
            console.error('Form submission error:', error);
            if (showError) {
                this.showMessage('Erreur de connexion', 'error');
            }
            if (onError) {
                onError(error);
            }
            return false;
        } finally {
            if (showLoading) {
                this.setLoading(form, false);
            }
        }
    },

    setLoading(form, isLoading) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = isLoading;
            if (isLoading) {
                submitBtn.classList.add('loading');
            } else {
                submitBtn.classList.remove('loading');
            }
        }
    },

    showMessage(message, type = 'info') {
        if (typeof UXUtils !== 'undefined' && UXUtils.showToast) {
            UXUtils.showToast(message, type);
        } else {
            alert(message);
        }
    },

    showValidationErrors(errors) {
        let errorMessage = 'Erreurs de validation:\n';
        for (const field in errors) {
            errorMessage += `• ${errors[field].join(', ')}\n`;
        }
        this.showMessage(errorMessage, 'error');
    }
};

// Optimized table functionality
const TableManager = {
    init() {
        this.addRowHoverEffects();
        this.addSorting();
        this.addFiltering();
    },

    addRowHoverEffects() {
        const tables = document.querySelectorAll('.table tbody tr');
        tables.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = '';
            });
        });
    },

    addSorting() {
        const headers = document.querySelectorAll('.table th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(header);
            });
        });
    },

    sortTable(header) {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const column = header.dataset.sort;
        const isAscending = header.classList.contains('sort-asc');

        rows.sort((a, b) => {
            const aVal = a.querySelector(`td:nth-child(${header.cellIndex + 1})`).textContent.trim();
            const bVal = b.querySelector(`td:nth-child(${header.cellIndex + 1})`).textContent.trim();
            
            if (isAscending) {
                return bVal.localeCompare(aVal);
            } else {
                return aVal.localeCompare(bVal);
            }
        });

        // Clear tbody and append sorted rows
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));

        // Update header classes
        document.querySelectorAll('.table th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
    },

    addFiltering() {
        const searchInputs = document.querySelectorAll('input[data-filter]');
        searchInputs.forEach(input => {
            const debouncedFilter = debounce(() => {
                this.filterTable(input);
            }, 300);
            
            input.addEventListener('input', debouncedFilter);
        });
    },

    filterTable(input) {
        const filterValue = input.value.toLowerCase();
        const table = input.closest('.card-body').querySelector('.table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const shouldShow = text.includes(filterValue);
            row.style.display = shouldShow ? '' : 'none';
        });
    }
};

// Optimized pagination
const PaginationManager = {
    init() {
        this.addPerPageSelector();
        this.addKeyboardNavigation();
    },

    addPerPageSelector() {
        const selectors = document.querySelectorAll('select[id*="perPage"]');
        selectors.forEach(selector => {
            selector.addEventListener('change', (e) => {
                this.changePerPage(e.target.value);
            });
        });
    },

    changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    },

    addKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
                return;
            }

            if (e.ctrlKey || e.metaKey) {
                if (e.key === 'ArrowLeft') {
                    this.goToPreviousPage();
                } else if (e.key === 'ArrowRight') {
                    this.goToNextPage();
                }
            }
        });
    },

    goToPreviousPage() {
        const prevLink = document.querySelector('.pagination .page-item:not(.disabled) .page-link[rel="prev"]');
        if (prevLink) {
            window.location.href = prevLink.href;
        }
    },

    goToNextPage() {
        const nextLink = document.querySelector('.pagination .page-item:not(.disabled) .page-link[rel="next"]');
        if (nextLink) {
            window.location.href = nextLink.href;
        }
    }
};

// Performance monitoring
const PerformanceMonitor = {
    startTime: null,
    
    start() {
        this.startTime = performance.now();
    },
    
    end(operation) {
        if (this.startTime) {
            const duration = performance.now() - this.startTime;
            console.log(`${operation} took ${duration.toFixed(2)}ms`);
        }
    },
    
    measure(callback, operation) {
        this.start();
        const result = callback();
        this.end(operation);
        return result;
    }
};

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    PerformanceMonitor.start();
    
    // Initialize components
    TableManager.init();
    PaginationManager.init();
    
    // Add form validation to all forms
    const forms = document.querySelectorAll('form[data-ajax="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            FormSubmitter.submitForm(form);
        });
    });
    
    PerformanceMonitor.end('DOM initialization');
});

// Export for global use
window.FormValidator = FormValidator;
window.FormSubmitter = FormSubmitter;
window.TableManager = TableManager;
window.PaginationManager = PaginationManager;
window.PerformanceMonitor = PerformanceMonitor;
