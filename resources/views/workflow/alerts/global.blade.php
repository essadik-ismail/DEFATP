@extends('layouts.app')

@section('title', 'Alertes — DEFATP')

@section('breadcrumb')
<li class="bc-item active">Alertes</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-6xl">

        <x-page-header
            title="Alertes"
            subtitle="Vue globale de toutes les alertes actives"
            icon="fas fa-bell"
        />

        {{-- Summary counters --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <a href="{{ route('workflow.alerts.index', ['severity' => 'critical']) }}"
               class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-lg p-4 hover:bg-red-100 transition-colors {{ $severity === 'critical' ? 'ring-2 ring-red-400' : '' }}">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-700">{{ $counts['critical'] ?? 0 }}</div>
                    <div class="text-xs text-red-600 font-medium">Critiques</div>
                </div>
            </a>

            <a href="{{ route('workflow.alerts.index', ['severity' => 'warning']) }}"
               class="flex items-center gap-3 bg-yellow-50 border border-yellow-200 rounded-lg p-4 hover:bg-yellow-100 transition-colors {{ $severity === 'warning' ? 'ring-2 ring-yellow-400' : '' }}">
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-700">{{ $counts['warning'] ?? 0 }}</div>
                    <div class="text-xs text-yellow-600 font-medium">Avertissements</div>
                </div>
            </a>

            <a href="{{ route('workflow.alerts.index', ['severity' => 'info']) }}"
               class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 transition-colors {{ $severity === 'info' ? 'ring-2 ring-blue-400' : '' }}">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-700">{{ $counts['info'] ?? 0 }}</div>
                    <div class="text-xs text-blue-600 font-medium">Informations</div>
                </div>
            </a>
        </div>

        {{-- Filter bar --}}
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            @if($severity || $type)
                <a href="{{ route('workflow.alerts.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                    <i class="fas fa-times-circle"></i> Effacer les filtres
                </a>
            @endif

            @php
                $typeLabels = [
                    \App\Models\Alert::TYPE_DECHEANCE_CAUTION             => 'Déchéance caution',
                    \App\Models\Alert::TYPE_RESILIATION_CONTRAT           => 'Résiliation',
                    \App\Models\Alert::TYPE_RETARD_TAXE                   => 'Retard taxe',
                    \App\Models\Alert::TYPE_RETARD_TRANCHE                => 'Retard tranche',
                    \App\Models\Alert::TYPE_EXPIRATION_CONTRAT            => 'Expiration contrat',
                    \App\Models\Alert::TYPE_DEPASSEMENT_VOLUME_COLPORTAGE => 'Dépassement colportage',
                    \App\Models\Alert::TYPE_SERIE_COLPORTAGE_NON_UTILISEE => 'Carnet non utilisé',
                ];
            @endphp

            <form method="GET" action="{{ route('workflow.alerts.index') }}" class="flex items-center gap-2">
                @if($severity)
                    <input type="hidden" name="severity" value="{{ $severity }}">
                @endif
                <select name="type" onchange="this.form.submit()"
                        class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les types</option>
                    @foreach($typeLabels as $value => $label)
                        <option value="{{ $value }}" {{ $type === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Alert list --}}
        @php
            $severityConfig = [
                'critical' => ['bg' => 'bg-red-50', 'border' => 'border-red-300', 'badge' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-exclamation-circle text-red-500', 'label' => 'Critique'],
                'warning'  => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300', 'badge' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-exclamation-triangle text-yellow-500', 'label' => 'Avertissement'],
                'info'     => ['bg' => 'bg-blue-50', 'border' => 'border-blue-300', 'badge' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-info-circle text-blue-500', 'label' => 'Information'],
            ];
        @endphp

        @if($alerts->isEmpty())
            <x-empty-state
                icon="fas fa-bell-slash"
                title="Aucune alerte active"
                message="Aucune alerte ne correspond aux critères sélectionnés."
                color="green"
            />
        @else
        <div class="space-y-3">
            @foreach($alerts as $alert)
            @php $cfg = $severityConfig[$alert->severity] ?? $severityConfig['info']; @endphp
            <div class="{{ $cfg['bg'] }} {{ $cfg['border'] }} border rounded-lg p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <i class="{{ $cfg['icon'] }} text-lg mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $alert->title }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cfg['badge'] }}">
                                    {{ $cfg['label'] }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $typeLabels[$alert->type] ?? $alert->type }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $alert->message }}</p>
                            <div class="flex items-center gap-4 mt-1.5 flex-wrap">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $alert->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </span>
                                @if($alert->entity_type && $alert->entity_id)
                                    @php
                                        $entityLabel = class_basename($alert->entity_type) . ' #' . $alert->entity_id;
                                        $articleLink = null;
                                        if ($alert->entity_type === \App\Models\Article::class) {
                                            $articleLink = route('articles.show', $alert->entity_id);
                                        }
                                    @endphp
                                    @if($articleLink)
                                        <a href="{{ $articleLink }}" class="text-xs text-blue-600 hover:underline">
                                            <i class="fas fa-external-link-alt mr-1"></i>{{ $entityLabel }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">{{ $entityLabel }}</span>
                                    @endif
                                @endif
                            </div>
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

        <div class="mt-6">
            {{ $alerts->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
