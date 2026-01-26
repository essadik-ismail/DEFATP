@props([
    'title',
    'subtitle' => null,
    'icon' => 'fas fa-file',
    'backRoute' => null,
    'backText' => 'Retour',
    'actions' => null,
])

<div class="mb-4">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold mb-0.5" style="color: #1F2D24;">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-xs" style="color: #6B7C72;">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($actions)
                {{ $actions }}
            @endif
            
            @if($backRoute)
                <a href="{{ $backRoute }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium transition-colors border"
                   style="background: #FFFFFF; color: #1F2D24; border-color: rgba(154,179,163,0.5); box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>{{ $backText }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
