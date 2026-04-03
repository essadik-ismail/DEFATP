@extends('layouts.app')

@section('title', 'Paramètres - Gestion Forestière')
@section('page-title', 'Paramètres')

@section('breadcrumb')
<li class="breadcrumb-item active">Paramètres</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full">

    <x-page-header
        title="Paramètres"
        subtitle="Gérez les données de base du système forestier"
        icon="fas fa-sliders-h"
    />

    @php
    $settingsItems = [
        [
            'title'    => 'Essences',
            'desc'     => 'Types d\'arbres forestiers',
            'icon'     => 'fas fa-leaf',
            'count'    => $stats['essences'],
            'unit'     => 'essences',
            'route'    => route('settings.essences.index'),
            'color'    => '#059669',
            'colorTo'  => '#047857',
            'bg'       => 'rgba(5,150,105,0.08)',
            'ring'     => 'rgba(5,150,105,0.2)',
        ],
        [
            'title'    => 'Forêts',
            'desc'     => 'Zones forestières',
            'icon'     => 'fas fa-tree',
            'count'    => $stats['forets'],
            'unit'     => 'forêts',
            'route'    => route('settings.forets.index'),
            'color'    => '#0d9488',
            'colorTo'  => '#0f766e',
            'bg'       => 'rgba(13,148,136,0.08)',
            'ring'     => 'rgba(13,148,136,0.2)',
        ],
        [
            'title'    => 'Natures de Coupes',
            'desc'     => 'Méthodes d\'exploitation',
            'icon'     => 'fas fa-cut',
            'count'    => $stats['nature_de_coupes'],
            'unit'     => 'types',
            'route'    => route('settings.nature-de-coupes.index'),
            'color'    => '#d97706',
            'colorTo'  => '#b45309',
            'bg'       => 'rgba(217,119,6,0.08)',
            'ring'     => 'rgba(217,119,6,0.2)',
        ],
        [
            'title'    => 'Situations Administratives',
            'desc'     => 'Communes et provinces',
            'icon'     => 'fas fa-building',
            'count'    => $stats['situation_administratives'],
            'unit'     => 'situations',
            'route'    => route('settings.situation-administratives.index'),
            'color'    => '#7c3aed',
            'colorTo'  => '#6d28d9',
            'bg'       => 'rgba(124,58,237,0.08)',
            'ring'     => 'rgba(124,58,237,0.2)',
        ],
        [
            'title'    => 'Exploitants',
            'desc'     => 'Gestion des exploitants forestiers',
            'icon'     => 'fas fa-user-tie',
            'count'    => $stats['exploitants'],
            'unit'     => 'exploitants',
            'route'    => route('exploitants.index'),
            'color'    => '#2563eb',
            'colorTo'  => '#1d4ed8',
            'bg'       => 'rgba(37,99,235,0.08)',
            'ring'     => 'rgba(37,99,235,0.2)',
        ],
        [
            'title'    => 'DRANEF',
            'desc'     => 'Directions régionales',
            'icon'     => 'fas fa-map-marker-alt',
            'count'    => $stats['dranefs'],
            'unit'     => 'entités',
            'route'    => route('settings.dranefs.index'),
            'color'    => '#059669',
            'colorTo'  => '#0d9488',
            'bg'       => 'rgba(5,150,105,0.08)',
            'ring'     => 'rgba(5,150,105,0.2)',
        ],
        [
            'title'    => 'Import / Export',
            'desc'     => 'Gestion des données Excel',
            'icon'     => 'fas fa-file-excel',
            'count'    => null,
            'unit'     => '',
            'route'    => route('excel.index'),
            'color'    => '#4b5563',
            'colorTo'  => '#374151',
            'bg'       => 'rgba(75,85,99,0.08)',
            'ring'     => 'rgba(75,85,99,0.2)',
        ],
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($settingsItems as $item)
        <a href="{{ $item['route'] }}"
           class="group block rounded-2xl bg-white border transition-all duration-200 overflow-hidden"
           style="border-color: {{ $item['ring'] }}; box-shadow: var(--shadow-card);">

            <!-- Card top accent line -->
            <div class="h-1 w-full" style="background: linear-gradient(90deg, {{ $item['color'] }}, {{ $item['colorTo'] }});"></div>

            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
                         style="background: linear-gradient(135deg, {{ $item['color'] }}, {{ $item['colorTo'] }}); box-shadow: 0 4px 12px {{ $item['ring'] }};">
                        <i class="{{ $item['icon'] }} text-white text-lg"></i>
                    </div>
                    @if($item['count'] !== null)
                    <span class="text-2xl font-bold tracking-tight" style="color: {{ $item['color'] }};">
                        {{ number_format($item['count']) }}
                    </span>
                    @endif
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $item['title'] }}</h3>
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">{{ $item['desc'] }}</p>

                @if($item['count'] !== null)
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium px-2 py-1 rounded-full"
                          style="background: {{ $item['bg'] }}; color: {{ $item['color'] }};">
                        {{ number_format($item['count']) }} {{ $item['unit'] }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold transition-all duration-150 group-hover:gap-2"
                          style="color: {{ $item['color'] }};">
                        Gérer <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </div>
                @else
                <div class="flex items-center justify-end">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold transition-all duration-150 group-hover:gap-2"
                          style="color: {{ $item['color'] }};">
                        Accéder <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
