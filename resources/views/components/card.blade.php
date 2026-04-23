@props([
    'title'         => null,
    'subtitle'      => null,
    'icon'          => null,
    'headerActions' => null,
    'footer'        => null,
    'collapsible'   => false,
    'collapsed'     => false,
    'id'            => null,
    'noPadding'     => false,
    'flat'          => false,
])

@php
    $cardId      = $id ?? 'card-' . uniqid();
    $contentId   = $cardId . '-content';
    $toggleId    = $cardId . '-toggle';
    $cardClass   = 'card ' . ($flat ? 'card-flat' : '');
@endphp

<div {{ $attributes->merge(['class' => trim($cardClass)]) }}>

    @if($title || $subtitle || $headerActions || $icon || $collapsible)
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:0.625rem;min-width:0;">

                @if($collapsible)
                    <button type="button"
                            id="{{ $toggleId }}"
                            onclick="toggleCard('{{ $contentId }}', '{{ $toggleId }}')"
                            style="background:none;border:none;padding:0;cursor:pointer;color:#7A9B8A;font-size:0.625rem;transition:transform 0.25s;flex-shrink:0;{{ $collapsed ? 'transform:rotate(-90deg);' : '' }}"
                            aria-expanded="{{ $collapsed ? 'false' : 'true' }}"
                            aria-controls="{{ $contentId }}">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                @endif

                @if($icon)
                    <div class="card-icon">
                        <i class="{{ $icon }}"></i>
                    </div>
                @endif

                <div style="min-width:0;">
                    @if($title)
                        <h5 class="card-header-title">{{ $title }}</h5>
                    @endif
                    @if($subtitle)
                        <p class="card-header-subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>

            @if($headerActions)
                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif

    <div class="card-collapse-content {{ $collapsed ? 'collapsed' : '' }}"
         id="{{ $collapsible ? $contentId : '' }}">
        <div class="{{ $noPadding ? '' : 'card-body' }}">
            {{ $slot }}
        </div>
    </div>

    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif

</div>

@once
@push('scripts')
<script>
function toggleCard(contentId, toggleId) {
    const content = document.getElementById(contentId);
    const toggle  = document.getElementById(toggleId);
    if (!content || !toggle) return;
    const isCollapsed = content.classList.toggle('collapsed');
    toggle.setAttribute('aria-expanded', !isCollapsed);
    toggle.style.transform = isCollapsed ? 'rotate(-90deg)' : 'rotate(0deg)';
}
</script>
@endpush
@endonce
