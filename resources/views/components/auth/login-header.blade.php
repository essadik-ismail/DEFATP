@props([
    'title' => 'Se connecter',
    'subtitle' => "Accédez à votre compte DEFATP",
    'icon' => 'fas fa-tree'
])

<div class="login-header">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="text-gray-500 text-sm">{{ $subtitle }}</p>
        @endif
    </div>
</div>

