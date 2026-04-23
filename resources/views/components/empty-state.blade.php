@props([
    'icon'    => 'fas fa-inbox',
    'title'   => 'Aucune donnée',
    'message' => null,
    'color'   => 'default',  // default | green | blue | warning | danger
])

@php
    $iconBg = match($color) {
        'green'   => 'background:#E8F7EF;color:#2D7A54;border-color:#B3E6CA;',
        'blue'    => 'background:#EFF6FF;color:#2563EB;border-color:#BFDBFE;',
        'warning' => 'background:#FFFBEB;color:#D97706;border-color:#FDE68A;',
        'danger'  => 'background:#FFF5F5;color:#DC2626;border-color:#FED7D7;',
        default   => 'background:#F3F6F5;color:#9DB8AE;border-color:#E4EDE8;',
    };
    $titleColor = match($color) {
        'green'   => '#163326',
        'blue'    => '#1E3A8A',
        'warning' => '#78350F',
        'danger'  => '#7F1D1D',
        default   => '#0F1F18',
    };
@endphp

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state-icon" style="{{ $iconBg }}">
        <i class="{{ $icon }}"></i>
    </div>
    <h3 class="empty-state-title" style="color:{{ $titleColor }};">{{ $title }}</h3>
    @if($message)
        <p class="empty-state-text">{{ $message }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div style="margin-top:0.5rem;">
            {{ $slot }}
        </div>
    @endif
</div>
