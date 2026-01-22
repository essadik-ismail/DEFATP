@props([
    'title' => 'Filtres et Recherche',
    'icon' => 'fas fa-filter',
    'action' => null,
    'method' => 'GET',
    'formId' => 'filterForm'
])

<div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold" style="color: #059669;">
            <i class="{{ $icon }} mr-2"></i>{{ $title }}
        </h2>
        <button type="button" onclick="clearFilters()" class="text-sm text-gray-600 hover:text-gray-900">
            <i class="fas fa-times-circle mr-1"></i>Effacer les filtres
        </button>
    </div>
    
    <form method="{{ $method }}" action="{{ $action }}" id="{{ $formId }}" {{ $attributes }}>
        @if($method !== 'GET')
            @csrf
        @endif
        {{ $slot }}
    </form>
</div>

@push('scripts')
<script>
function clearFilters() {
    const form = document.getElementById('{{ $formId }}');
    if (form) {
        form.reset();
        // Also clear select2 if being used
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            jQuery(form).find('select').val(null).trigger('change');
        }
        form.submit();
    }
}
</script>
@endpush
