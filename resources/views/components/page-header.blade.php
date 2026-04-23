@props([
    'title',
    'subtitle'  => null,
    'icon'      => 'fas fa-file',
    'backRoute' => null,
    'backText'  => 'Retour',
    'actions'   => null,
    'divider'   => true,
])

<div class="ph-root">
    <div class="ph-inner">

        {{-- Left: icon + title --}}
        <div class="ph-left">
            <div class="ph-icon" aria-hidden="true">
                <i class="{{ $icon }}"></i>
            </div>
            <div>
                <h1 class="ph-title">{{ $title }}</h1>
                @if($subtitle)
                    <p class="ph-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        {{-- Right: actions + back button --}}
        @if($actions || $backRoute)
            <div class="ph-actions">
                @if($actions)
                    {{ $actions }}
                @endif
                @if($backRoute)
                    <a href="{{ $backRoute }}" class="btn-secondary btn-sm">
                        <i class="fas fa-arrow-left" style="font-size:0.625rem;"></i>
                        {{ $backText }}
                    </a>
                @endif
            </div>
        @endif

    </div>

    @if($divider)
        <div class="ph-divider"></div>
    @endif
</div>
