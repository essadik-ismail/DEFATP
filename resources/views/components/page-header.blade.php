@props([
    'title',
    'subtitle' => null,
    'icon' => 'fas fa-file',
    'backRoute' => null,
    'backText' => 'Retour',
    'actions' => null,
])

<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center mt-0.5"
                 style="background: var(--primary-gradient); box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);">
                <i class="{{ $icon }} text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold leading-tight" style="color: #1F2D24;">
                    {{ $title }}
                </h1>
                @if($subtitle)
                    <p class="text-sm mt-0.5" style="color: #6B7C72;">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            @if($actions)
                {{ $actions }}
            @endif
            
            @if($backRoute)
                <a href="{{ $backRoute }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium transition-colors border text-sm"
                   style="background: #FFFFFF; color: #1F2D24; border-color: rgba(154,179,163,0.5); box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>{{ $backText }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
