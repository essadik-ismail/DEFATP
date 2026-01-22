@props([
    'title',
    'subtitle' => null,
    'icon' => 'fas fa-file',
    'backRoute' => null,
    'backText' => 'Retour',
    'actions' => null,
    'gradient' => 'linear-gradient(135deg, #059669, #047857)'
])

<div class="mb-8">
    <div class="p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-lg" style="background: {{ $gradient }};">
                    <i class="{{ $icon }} text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        {{ $title }}
                    </h1>
                    @if($subtitle)
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle text-green-400"></i>
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($actions)
                    {{ $actions }}
                @endif
                
                @if($backRoute)
                    <a href="{{ $backRoute }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ $backText }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
