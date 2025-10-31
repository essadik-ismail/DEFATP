@props([
    'title' => 'Se connecter',
    'subtitle' => "Accédez à votre compte DEFATP",
    'icon' => 'fas fa-tree'
])

<div class="login-header">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
            <i class="{{ $icon }} text-white text-2xl" style="font-size: 1.5rem;"></i>
        </div>
        <div>
            <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-gray-600 text-lg mt-2">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>

