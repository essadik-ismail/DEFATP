@props([
    'title' => 'Filtres et Recherche',
    'icon' => 'fas fa-filter',
    'action' => null,
    'method' => 'GET',
    'formId' => 'filterForm'
])

<div class="rounded-2xl p-5 border mb-5 shadow-sm" style="background: #FFFFFF; border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold" style="color: #1F2D24;">
            <i class="{{ $icon }} mr-2" style="color: #2E5239;"></i>{{ $title }}
        </h2>
        <button type="button" onclick="clearFilters()" class="text-xs transition-colors" style="color: #6B7C72;">
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
