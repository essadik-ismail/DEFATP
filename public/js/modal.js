/**
 * Global Modal Management System
 * Provides reusable modal functionality across the entire project
 */

class ModalManager {
    constructor() {
        this.init();
    }

    init() {
        // Close modals on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }

    /**
     * Open a modal by ID
     * @param {string} modalId - The ID of the modal to open
     */
    open(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
            // Trigger custom event
            modal.dispatchEvent(new CustomEvent('modal:opened', { detail: { modalId } }));
        }
    }

    /**
     * Close a modal by ID
     * @param {string} modalId - The ID of the modal to close
     */
    close(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore body scroll
            // Trigger custom event
            modal.dispatchEvent(new CustomEvent('modal:closed', { detail: { modalId } }));
        }
    }

    /**
     * Toggle a modal (open if closed, close if open)
     * @param {string} modalId - The ID of the modal to toggle
     */
    toggle(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (modal.classList.contains('hidden')) {
                this.open(modalId);
            } else {
                this.close(modalId);
            }
        }
    }

    /**
     * Close all open modals
     */
    closeAllModals() {
        const openModals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
        openModals.forEach(modal => {
            if (modal.id && modal.id.includes('modal')) {
                this.close(modal.id);
            }
        });
    }

    /**
     * Check if a modal is open
     * @param {string} modalId - The ID of the modal to check
     * @returns {boolean}
     */
    isOpen(modalId) {
        const modal = document.getElementById(modalId);
        return modal && !modal.classList.contains('hidden');
    }
}

// Create global instance
window.ModalManager = new ModalManager();

// Global convenience functions - define immediately
window.openModal = function(modalId) {
    if (window.ModalManager) {
        window.ModalManager.open(modalId);
    } else {
        // Fallback if ModalManager not loaded yet
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
};

window.closeModal = function(modalId) {
    if (window.ModalManager) {
        window.ModalManager.close(modalId);
    } else {
        // Fallback if ModalManager not loaded yet
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
};

window.toggleModal = function(modalId) {
    if (window.ModalManager) {
        window.ModalManager.toggle(modalId);
    } else {
        // Fallback if ModalManager not loaded yet
        const modal = document.getElementById(modalId);
        if (modal) {
            if (modal.classList.contains('hidden')) {
                window.openModal(modalId);
            } else {
                window.closeModal(modalId);
            }
        }
    }
};

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.ModalManager.init();
    });
} else {
    window.ModalManager.init();
}

