@extends('layouts.app')

@section('title', 'Notifications')

@section('breadcrumb')
<li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Notifications"
        subtitle="Restez informé des dernières activités"
        icon="fas fa-bell"
    >
        <x-slot name="actions">
            @if($unreadCount > 0)
                <x-button variant="secondary" icon="fas fa-check-double" onclick="markAllAsRead()">
                    Tout marquer comme lu
                </x-button>
            @endif
            <x-button href="{{ route('notifications.settings') }}" variant="secondary" icon="fas fa-cog">
                Paramètres
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ── KPI strip ──────────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:0.875rem; margin-bottom:1.25rem;">
        @foreach([
            ['label'=>'Total',      'value'=>$notifications->total(),                                      'icon'=>'fas fa-bell',           'color'=>'#1A5276','bg'=>'#EBF5FB','border'=>'#AED6F1'],
            ['label'=>'Non lues',   'value'=>$unreadCount,                                                 'icon'=>'fas fa-exclamation-circle','color'=>'#92400E','bg'=>'#FFFBEB','border'=>'#FCD34D'],
            ['label'=>'Lues',       'value'=>$notifications->where('read_at','!=',null)->count(),          'icon'=>'fas fa-check-circle',   'color'=>'#276749','bg'=>'#ECFDF5','border'=>'#A7F3D0'],
            ['label'=>"Aujourd'hui",'value'=>$notifications->where('created_at','>=',today())->count(),    'icon'=>'fas fa-calendar-day',   'color'=>'#5F7A6B','bg'=>'#F3F6F4','border'=>'#DDE5E1'],
        ] as $kpi)
        <div style="background:#fff; border:1px solid {{ $kpi['border'] }}; border-radius:0.75rem;
                    padding:1rem 1.25rem; display:flex; align-items:center; gap:0.75rem;
                    box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="width:34px; height:34px; border-radius:0.5rem; flex-shrink:0; display:flex;
                        align-items:center; justify-content:center; background:{{ $kpi['bg'] }};">
                <i class="{{ $kpi['icon'] }}" style="color:{{ $kpi['color'] }}; font-size:0.875rem;"></i>
            </div>
            <div>
                <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#5F7A6B; margin:0 0 0.125rem;">{{ $kpi['label'] }}</p>
                <p style="font-size:1.375rem; font-weight:700; color:#1A2D22; margin:0; line-height:1;">{{ $kpi['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Filters ─────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('notifications.index') }}"
          style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                 padding:1rem 1.25rem; margin-bottom:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:0.75rem; align-items:flex-end;">
            <div>
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">Tous les types</option>
                    @foreach(['success'=>'Succès','error'=>'Erreur','warning'=>'Avertissement','info'=>'Information','system'=>'Système','exploitant'=>'Exploitant','foret'=>'Forêt'] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('type')==$val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="unread" {{ request('status')=='unread' ? 'selected' : '' }}>Non lues</option>
                    <option value="read"   {{ request('status')=='read'   ? 'selected' : '' }}>Lues</option>
                </select>
            </div>
            <div>
                <label class="form-label">Priorité</label>
                <select name="priority" class="form-select">
                    <option value="">Toutes</option>
                    <option value="high"   {{ request('priority')=='high'   ? 'selected' : '' }}>Urgent</option>
                    <option value="medium" {{ request('priority')=='medium' ? 'selected' : '' }}>Important</option>
                    <option value="low"    {{ request('priority')=='low'    ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn-primary" style="flex:1;">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <a href="{{ route('notifications.index') }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- ── Notifications list ──────────────────────────────────────────── --}}
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">

        <div style="padding:0.75rem 1.25rem; border-bottom:1px solid #EEF2EF; background:#FAFCFB;">
            <p style="font-size:0.8125rem; color:#5F7A6B; margin:0;">
                {{ $notifications->count() }} notification(s) affichée(s)
            </p>
        </div>

        @if($notifications->count())
            @foreach($notifications as $notification)
            <div class="notif-item {{ $notification->isUnread() ? 'notif-unread' : '' }}"
                 data-notification-id="{{ $notification->id }}"
                 style="display:flex; align-items:flex-start; gap:0.875rem; padding:0.875rem 1.25rem;
                        border-bottom:1px solid #EEF2EF; transition:background 0.15s;">
                {{-- Icon --}}
                <div style="width:36px; height:36px; border-radius:50%; background:#F3F6F4; flex-shrink:0;
                            display:flex; align-items:center; justify-content:center;">
                    <i class="{{ $notification->icon }} text-{{ $notification->color }}" style="font-size:0.9rem;"></i>
                </div>

                {{-- Body --}}
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:0.5rem; margin-bottom:0.25rem;">
                        <p style="font-size:0.8125rem; font-weight:600; color:#1A2D22; margin:0;">
                            {{ $notification->title }}
                            @if($notification->isUnread())
                                <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#2D7A54; margin-left:0.375rem; vertical-align:middle;"></span>
                            @endif
                        </p>
                        <div style="display:flex; align-items:center; gap:0.375rem; flex-shrink:0;">
                            @if($notification->isUnread())
                                <button type="button"
                                        onclick="markAsRead('{{ $notification->id }}')"
                                        class="tbl-action bg-green-50 hover:bg-green-100 text-green-700 border border-green-200"
                                        title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            <button type="button"
                                    onclick="deleteNotification('{{ $notification->id }}')"
                                    class="tbl-action bg-red-50 hover:bg-red-100 text-red-600 border border-red-200"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p style="font-size:0.8125rem; color:#5F7A6B; margin:0 0 0.375rem; line-height:1.5;">
                        {{ $notification->message }}
                    </p>
                    <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                        <span style="font-size:0.75rem; color:#9AB3A3; display:flex; align-items:center; gap:0.25rem;">
                            <i class="fas fa-clock" style="font-size:0.6875rem;"></i>
                            {{ $notification->time_ago }}
                        </span>
                        @if($notification->priority === 'high')
                            <span class="badge-danger"><i class="fas fa-exclamation"></i> Urgent</span>
                        @elseif($notification->priority === 'medium')
                            <span class="badge-warning"><i class="fas fa-exclamation-triangle"></i> Important</span>
                        @endif
                        @if($notification->action_url)
                            <a href="{{ $notification->action_url }}" class="badge-info" style="text-decoration:none;">
                                <i class="fas fa-external-link-alt"></i> Voir
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            @if($notifications->hasPages())
                <div style="padding:0.75rem 1.25rem; border-top:1px solid #EEF2EF;">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div style="padding:3rem 1rem; text-align:center;">
                <x-empty-state
                    icon="fas fa-bell-slash"
                    title="Aucune notification"
                    message="Vous n'avez aucune notification pour le moment."
                    color="green"
                />
            </div>
        @endif
    </div>

</div>

@push('styles')
<style>
.notif-item:hover { background: #FAFCFB; }
.notif-unread { background: #F4FAF7; border-left: 3px solid #2D7A54; }
.notif-unread:hover { background: #EDF7F2; }
</style>
@endpush

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); })
    .catch(console.error);
}

function markAllAsRead() {
    if (!confirm('Marquer toutes les notifications comme lues ?')) return;
    fetch('/notifications/mark-all-read', {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); })
    .catch(console.error);
}

function deleteNotification(id) {
    if (!confirm('Supprimer cette notification ?')) return;
    fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => { if (data.success) document.querySelector(`[data-notification-id="${id}"]`)?.remove(); })
    .catch(console.error);
}
</script>
@endpush

@endsection
