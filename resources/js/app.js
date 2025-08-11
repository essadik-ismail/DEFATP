import './bootstrap';

// Enhanced DataTable and Filter Functionality
class EnhancedDataTable {
    constructor() {
        this.initializeEventListeners();
        this.initializeTooltips();
        this.initializeRealTimeSearch();
        this.initializeSortableHeaders();
    }

    initializeEventListeners() {
        // Filter toggle functionality
        const filterToggle = document.querySelector('[onclick="toggleFilters()"]');
        if (filterToggle) {
            filterToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleFilters();
            });
        }

        // Real-time search
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 500));
        }

        // Form submission with loading state
        const filterForm = document.querySelector('form[method="GET"]');
        if (filterForm) {
            filterForm.addEventListener('submit', this.handleFormSubmit.bind(this));
        }

        // File upload enhancement
        const fileInput = document.querySelector('input[type="file"]');
        if (fileInput) {
            fileInput.addEventListener('change', this.handleFileUpload.bind(this));
        }

        // Export button enhancement
        const exportButton = document.querySelector('a[href*="export"]');
        if (exportButton) {
            exportButton.addEventListener('click', this.handleExport.bind(this));
        }
    }

    initializeTooltips() {
        // Initialize tooltips for action buttons
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            this.createTooltip(element);
        });
    }

    initializeRealTimeSearch() {
        // Add search suggestions if needed
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('focus', this.showSearchSuggestions.bind(this));
            searchInput.addEventListener('blur', this.hideSearchSuggestions.bind(this));
        }
    }

    initializeSortableHeaders() {
        // Add sort indicators to table headers
        const sortableHeaders = document.querySelectorAll('th[onclick*="sortTable"]');
        sortableHeaders.forEach(header => {
            this.addSortIndicator(header);
        });
    }

    toggleFilters() {
        const filterSection = document.getElementById('filterSection');
        if (filterSection) {
            filterSection.classList.toggle('hidden');
            
            // Add smooth animation
            if (!filterSection.classList.contains('hidden')) {
                filterSection.style.opacity = '0';
                filterSection.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    filterSection.style.transition = 'all 0.3s ease-out';
                    filterSection.style.opacity = '1';
                    filterSection.style.transform = 'translateY(0)';
                }, 10);
            }
        }
    }

    handleSearch(event) {
        const searchTerm = event.target.value;
        const searchResults = this.performSearch(searchTerm);
        this.updateSearchResults(searchResults);
    }

    performSearch(term) {
        // Implement client-side search if needed
        // For now, we'll let the server handle it
        return [];
    }

    updateSearchResults(results) {
        // Update search results display
        const resultsContainer = document.querySelector('.search-results');
        if (resultsContainer) {
            // Update results display
        }
    }

    handleFormSubmit(event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (submitButton) {
            // Add loading state
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Application des filtres...
            `;
            submitButton.disabled = true;
            
            // Re-enable after a delay (in case of errors)
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        }
    }

    handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            this.updateFileName(file.name);
            this.validateFile(file);
            this.showUploadPreview(file);
        }
    }

    updateFileName(fileName) {
        const fileNameElement = document.getElementById('fileName');
        if (fileNameElement) {
            fileNameElement.textContent = fileName;
        }
    }

    validateFile(file) {
        const allowedTypes = ['.xlsx', '.xls', '.csv'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            this.showError('Type de fichier non supporté. Veuillez utiliser .xlsx, .xls ou .csv');
            return false;
        }
        
        if (file.size > 10 * 1024 * 1024) { // 10MB
            this.showError('Le fichier est trop volumineux. Taille maximale: 10MB');
            return false;
        }
        
        return true;
    }

    showUploadPreview(file) {
        // Create preview element
        const preview = document.createElement('div');
        preview.className = 'mt-3 p-3 bg-green-50 border border-green-200 rounded-lg';
        preview.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm text-green-800">Fichier sélectionné: ${file.name}</span>
            </div>
        `;
        
        // Insert preview
        const fileInput = document.querySelector('input[type="file"]');
        if (fileInput) {
            fileInput.parentNode.appendChild(preview);
        }
    }

    handleExport(event) {
        const exportButton = event.target;
        const originalText = exportButton.innerHTML;
        
        // Add loading state
        exportButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Export en cours...
        `;
        exportButton.disabled = true;
        
        // Re-enable after a delay
        setTimeout(() => {
            exportButton.innerHTML = originalText;
            exportButton.disabled = false;
        }, 5000);
    }

    createTooltip(element) {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg opacity-0 pointer-events-none transition-opacity duration-200';
        tooltip.textContent = element.getAttribute('title');
        
        element.style.position = 'relative';
        element.appendChild(tooltip);
        
        element.addEventListener('mouseenter', () => {
            tooltip.style.opacity = '1';
        });
        
        element.addEventListener('mouseleave', () => {
            tooltip.style.opacity = '0';
        });
    }

    addSortIndicator(header) {
        const indicator = document.createElement('span');
        indicator.className = 'ml-1 text-gray-400';
        indicator.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
            </svg>
        `;
        
        header.appendChild(indicator);
    }

    showError(message) {
        // Create error notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ${message}
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    debounce(func, wait) {
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
}

// Enhanced Table Sorting
class TableSorter {
    static sortTable(field) {
        const currentSort = this.getCurrentSort();
        const currentDirection = this.getCurrentDirection();
        
        let newDirection = 'asc';
        if (currentSort === field && currentDirection === 'asc') {
            newDirection = 'desc';
        }
        
        this.updateURL(field, newDirection);
        this.updateSortIndicators(field, newDirection);
    }

    static getCurrentSort() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('sort') || 'nature_de_coupe';
    }

    static getCurrentDirection() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('direction') || 'asc';
    }

    static updateURL(field, direction) {
        const url = new URL(window.location);
        url.searchParams.set('sort', field);
        url.searchParams.set('direction', direction);
        window.location.href = url.toString();
    }

    static updateSortIndicators(field, direction) {
        // Update visual indicators
        const headers = document.querySelectorAll('th[onclick*="sortTable"]');
        headers.forEach(header => {
            const indicator = header.querySelector('svg');
            if (indicator) {
                if (header.onclick.toString().includes(field)) {
                    indicator.style.transform = direction === 'desc' ? 'rotate(180deg)' : 'rotate(0deg)';
                    indicator.style.color = '#3B82F6'; // Blue color for active sort
                } else {
                    indicator.style.transform = 'rotate(0deg)';
                    indicator.style.color = '#9CA3AF'; // Gray color for inactive
                }
            }
        });
    }
}

// Enhanced Pagination
class PaginationEnhancer {
    static enhancePagination() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', this.handlePaginationClick.bind(this));
        });
    }

    static handlePaginationClick(event) {
        const link = event.target.closest('a');
        if (link) {
            // Add loading state to the page
            this.showPageLoading();
        }
    }

    static showPageLoading() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50';
        loadingOverlay.innerHTML = `
            <div class="text-center">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-lg text-gray-700">Chargement de la page...</p>
            </div>
        `;
        
        document.body.appendChild(loadingOverlay);
    }
}

// Enhanced Form Validation
class FormValidator {
    static validateForm(form) {
        const inputs = form.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showFieldError(input, 'Ce champ est requis');
                isValid = false;
            } else {
                this.clearFieldError(input);
            }
        });
        
        return isValid;
    }

    static showFieldError(input, message) {
        this.clearFieldError(input);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-1 text-sm text-red-600';
        errorDiv.textContent = message;
        
        input.parentNode.appendChild(errorDiv);
        input.classList.add('border-red-500');
    }

    static clearFieldError(input) {
        const errorDiv = input.parentNode.querySelector('.text-red-600');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('border-red-500');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize enhanced data table
    new EnhancedDataTable();
    
    // Enhance pagination
    PaginationEnhancer.enhancePagination();
    
    // Initialize form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!FormValidator.validateForm(form)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide notifications
    const notifications = document.querySelectorAll('#successMessage, #errorMessage');
    notifications.forEach(notification => {
        if (notification) {
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    });
    
    // Initialize sort indicators
    TableSorter.updateSortIndicators(
        TableSorter.getCurrentSort(),
        TableSorter.getCurrentDirection()
    );
});

// Global functions for backward compatibility
window.toggleFilters = function() {
    const filterSection = document.getElementById('filterSection');
    if (filterSection) {
        filterSection.classList.toggle('hidden');
    }
};

window.updateFileName = function(input) {
    const fileName = input.files[0]?.name || 'Cliquez pour sélectionner un fichier';
    const fileNameElement = document.getElementById('fileName');
    if (fileNameElement) {
        fileNameElement.textContent = fileName;
    }
};

window.sortTable = function(field) {
    TableSorter.sortTable(field);
};

window.refreshTable = function() {
    window.location.reload();
};

window.editNature = function(id, nature) {
    // Implement edit functionality
    console.log('Edit nature:', id, nature);
    // You can implement a modal or redirect to edit page
};

window.deleteNature = function(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette nature de coupe ?')) {
        // Implement delete functionality
        console.log('Delete nature:', id);
        // You can implement AJAX delete or redirect to delete route
    }
};
