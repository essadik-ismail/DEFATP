@extends('layouts.app')

@section('title', 'Mon Profil')

@section('breadcrumb')
<li class="bc-item active">Mon profil</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Mon profil"
        subtitle="Gérez vos informations personnelles et paramètres de compte"
        icon="fas fa-user-circle"
    />

    <x-validation-errors />

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
            @if($user->dranef || $user->dpanef || $user->zdtf || $user->dfp || $user->province || $user->commune)
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
                    @if($user->commune)
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color: var(--text-muted)">Commune</span>
                        <span class="text-xs font-medium" style="color: var(--text)">{{ $user->commune->nom }}</span>
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

                        {{-- DRANEF --}}
                        <div>
                            <label for="aff_dranef" class="form-label">DRANEF</label>
                            <select id="aff_dranef" name="dranef_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach($dranefs as $d)
                                    <option value="{{ $d->id }}"
                                            data-code="{{ $d->code }}"
                                            @selected(old('dranef_id', $user->dranef_id) == $d->id)>
                                        {{ $d->dranef }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dranef_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- DPANEF — filtered by DRANEF --}}
                        <div>
                            <label for="aff_dpanef" class="form-label">DPANEF</label>
                            <select id="aff_dpanef" name="dpanef_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach($dpanefs as $d)
                                    <option value="{{ $d->id }}"
                                            data-dranef-id="{{ $d->dranef_id }}"
                                            data-dranef-code="{{ $d->dranef_code }}"
                                            data-code="{{ $d->code }}"
                                            @selected(old('dpanef_id', $user->dpanef_id) == $d->id)>
                                        {{ $d->dpanef ?? $d->code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dpanef_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- ZDTF — filtered by DPANEF --}}
                        <div>
                            <label for="aff_zdtf" class="form-label">ZDTF</label>
                            <select id="aff_zdtf" name="zdtf_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach($zdtfs as $z)
                                    <option value="{{ $z->id }}"
                                            data-dpanef-id="{{ $z->dpanef_id }}"
                                            data-dpanef-code="{{ $z->dpanef_code }}"
                                            @selected(old('zdtf_id', $user->zdtf_id) == $z->id)>
                                        {{ $z->code ?? $z->zdtf ?? $z->sdtf }}
                                    </option>
                                @endforeach
                            </select>
                            @error('zdtf_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- DFP — filtered by DPANEF --}}
                        <div>
                            <label for="aff_dfp" class="form-label">DFP</label>
                            <select id="aff_dfp" name="dfp_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach($dfps as $d)
                                    <option value="{{ $d->id }}"
                                            data-dpanef-id="{{ $d->dpanef?->id }}"
                                            data-dpanef-code="{{ $d->dpanef_code }}"
                                            data-zdtf-code="{{ $d->zdtf_code }}"
                                            @selected(old('dfp_id', $user->dfp_id) == $d->id)>
                                        {{ $d->code ?? $d->dfp }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dfp_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Province — independent --}}
                        <div>
                            <label for="aff_province" class="form-label">Province</label>
                            <select id="aff_province" name="province_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->id }}" @selected(old('province_id', $user->province_id) == $p->id)>
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Commune — filtered by Province --}}
                        <div>
                            <label for="aff_commune" class="form-label">Commune</label>
                            <select id="aff_commune" name="commune_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach($communes as $c)
                                    <option value="{{ $c->id }}"
                                            data-province-id="{{ $c->province_id }}"
                                            @selected(old('commune_id', $user->commune_id) == $c->id)>
                                        {{ $c->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('commune_id')<p class="form-error">{{ $message }}</p>@enderror
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

// ── Affectation cascading dropdowns ──────────────────────────────────────────
(function () {
    const selDranef  = document.getElementById('aff_dranef');
    const selDpanef  = document.getElementById('aff_dpanef');
    const selZdtf    = document.getElementById('aff_zdtf');
    const selDfp     = document.getElementById('aff_dfp');
    const selProvince = document.getElementById('aff_province');
    const selCommune  = document.getElementById('aff_commune');

    if (!selDranef) return;

    // Cache all non-placeholder options on page load
    const allDpanefOpts  = Array.from(selDpanef.querySelectorAll('option[value]:not([value=""])'));
    const allZdtfOpts    = Array.from(selZdtf.querySelectorAll('option[value]:not([value=""])'));
    const allDfpOpts     = Array.from(selDfp.querySelectorAll('option[value]:not([value=""])'));
    const allCommuneOpts = Array.from(selCommune.querySelectorAll('option[value]:not([value=""])'));

    function filterOptions(select, allOpts, matchFn) {
        const currentVal = select.value;
        // Remove all non-placeholder options then re-add matching ones
        allOpts.forEach(opt => opt.remove());
        const matching = allOpts.filter(matchFn);
        matching.forEach(opt => select.appendChild(opt));
        // Restore previous value if still available
        if (matching.some(o => o.value === currentVal)) {
            select.value = currentVal;
        } else {
            select.value = '';
        }
    }

    function getSelectedDranefCode() {
        const sel = selDranef.options[selDranef.selectedIndex];
        return sel ? sel.dataset.code : null;
    }

    function getSelectedDpanefCode() {
        const sel = selDpanef.options[selDpanef.selectedIndex];
        return sel ? sel.dataset.code : null;
    }

    function applyDranefFilter() {
        const dranefId   = selDranef.value;
        const dranefCode = getSelectedDranefCode();

        filterOptions(selDpanef, allDpanefOpts, function (opt) {
            if (!dranefId) return true; // no filter when no DRANEF selected
            return opt.dataset.dranefId === dranefId
                || (dranefCode && opt.dataset.dranefCode === dranefCode);
        });

        // Changing DRANEF may change DPANEF selection → cascade further
        applyDpanefFilter();
    }

    function applyDpanefFilter() {
        const dpanefId   = selDpanef.value;
        const dpanefCode = getSelectedDpanefCode();

        filterOptions(selZdtf, allZdtfOpts, function (opt) {
            if (!dpanefId) return true;
            return opt.dataset.dpanefId === dpanefId
                || (dpanefCode && opt.dataset.dpanefCode === dpanefCode);
        });

        filterOptions(selDfp, allDfpOpts, function (opt) {
            if (!dpanefId) return true;
            return opt.dataset.dpanefId === dpanefId
                || (dpanefCode && opt.dataset.dpanefCode === dpanefCode);
        });
    }

    function applyProvinceFilter() {
        const provinceId = selProvince.value;
        filterOptions(selCommune, allCommuneOpts, function (opt) {
            if (!provinceId) return true;
            return opt.dataset.provinceId === provinceId;
        });
    }

    selDranef.addEventListener('change', applyDranefFilter);
    selDpanef.addEventListener('change', applyDpanefFilter);
    selProvince.addEventListener('change', applyProvinceFilter);

    // Apply filters on page load to reflect saved values
    applyDranefFilter();
    applyProvinceFilter();
})();
</script>
@endpush

@endsection
