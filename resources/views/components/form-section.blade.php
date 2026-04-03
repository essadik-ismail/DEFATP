{{--
    Form Section
    ============
    A titled section card used in multi-step forms and show pages.

    Props:
        title       (string)         – section heading
        icon        (string|null)    – Font Awesome class (e.g. "fas fa-info-circle")
        number      (string|int|null)– step badge rendered on top of the icon (optional)
        color       (string)         – accent: green | blue | purple | orange | red | yellow | gray
        columns     (int)            – grid columns for the body (0 or null = no auto grid)
        collapsible (bool)           – add Alpine toggle
        collapsed   (bool)           – start collapsed (requires collapsible=true)

    Backward-compatible: existing usages with columns="2" still work unchanged.
--}}

@props([
    'title',
    'icon'        => null,
    'number'      => null,
    'color'       => 'green',
    'columns'     => null,   // null = no auto grid; 1|2|3|4 = wrap slot in grid
    'collapsible' => false,
    'collapsed'   => false,
])

@php
    // Icon background colours
    $iconBg = match($color) {
        'blue'   => 'bg-blue-600',
        'purple' => 'bg-purple-600',
        'orange' => 'bg-orange-500',
        'red'    => 'bg-red-600',
        'yellow' => 'bg-amber-500',
        'gray'   => 'bg-gray-500',
        default  => 'bg-emerald-600', // green
    };

    // Border / bg tint for the whole card
    $cardBorder = match($color) {
        'blue'   => 'rgba(59,130,246,0.2)',
        'purple' => 'rgba(139,92,246,0.2)',
        'orange' => 'rgba(249,115,22,0.2)',
        'red'    => 'rgba(239,68,68,0.2)',
        'yellow' => 'rgba(245,158,11,0.2)',
        'gray'   => 'rgba(107,114,128,0.2)',
        default  => 'rgba(154,179,163,0.3)',
    };

    $gridClass = $columns ? "grid grid-cols-1 md:grid-cols-{$columns} gap-4" : null;
@endphp

<div
    class="rounded-xl border bg-gray-50/60 overflow-hidden mb-5"
    style="border-color: {{ $cardBorder }};"
    @if($collapsible) x-data="{ open: {{ $collapsed ? 'false' : 'true' }} }" @endif
    {{ $attributes->except(['class']) }}
>
    {{-- ── Section header ──────────────────────────────────────────── --}}
    <div
        class="flex items-center justify-between gap-3 px-5 py-3 border-b bg-white/60"
        style="border-color: {{ $cardBorder }};"
        @if($collapsible) @click="open = !open" role="button" @endif
        @if($collapsible) class="flex items-center justify-between gap-3 px-5 py-3 border-b bg-white/60 cursor-pointer select-none" @endif
    >
        <div class="flex items-center gap-3 min-w-0">
            @if($icon)
                <div class="relative flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $iconBg }}">
                        <i class="{{ $icon }} text-white text-xs"></i>
                    </div>
                    @if($number !== null)
                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-white border border-gray-200 text-[9px] font-bold text-gray-600 leading-none shadow-sm">
                            {{ $number }}
                        </span>
                    @endif
                </div>
            @endif
            <h3 class="text-sm font-semibold text-gray-800 leading-snug">
                @if($number !== null && !$icon)
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-200 text-gray-600 text-[10px] font-bold mr-1">{{ $number }}</span>
                @endif
                {{ $title }}
            </h3>
        </div>

        @if($collapsible)
            <i class="fas fa-chevron-down text-gray-400 text-xs flex-shrink-0 transition-transform duration-200"
               :class="{ 'rotate-180': open }"></i>
        @endif
    </div>

    {{-- ── Section body ─────────────────────────────────────────────── --}}
    @php $bodyTag = $collapsible ? '' : ''; @endphp
    @if($collapsible)
        <div x-show="open" x-transition class="p-5">
            @if($gridClass)
                <div class="{{ $gridClass }}">{{ $slot }}</div>
            @else
                {{ $slot }}
            @endif
        </div>
    @else
        <div class="p-5">
            @if($gridClass)
                <div class="{{ $gridClass }}">{{ $slot }}</div>
            @else
                {{ $slot }}
            @endif
        </div>
    @endif
</div>
