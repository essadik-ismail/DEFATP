@props([
    'submitText' => 'Enregistrer',
    'cancelText' => 'Annuler',
    'cancelRoute' => null,
    'submitIcon' => 'fas fa-save',
    'cancelIcon' => 'fas fa-times',
    'gradient' => 'linear-gradient(135deg, #059669, #047857)',
    'showDivider' => true
])

<div class="flex items-center justify-end gap-4 {{ $showDivider ? 'pt-6 border-t border-green-200' : '' }}">
    @if($cancelRoute)
        <a href="{{ $cancelRoute }}" 
           class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
            <i class="{{ $cancelIcon }}"></i>
            <span>{{ $cancelText }}</span>
        </a>
    @endif
    
    <button type="submit" 
            class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
            style="background: {{ $gradient }};">
        <i class="{{ $submitIcon }}"></i>
        <span>{{ $submitText }}</span>
    </button>
    
    {{ $slot }}
</div>
