@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // xs, sm, md, lg, xl, full
    'closeButton' => true,
    'backdrop' => true,
    'backdropClose' => true,
    'escapeClose' => true,
])

@php
    $sizeClasses = [
        'xs' => 'max-w-xs',
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-2xl',
        'full' => 'max-w-full mx-4',
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<!-- Modal Backdrop -->
<div id="{{ $id }}" 
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4" 
     style="backdrop-filter: blur(2px);"
     @if($backdropClose) onclick="if(event.target === this && typeof window.closeModal === 'function') window.closeModal('{{ $id }}')" @endif>
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl shadow-2xl w-full {{ $sizeClass }} relative" 
         onclick="event.stopPropagation()">
        @if($title || $closeButton)
        <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
            @if($title)
            <h3 class="text-2xl font-bold text-gray-900">{{ $title }}</h3>
            @else
            <div></div>
            @endif
            @if($closeButton)
            <button type="button" 
                    onclick="if(typeof window.closeModal === 'function') window.closeModal('{{ $id }}')" 
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
            @endif
        </div>
        @endif
        
        <!-- Modal Body -->
        <div class="p-6 {{ $title || $closeButton ? '' : 'pt-6' }}">
            {{ $slot }}
        </div>
        
        @if(isset($footer))
        <!-- Modal Footer -->
        <div class="flex items-center gap-3 p-6 border-t border-gray-200 flex-shrink-0">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>

