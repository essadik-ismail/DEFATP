@extends('layouts.app')

@section('title', "Journal d'Activités")

@section('breadcrumb')
<li class="breadcrumb-item active">Journal d'activités</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Journal d'Activités"
        subtitle="Suivi des actions des utilisateurs dans le système"
        icon="fas fa-history"
    >
        <x-slot name="actions">
            <x-button href="{{ route('activity-logs.export') }}" variant="secondary" icon="fas fa-download">
                Exporter
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ── Filters ─────────────────────────────────────────────────────── --}}
    <form id="activityLogsFilter"
          style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                 padding:1rem 1.25rem; margin-bottom:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:0.75rem; align-items:flex-end;">
            <div>
                <label class="form-label">Recherche</label>
                <input type="text" id="search" name="search" class="form-input" placeholder="Description…">
            </div>
            <div>
                <label class="form-label">Utilisateur</label>
                <select id="user_id" name="user_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Action</label>
                <select id="action" name="action" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Type de modèle</label>
                <select id="model_type" name="model_type" class="form-select">
                    <option value="">Tous</option>
                    @foreach($modelTypes as $modelType)
                        <option value="{{ $modelType }}">{{ class_basename($modelType) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Du</label>
                <input type="date" id="date_from" name="date_from" class="form-input">
            </div>
            <div>
                <label class="form-label">Au</label>
                <input type="date" id="date_to" name="date_to" class="form-input">
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn-primary" style="flex:1;">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <button type="button" class="btn-secondary" onclick="resetFilters()" title="Réinitialiser">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
    </form>

    {{-- ── Table card ──────────────────────────────────────────────────── --}}
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">

        <div style="padding:0.75rem 1.25rem; border-bottom:1px solid #EEF2EF; background:#FAFCFB;">
            <p style="font-size:0.8125rem; color:#5F7A6B; margin:0;" id="totalCount">
                {{ $activityLogs->total() }} activité(s) trouvée(s)
            </p>
        </div>

        <div id="activityLogsTable">
            @include('activity-logs.partials.activity-logs-table', ['activityLogs' => $activityLogs])
        </div>

        <div id="activityLogsPagination" style="padding:0.75rem 1.25rem; border-top:1px solid #EEF2EF;">
            @include('activity-logs.partials.pagination', ['activityLogs' => $activityLogs])
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('activityLogsFilter').addEventListener('submit', function(e) {
    e.preventDefault();
    loadActivityLogs();
});

function loadActivityLogs() {
    const formData = new FormData(document.getElementById('activityLogsFilter'));
    const params = new URLSearchParams(formData);

    fetch(`{{ route('activity-logs.ajax-logs') }}?${params.toString()}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('activityLogsTable').innerHTML = data.html;
            document.getElementById('activityLogsPagination').innerHTML = data.pagination;
        })
        .catch(console.error);
}

function resetFilters() {
    document.getElementById('activityLogsFilter').reset();
    loadActivityLogs();
}

setInterval(() => {}, 30000);
</script>
@endpush
