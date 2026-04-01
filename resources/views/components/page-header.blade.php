@props([
    'title',
    'subtitle' => null,
    'icon' => 'fas fa-file',
    'backRoute' => null,
    'backText' => 'Retour',
    'actions' => null,
])

<div class="ph-root mb-6">
    <div class="ph-inner">
        <!-- Left: icon + text -->
        <div class="ph-left">
            <div class="ph-icon-wrap">
                <i class="{{ $icon }}"></i>
            </div>
            <div class="ph-text">
                <h1 class="ph-title">{{ $title }}</h1>
                @if($subtitle)
                    <p class="ph-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        <!-- Right: action buttons -->
        @if($actions || $backRoute)
        <div class="ph-actions">
            @if($actions)
                {{ $actions }}
            @endif
            @if($backRoute)
                <a href="{{ $backRoute }}" class="ph-btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ $backText }}</span>
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

@once
@push('styles')
<style>
.ph-root {
    padding-bottom: 1.25rem;
    border-bottom: 1px solid rgba(154,179,163,0.22);
    margin-bottom: 1.5rem;
}
.ph-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.ph-left {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    min-width: 0;
}
.ph-icon-wrap {
    flex-shrink: 0;
    width: 2.625rem;
    height: 2.625rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #059669, #047857);
    box-shadow: 0 3px 10px rgba(5,150,105,0.28);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
}
.ph-text {
    min-width: 0;
}
.ph-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1F2D24;
    line-height: 1.25;
    margin: 0;
    letter-spacing: -0.01em;
}
.ph-subtitle {
    font-size: 0.8125rem;
    color: #6B7C72;
    margin: 0.2rem 0 0;
    line-height: 1.4;
}
.ph-actions {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    flex-shrink: 0;
    flex-wrap: wrap;
}
.ph-btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #374151;
    background: #fff;
    border: 1px solid rgba(154,179,163,0.45);
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.15s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.ph-btn-back:hover {
    background: #f6f9f7;
    border-color: rgba(5,150,105,0.35);
    color: #059669;
}
.ph-btn-back i { font-size: 0.6875rem; opacity: 0.75; }

/* Unify inline action buttons from all pages */
.ph-actions a[style*="primary-gradient"],
.ph-actions a[class*="text-white"],
.ph-actions button[class*="text-white"] {
    padding: 0.5rem 1rem !important;
    font-size: 0.8125rem !important;
    border-radius: 0.5rem !important;
}

@media (max-width: 640px) {
    .ph-inner { flex-direction: column; align-items: flex-start; }
    .ph-actions { width: 100%; justify-content: flex-end; }
}
</style>
@endpush
@endonce
