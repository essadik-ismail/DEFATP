{{-- 
    MODAL COMPONENT USAGE EXAMPLES
    
    This file demonstrates how to use the reusable modal component throughout the project.
--}}

{{-- 
    BASIC MODAL EXAMPLE
    Usage: Simple modal with title and content
--}}
<x-modal id="basicModal" title="Basic Modal">
    <p>This is a basic modal with a title and content.</p>
    
    <x-slot name="footer">
        <button type="button" 
                onclick="closeModal('basicModal')" 
                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
            Close
        </button>
    </x-slot>
</x-modal>

{{-- 
    MODAL WITH DIFFERENT SIZES
    Available sizes: xs, sm, md, lg, xl, full
--}}
<x-modal id="smallModal" title="Small Modal" size="sm">
    <p>This is a small modal.</p>
</x-modal>

<x-modal id="largeModal" title="Large Modal" size="xl">
    <p>This is a large modal with more space.</p>
</x-modal>

{{-- 
    MODAL WITHOUT TITLE
--}}
<x-modal id="noTitleModal" size="md">
    <p>This modal has no title.</p>
</x-slot>

{{-- 
    MODAL WITHOUT CLOSE BUTTON
--}}
<x-modal id="noCloseModal" title="No Close Button" :closeButton="false">
    <p>This modal cannot be closed with the X button.</p>
</x-modal>

{{-- 
    MODAL WITH CUSTOM CONTENT
--}}
<x-modal id="customModal" title="Custom Content" size="lg">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-2">Name</label>
            <input type="text" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input type="email" class="w-full px-4 py-2 border rounded-lg">
        </div>
    </div>
    
    <x-slot name="footer">
        <button type="button" 
                onclick="closeModal('customModal')" 
                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
            Cancel
        </button>
        <button type="button" 
                onclick="saveData()" 
                class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
            Save
        </button>
    </x-slot>
</x-modal>

{{-- 
    JAVASCRIPT USAGE
    
    // Open a modal
    openModal('modalId');
    
    // Close a modal
    closeModal('modalId');
    
    // Toggle a modal
    toggleModal('modalId');
    
    // Check if modal is open
    ModalManager.isOpen('modalId');
    
    // Close all modals
    ModalManager.closeAllModals();
--}}

