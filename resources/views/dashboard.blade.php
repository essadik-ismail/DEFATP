@extends('layouts.app')

@section('title', 'Tableau de bord — ANEF Gestion Forestière')

@section('content')

{{-- ── Page header ──────────────────────────────────────────────────────── --}}
<x-page-header
    title="Tableau de bord"
    subtitle="Vue d'ensemble de la gestion forestière — ANEF"
    icon="fas fa-th-large">
    <x-slot:actions>
        <a href="{{ route('cessions.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-gavel"></i>
            Cessions
        </a>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-file-alt"></i>
            Articles
        </a>
    </x-slot:actions>
</x-page-header>

{{-- ── KPI Stats grid ───────────────────────────────────────────────────── --}}
@php
    $kpis = [
        [
            'label'      => 'Articles',
            'value'      => $stats['totalArticles'],
            'icon'       => 'fas fa-file-alt',
            'route'      => route('articles.index'),
            'icon-bg'    => '#163326',
            'icon-color' => '#FFFFFF',
        ],
        [
            'label'      => 'Forêts',
            'value'      => $stats['totalForests'],
            'icon'       => 'fas fa-tree',
            'route'      => route('entity-data.index', ['tab' => 'forets']),
            'icon-bg'    => '#2D7A54',
            'icon-color' => '#FFFFFF',
        ],
        [
            'label'      => 'Exploitants actifs',
            'value'      => $stats['activeExploitants'],
            'icon'       => 'fas fa-user-tie',
            'route'      => route('entity-data.index', ['tab' => 'exploitants']),
            'icon-bg'    => '#1A5276',
            'icon-color' => '#FFFFFF',
        ],
        [
            'label'      => 'Essences',
            'value'      => $stats['totalEssences'],
            'icon'       => 'fas fa-leaf',
            'route'      => route('entity-data.index', ['tab' => 'essences']),
            'icon-bg'    => '#7A4210',
            'icon-color' => '#FFFFFF',
        ],
    ];
@endphp

<div class="kpi-grid">
    @foreach($kpis as $kpi)
        <a href="{{ $kpi['route'] }}" class="stat-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;">
                <div style="min-width:0;">
                    <p class="kpi-label">{{ $kpi['label'] }}</p>
                    <p class="kpi-value">{{ number_format($kpi['value']) }}</p>
                </div>
                <div class="kpi-icon" style="background:{{ $kpi['icon-bg'] }};color:{{ $kpi['icon-color'] }};">
                    <i class="{{ $kpi['icon'] }}"></i>
                </div>
            </div>
            <p class="kpi-link">
                <i class="fas fa-arrow-right" style="font-size:0.625rem;"></i>
                Voir les détails
            </p>
        </a>
    @endforeach
</div>

{{-- ── Actions Required ─────────────────────────────────────────────────── --}}
@if(count($actionsRequired) > 0)
    <div class="card" style="margin-bottom:1.75rem;">
        <div class="card-header" style="background:#FFFBEB;border-bottom-color:#FDE68A;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="width:2rem;height:2rem;background:#D97706;border-radius:0.4375rem;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-exclamation-triangle" style="color:#fff;font-size:0.8125rem;"></i>
                </div>
                <div>
                    <h3 class="card-header-title" style="color:#78350F;">Actions requises</h3>
                    <p class="card-header-subtitle" style="color:#92400E;">
                        {{ count($actionsRequired) }} élément(s) nécessitent votre attention
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="actions-grid">
                @foreach($actionsRequired as $action)
                    <a href="{{ $action['route'] }}" class="action-card">
                        <span class="action-icon">
                            <i class="fas {{ $action['icon'] }}"></i>
                        </span>
                        <div style="flex:1;min-width:0;">
                            <p class="action-title">{{ $action['title'] }}</p>
                            <p class="action-desc">
                                {{ $action['description'] }} —
                                <span style="font-weight:700;color:#D97706;">{{ $action['count'] }} concerné(s)</span>
                            </p>
                        </div>
                        <i class="fas fa-chevron-right" style="color:#DDE6E2;font-size:0.5625rem;flex-shrink:0;"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- ── Welcome guide ───────────────────────────────────────────────────── --}}
<x-welcome-guide :show="true" />


@endsection
