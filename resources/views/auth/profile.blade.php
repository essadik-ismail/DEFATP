@extends('layouts.app')

@section('title', 'Mon Profil')

@section('breadcrumb')
<li class="breadcrumb-item active">Mon profil</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Mon profil"
        subtitle="Gérez vos informations personnelles et paramètres de compte"
        icon="fas fa-user-circle"
    />

    <x-validation-errors />

    @if(session('success'))
        <x-alert type="success" title="Succès !" dismissible class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error" title="Erreur !" dismissible class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ─── Sidebar ──────────────────────────────────────────────────── --}}
        <div class="space-y-5">

            {{-- Avatar card --}}
            <div class="rounded-2xl border bg-white overflow-hidden"
                 style="border-color: var(--border); box-shadow: var(--sh-md);">

                {{-- Cover strip --}}
                <div class="h-20 w-full" style="background: linear-gradient(135deg, var(--anef-800) 0%, var(--anef-600) 100%);"></div>

                {{-- Avatar --}}
                <div class="px-6 pb-5">
                    <div class="-mt-10 mb-4 flex items-end justify-between">
                        <div class="relative">
                            @if($user->image)
                                <img src="{{ Storage::url($user->image) }}"
                                     alt="{{ $user->name }}"
                                     class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white"
                                     style="box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            @else
                                <div class="w-20 h-20 rounded-2xl ring-4 ring-white flex items-center justify-center"
                                     style="background: var(--anef-800); box-shadow: 0 4px 12px rgba(0,188,125,0.25);">
                                    <span class="text-white text-2xl font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-bold leading-tight" style="color: var(--text)">{{ $user->name }}</h3>
                    <p class="text-sm mt-0.5 flex items-center gap-1.5" style="color: var(--text-sec)">
                        <i class="fas fa-envelope text-xs" style="color: var(--anef-500)"></i>
                        {{ $user->email }}
                    </p>
                    <p class="text-sm mt-0.5 flex items-center gap-1.5" style="color: var(--text-sec)">
                        <i class="fas fa-id-card text-xs" style="color: var(--anef-500)"></i>
                        PPR : {{ $user->ppr }}
                    </p>
                    <p class="text-xs mt-1 flex items-center gap-1.5" style="color: var(--text-muted)">
                        <i class="fas fa-calendar text-xs"></i>
                        Membre depuis {{ $user->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            {{-- Roles & Permissions --}}
            @if($user->roles->isNotEmpty())
            <div class="rounded-2xl border bg-white p-5"
                 style="border-color: var(--border); box-shadow: var(--sh-md);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--text-muted)">
                    <i class="fas fa-shield-alt mr-1.5" style="color: var(--anef-500)"></i>Rôles attribués
                </p>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold"
                              style="background: var(--color-anef-100); color: var(--anef-800); border: 1px solid var(--color-anef-200);">
                            <i class="fas fa-user-tag text-xs"></i>
                            {{ strtoupper($role->name) }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Affectation summary --}}
            @if($user->dranef || $user->dpanef || $user->zdtf || $user->dfp || $user->province)
            <div class="rounded-2xl border bg-white p-5"
                 style="border-color: var(--border); box-shadow: var(--sh-md);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--text-muted)">
                    <i class="fas fa-sitemap mr-1.5" style="color: var(--anef-500)"></i>Affectation
                </p>
                <div class="space-y-2">
                    @if($user->dranef)
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color: var(--text-muted)">DRANEF</span>
                        <span class="text-xs font-medium" style="color: var(--text)">{{ $user->dranef->dranef }}</span>
                    </div>
                    @endif
                    @if($user->dpanef)
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color: var(--text-muted)">DPANEF</span>
                        <span class="text-xs font-medium" style="color: var(--text)">{{ $user->dpanef->dpanef ?? $user->dpanef->code }}</span>
                    </div>
                    @endif
                    @if($user->zdtf)
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color: var(--text-muted)">ZDTF</span>
                        <span class="text-xs font-medium" style="color: var(--text)">{{ $user->zdtf->code ?? $user->zdtf->zdtf ?? $user->zdtf->sdtf }}</span>
                    </div>
                    @endif
                    @if($user->dfp)
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color: var(--text-muted)">DFP</span>
                        <span class="text-xs font-medium" style="color: var(--text)">{{ $user->dfp->code ?? $user->dfp->dfp }}</span>
                    </div>
                    @endif
                    @if($user->province)
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color: var(--text-muted)">Province</span>
                        <span class="text-xs font-medium" style="color: var(--text)">{{ $user->province->nom }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Security badge --}}
            <div class="rounded-xl p-4 flex items-center gap-3"
                 style="background: var(--color-anef-100); border: 1px solid var(--color-anef-200);">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background: var(--anef-800);">
                    <i class="fas fa-shield-alt text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold" style="color: var(--anef-800)">Compte sécurisé</p>
                    <p class="text-xs" style="color: var(--anef-600)">Votre compte est protégé</p>
                </div>
            </div>

        </div>

        {{-- ─── Forms ────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- ── 1. Informations personnelles ──────────────────────────── --}}
            <form action="{{ route('auth.profile.update.info') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="rounded-2xl border bg-white overflow-hidden"
                     style="border-color: var(--border); box-shadow: var(--sh-md);">

                    <div class="flex items-center gap-3 px-6 py-4"
                         style="border-bottom: 1px solid var(--border); background: var(--color-anef-50);">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--anef-800);">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <p class="text-sm font-semibold" style="color: var(--text)">Informations personnelles</p>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- Avatar upload --}}
                        <div>
                            <label class="form-label">Photo de profil</label>
                            <div class="flex items-center gap-4 mt-1">
                                <div id="avatar-preview"
                                     class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center"
                                     style="background: var(--anef-800);">
                                    @if($user->image)
                                        <img src="{{ Storage::url($user->image) }}"
                                             alt="Avatar"
                                             class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white text-xl font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label for="image"
                                           class="btn btn-secondary btn-sm cursor-pointer inline-flex items-center gap-2">
                                        <i class="fas fa-upload"></i>
                                        Choisir une photo
                                    </label>
                                    <input id="image" name="image" type="file"
                                           accept="image/jpeg,image/png,image/jpg,image/webp"
                                           class="hidden"
                                           onchange="previewAvatar(this)">
                                    <p class="text-xs mt-1.5" style="color: var(--text-muted)">
                                        JPEG, PNG ou WebP · max 2 Mo
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form-input
                                name="name"
                                label="Nom complet"
                                required
                                :value="old('name', $user->name)"
                            />
                            <x-form-input
                                name="ppr"
                                label="PPR"
                                required
                                :value="old('ppr', $user->ppr)"
                            />
                            <div class="md:col-span-2">
                                <x-form-input
                                    name="email"
                                    type="email"
                                    label="Adresse e-mail"
                                    required
                                    :value="old('email', $user->email)"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4"
                         style="border-top: 1px solid var(--border); background: var(--color-anef-50);">
                        <x-button type="submit" icon="fas fa-save" size="sm">
                            Enregistrer
                        </x-button>
                    </div>
                </div>
            </form>

            {{-- ── 2. Affectation / Entité ───────────────────────────────── --}}
            <form action="{{ route('auth.profile.update.affectation') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="rounded-2xl border bg-white overflow-hidden"
                     style="border-color: var(--border); box-shadow: var(--sh-md);">

                    <div class="flex items-center gap-3 px-6 py-4"
                         style="border-bottom: 1px solid var(--border); background: var(--color-anef-50);">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--anef-800);">
                            <i class="fas fa-sitemap text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--text)">Affectation / Entité</p>
                            <p class="text-xs mt-0.5" style="color: var(--text-muted)">Optionnel — liez votre compte à une DRANEF, DPANEF, ZDTF, DFP ou Province.</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-input name="dranef_id" type="select" label="DRANEF">
                            <option value="">— Aucune —</option>
                            @foreach($dranefs as $d)
                                <option value="{{ $d->id }}" @selected(old('dranef_id', $user->dranef_id) == $d->id)>{{ $d->dranef }}</option>
                            @endforeach
                        </x-form-input>

                        <x-form-input name="dpanef_id" type="select" label="DPANEF">
                            <option value="">— Aucune —</option>
                            @foreach($dpanefs as $d)
                                <option value="{{ $d->id }}" @selected(old('dpanef_id', $user->dpanef_id) == $d->id)>{{ $d->dpanef ?? $d->code }}</option>
                            @endforeach
                        </x-form-input>

                        <x-form-input name="zdtf_id" type="select" label="ZDTF">
                            <option value="">— Aucune —</option>
                            @foreach($zdtfs as $z)
                                <option value="{{ $z->id }}" @selected(old('zdtf_id', $user->zdtf_id) == $z->id)>{{ $z->code ?? $z->zdtf ?? $z->sdtf }}</option>
                            @endforeach
                        </x-form-input>

                        <x-form-input name="dfp_id" type="select" label="DFP">
                            <option value="">— Aucune —</option>
                            @foreach($dfps as $d)
                                <option value="{{ $d->id }}" @selected(old('dfp_id', $user->dfp_id) == $d->id)>{{ $d->code ?? $d->dfp }}</option>
                            @endforeach
                        </x-form-input>

                        <div class="md:col-span-2">
                            <x-form-input name="province_id" type="select" label="Province">
                                <option value="">— Aucune —</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->id }}" @selected(old('province_id', $user->province_id) == $p->id)>{{ $p->nom }}</option>
                                @endforeach
                            </x-form-input>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4"
                         style="border-top: 1px solid var(--border); background: var(--color-anef-50);">
                        <x-button type="submit" icon="fas fa-save" size="sm">
                            Enregistrer
                        </x-button>
                    </div>
                </div>
            </form>

            {{-- ── 3. Mot de passe ───────────────────────────────────────── --}}
            <form action="{{ route('auth.profile.update.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="rounded-2xl border bg-white overflow-hidden"
                     style="border-color: var(--color-warning-light); box-shadow: var(--sh-md);">

                    <div class="flex items-center gap-3 px-6 py-4"
                         style="border-bottom: 1px solid var(--color-warning-light); background: #FFFBEB;">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--warning);">
                            <i class="fas fa-lock text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--text)">Changer le mot de passe</p>
                            <p class="text-xs mt-0.5" style="color: var(--text-muted)">Tous les champs sont requis · minimum 8 caractères.</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-form-input
                            name="current_password"
                            type="password"
                            label="Mot de passe actuel"
                            placeholder="••••••••"
                        />
                        <x-form-input
                            name="new_password"
                            type="password"
                            label="Nouveau mot de passe"
                            placeholder="••••••••"
                        />
                        <x-form-input
                            name="new_password_confirmation"
                            type="password"
                            label="Confirmer"
                            placeholder="••••••••"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4"
                         style="border-top: 1px solid var(--color-warning-light); background: #FFFBEB;">
                        <x-button type="submit" icon="fas fa-key" size="sm">
                            Mettre à jour
                        </x-button>
                    </div>
                </div>
            </form>

            {{-- ── 4. Activité récente ───────────────────────────────────── --}}
            @if($activityJournals->isNotEmpty())
            <div class="rounded-2xl border bg-white overflow-hidden"
                 style="border-color: var(--border); box-shadow: var(--sh-md);">

                <div class="flex items-center gap-3 px-6 py-4"
                     style="border-bottom: 1px solid var(--border); background: var(--color-anef-50);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--anef-800);">
                        <i class="fas fa-history text-white text-sm"></i>
                    </div>
                    <p class="text-sm font-semibold" style="color: var(--text)">Activité récente</p>
                </div>

                <div class="divide-y" style="border-color: var(--border)">
                    @foreach($activityJournals as $log)
                    <div class="px-6 py-3 flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background: var(--color-anef-100);">
                            <i class="fas fa-circle-dot text-xs" style="color: var(--anef-600)"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm truncate" style="color: var(--text)">
                                {{ $log->Action ?? $log->action ?? '—' }}
                            </p>
                            <p class="text-xs mt-0.5" style="color: var(--text-muted)">
                                {{ \Carbon\Carbon::parse($log->Date ?? $log->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($activityJournals->hasPages())
                <div class="px-6 py-3" style="border-top: 1px solid var(--border)">
                    {{ $activityJournals->links() }}
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById('avatar-preview');
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush

@endsection
