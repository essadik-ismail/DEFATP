@extends('layouts.app')

@section('title', 'Alertes - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Alertes</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-5xl">

        <x-page-header
            title="Alertes du dossier"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-bell"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @php
            $severityConfig = [
                'critical' => ['bg' => 'bg-red-50', 'border' => 'border-red-300', 'badge' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-exclamation-circle text-red-500', 'label' => 'Critique'],
                'warning'  => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300', 'badge' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-exclamation-triangle text-yellow-500', 'label' => 'Avertissement'],
                'info'     => ['bg' => 'bg-blue-50', 'border' => 'border-blue-300', 'badge' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-info-circle text-blue-500', 'label' => 'Information'],
            ];
        @endphp

        @if(empty($alerts))
            <x-empty-state
                icon="fas fa-bell-slash"
                title="Aucune alerte active"
                message="Il n'y a aucune alerte active pour ce dossier."
                color="green"
            />
        @else
        <div class="space-y-4">
            @foreach($alerts as $alert)
            @php $cfg = $severityConfig[$alert->severity] ?? $severityConfig['info']; @endphp
            <div class="{{ $cfg['bg'] }} {{ $cfg['border'] }} border rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <i class="{{ $cfg['icon'] }} text-xl mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $alert->title }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cfg['badge'] }}">
                                    {{ $cfg['label'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $alert->message }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $alert->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </p>
                        </div>
                    </div>
                    @can('alert.archive')
                    <form action="{{ route('workflow.alert.archive', $alert) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <input type="hidden" name="reason" value="Archivé manuellement">
                        <button type="submit"
                                onclick="return confirm('Archiver cette alerte ?')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-50 transition-colors">
                            <i class="fas fa-archive"></i> Archiver
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
