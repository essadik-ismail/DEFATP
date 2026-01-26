@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null
])

@php
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3',
        'lg' => 'px-8 py-4 text-lg',
    ];
    $base = 'inline-flex items-center gap-2 rounded-2xl font-semibold transition-all duration-300 ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    $variantClass = 'btn-theme-' . $variant;
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => $base . ' ' . $variantClass]) }}>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" 
            {{ $attributes->merge(['class' => $base . ' ' . $variantClass]) }}>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif

@push('styles')
<style>
    .btn-theme-primary { background: linear-gradient(135deg, #059669, #047857); color: #FFFFFF; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .btn-theme-primary:hover { background: linear-gradient(135deg, #047857, #035d42); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .btn-theme-secondary { background-color: #9AB3A3; color: #1F2D24; border-radius: 1rem; }
    .btn-theme-secondary:hover { background-color: #3E6A4B; color: #FFFFFF; }
    .btn-theme-success { background: linear-gradient(135deg, #059669, #047857); color: #FFFFFF; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .btn-theme-success:hover { background: linear-gradient(135deg, #047857, #035d42); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .btn-theme-danger { background-color: #1F2D24; color: #FFFFFF; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .btn-theme-danger:hover { opacity: 0.9; }
    .btn-theme-outline { background: transparent; color: #059669; border: 2px solid #059669; border-radius: 1rem; }
    .btn-theme-outline:hover { background: linear-gradient(135deg, #059669, #047857); color: #FFFFFF; }
</style>
@endpush
