{{--
    Flash Messages — renders ALL session flash types in one line.

    Usage (drop anywhere in a @section('content')):
        <x-flash-messages />

    Supported session keys:  success | info | warning | error
    Each renders an <x-alert> with the correct variant, icon, and dismiss button.
--}}

@php
    $flashes = [
        'success' => ['title' => 'Succès !',   'type' => 'success'],
        'info'    => ['title' => 'Information', 'type' => 'info'],
        'warning' => ['title' => 'Attention',   'type' => 'warning'],
        'error'   => ['title' => 'Erreur',      'type' => 'error'],
    ];
@endphp

@foreach($flashes as $key => $cfg)
    @if(session($key))
        <x-alert
            :type="$cfg['type']"
            :title="$cfg['title']"
            dismissible
            class="mb-4"
        >{{ session($key) }}</x-alert>
    @endif
@endforeach
