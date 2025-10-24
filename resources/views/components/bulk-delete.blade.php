@props([
    'route' => '',
    'modelName' => 'éléments',
    'confirmMessage' => 'Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ?',
    'successMessage' => 'Éléments supprimés avec succès',
    'errorMessage' => 'Erreur lors de la suppression'
])

<div id="bulk-delete-section" class="bulk-delete-section" style="display: none;">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-trash text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-900">Suppression en lot</h4>
                    <p class="text-sm text-red-700">
                        <span id="selected-count">0</span> {{ $modelName }} sélectionné(s)
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" 
                        id="bulk-delete-btn"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-300"
                        data-route="{{ $route }}"
                        data-confirm-message="{{ $confirmMessage }}"
                        data-success-message="{{ $successMessage }}"
                        data-error-message="{{ $errorMessage }}">
                    <i class="fas fa-trash"></i>
                    <span>Supprimer sélectionnés</span>
                </button>
                <button type="button" 
                        id="clear-selection-btn"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bulkDeleteSection = document.getElementById('bulk-delete-section');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const clearSelectionBtn = document.getElementById('clear-selection-btn');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    // Update selected count and show/hide bulk delete section
    function updateBulkDeleteSection() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        
        selectedCountSpan.textContent = selectedCount;
        
        if (selectedCount > 0) {
            bulkDeleteSection.style.display = 'block';
        } else {
            bulkDeleteSection.style.display = 'none';
        }
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            if (selectedCount === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (selectedCount === rowCheckboxes.length) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
    }
    
    // Handle select all checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkDeleteSection();
        });
    }
    
    // Handle individual row checkboxes
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteSection);
    });
    
    // Handle clear selection
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            updateBulkDeleteSection();
        });
    }
    
    // Handle bulk delete
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Aucun élément sélectionné');
                return;
            }
            
            const confirmMessage = this.getAttribute('data-confirm-message');
            const successMessage = this.getAttribute('data-success-message');
            const errorMessage = this.getAttribute('data-error-message');
            const route = this.getAttribute('data-route');
            
            if (confirm(confirmMessage)) {
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
                this.disabled = true;
                
                // Create form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('_method', 'DELETE');
                formData.append('ids', JSON.stringify(selectedIds));
                
                // Send request
                fetch(route, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        if (window.notificationManager) {
                            window.notificationManager.success(successMessage);
                        } else {
                            alert(successMessage);
                        }
                        
                        // Reload page or remove rows
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Bulk delete error:', error);
                    if (window.notificationManager) {
                        window.notificationManager.error(errorMessage);
                    } else {
                        alert(errorMessage);
                    }
                    
                    // Reset button
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            }
        });
    }
});
</script>

<style>
.bulk-delete-section {
    animation: slideDown 0.3s ease-out;
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

.row-checkbox {
    transform: scale(1.1);
}

.row-checkbox:checked {
    background-color: #dc2626;
    border-color: #dc2626;
}

.select-all-checkbox {
    transform: scale(1.2);
}

.select-all-checkbox:checked {
    background-color: #dc2626;
    border-color: #dc2626;
}

.select-all-checkbox:indeterminate {
    background-color: #f59e0b;
    border-color: #f59e0b;
}
</style>
