@extends('layouts.app')

@section('title', 'Tableau de Bord - DEFATP')

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    @php
        $statsData = [
            ['title' => 'Articles', 'value' => $stats['totalArticles'], 'icon' => 'fas fa-file-alt', 'color' => 'green', 'route' => route('articles.index')],
            ['title' => 'Forêts', 'value' => $stats['totalForests'], 'icon' => 'fas fa-tree', 'color' => 'teal', 'route' => route('entity-data.index', ['tab' => 'forets'])],
            ['title' => 'Exploitants actifs', 'value' => $stats['activeExploitants'], 'icon' => 'fas fa-user-tie', 'color' => 'blue', 'route' => route('entity-data.index', ['tab' => 'exploitants'])],
            ['title' => 'Essences', 'value' => $stats['totalEssences'], 'icon' => 'fas fa-leaf', 'color' => 'purple', 'route' => route('entity-data.index', ['tab' => 'essences'])],
        ];
    @endphp

    <!-- Header -->
    <x-page-header
        title="Tableau de bord"
        subtitle="Vue d'ensemble de votre gestion forestière"
        icon="fas fa-chart-line"
    >
        <x-slot name="actions">
            <a href="{{ route('cessions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm" style="background: var(--primary-gradient); box-shadow: var(--shadow-md);">
                <i class="fas fa-gavel"></i>
                Cessions
            </a>
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-file-alt"></i>
                Articles
            </a>
        </x-slot>
    </x-page-header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach($statsData as $stat)
        <a href="{{ $stat['route'] }}" class="stat-card block rounded-2xl border bg-white p-5 hover:shadow-lg transition-all duration-200" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                    {{ $stat['color'] === 'green' ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200' : '' }}
                    {{ $stat['color'] === 'teal' ? 'bg-teal-50 text-teal-600 ring-1 ring-teal-200' : '' }}
                    {{ $stat['color'] === 'blue' ? 'bg-blue-50 text-blue-600 ring-1 ring-blue-200' : '' }}
                    {{ $stat['color'] === 'purple' ? 'bg-purple-50 text-purple-600 ring-1 ring-purple-200' : '' }}">
                    <i class="{{ $stat['icon'] }} text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
                    <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Actions Required (if any) -->
    @if(count($actionsRequired) > 0)
    <div class="rounded-2xl border bg-white mb-8 overflow-hidden" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
        <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color: rgba(154,179,163,0.3);">
            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-900">Actions requises</h3>
        </div>
        <div class="p-4">
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                @foreach($actionsRequired as $action)
                <a href="{{ $action['route'] }}" class="inline-flex items-center gap-3 px-4 py-3 rounded-xl border bg-white hover:shadow-md transition-all"
                   style="border-color: rgba(154,179,163,0.4);">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-amber-50 text-amber-600">
                        <i class="fas {{ $action['icon'] }}"></i>
                    </span>
                    <div class="text-left">
                        <p class="font-semibold text-gray-900">{{ $action['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $action['description'] }} — {{ $action['count'] }} concerné(s)</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Welcome Guide -->
    <x-welcome-guide :show="true" />

    <!-- Recent Articles -->
    @if($recentArticles->isNotEmpty())
    <div class="rounded-2xl border bg-white overflow-hidden" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: rgba(154,179,163,0.3);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-sm"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Articles récents</h3>
            </div>
            <a href="{{ route('articles.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                Voir tout →
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentArticles as $article)
            <a href="{{ route('articles.show', $article) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-semibold">
                    {{ $article->numero ?? $article->id }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-gray-900 truncate">Article {{ $article->numero ?? '#' . $article->id }}</p>
                    <p class="text-xs text-gray-500">{{ $article->created_at ? $article->created_at->format('d/m/Y H:i') : '-' }}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm flex-shrink-0"></i>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-2xl border bg-white p-8" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
        <x-empty-state
            icon="fas fa-file-alt"
            title="Aucun article récent"
            message="Les derniers articles créés apparaîtront ici."
            color="gray"
        >
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white mt-4" style="background: var(--primary-gradient);">
                <i class="fas fa-file-alt"></i>
                Voir les articles
            </a>
        </x-empty-state>
    </div>
    @endif
</div>

@push('styles')
<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.stat-card { animation: fadeInUp 0.5s ease-out; animation-fill-mode: both; }
.stat-card:nth-child(1) { animation-delay: 0.05s; }
.stat-card:nth-child(2) { animation-delay: 0.1s; }
.stat-card:nth-child(3) { animation-delay: 0.15s; }
.stat-card:nth-child(4) { animation-delay: 0.2s; }
</style>
@endpush
@endsection
