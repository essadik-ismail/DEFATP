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
        icon="fas fa-user"
    />

    @if(session('success'))
        <x-alert type="success" title="Succès !" dismissible class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" title="Erreur !" dismissible class="mb-6">
            {{ session('error') }}
        </x-alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Summary Card -->
        <div class="lg:col-span-1">
            <div class="rounded-2xl border bg-white p-6" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: var(--primary-gradient); box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600 mb-1 flex items-center justify-center gap-2">
                        <i class="fas fa-id-card text-emerald-500"></i>
                        PPR: {{ $user->ppr }}
                    </p>
                    <p class="text-sm text-gray-500 mb-6">
                        <i class="fas fa-calendar text-emerald-500 mr-1"></i>
                        Membre depuis {{ $user->created_at->format('d/m/Y') }}
                    </p>

                    @if($user->dranef || $user->dpanef || $user->zdtf || $user->dfp || $user->province)
                    <div class="text-left rounded-xl p-4 mb-4" style="background: rgba(242, 246, 243, 0.8); border: 1px solid rgba(154, 179, 163, 0.3);">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Affectation</p>
                        @if($user->dranef)<p class="text-sm text-gray-700"><span class="text-gray-500">DRANEF:</span> {{ $user->dranef->dranef }}</p>@endif
                        @if($user->dpanef)<p class="text-sm text-gray-700"><span class="text-gray-500">DPANEF:</span> {{ $user->dpanef->dpanef ?? $user->dpanef->code }}</p>@endif
                        @if($user->zdtf)<p class="text-sm text-gray-700"><span class="text-gray-500">ZDTF:</span> {{ $user->zdtf->code ?? $user->zdtf->zdtf ?? $user->zdtf->sdtf }}</p>@endif
                        @if($user->dfp)<p class="text-sm text-gray-700"><span class="text-gray-500">DFP:</span> {{ $user->dfp->code ?? $user->dfp->dfp }}</p>@endif
                        @if($user->province)<p class="text-sm text-gray-700"><span class="text-gray-500">Province:</span> {{ $user->province->nom }}</p>@endif
                    </div>
                    @endif

                    <div class="rounded-xl p-4 flex items-center gap-3" style="background: rgba(5, 150, 105, 0.08); border: 1px solid rgba(5, 150, 105, 0.2);">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--primary-gradient);">
                            <i class="fas fa-shield-alt text-white text-sm"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-emerald-800">Compte sécurisé</p>
                            <p class="text-xs text-emerald-600">Votre compte est protégé</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Edit Form -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl border bg-white overflow-hidden" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
                <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color: rgba(154,179,163,0.3);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--primary-gradient);">
                        <i class="fas fa-user-edit text-white text-sm"></i>
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Modifier mon profil</h2>
                </div>

                <form action="{{ route('auth.profile.update') }}" method="POST" class="p-6 space-y-6" data-server-validation>
                    @csrf
                    @method('PUT')

                    <!-- Informations personnelles -->
                    <div class="rounded-xl p-6 border" style="border-color: rgba(154,179,163,0.3); background: rgba(242, 246, 243, 0.5);">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Informations personnelles</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom complet <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                    class="form-input w-full px-4 py-3 border rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="ppr" class="block text-sm font-semibold text-gray-700 mb-2">PPR <span class="text-red-500">*</span></label>
                                <input type="text" id="ppr" name="ppr" value="{{ old('ppr', $user->ppr) }}" required
                                    class="form-input w-full px-4 py-3 border rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                @error('ppr')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Affectation -->
                    <div class="rounded-xl p-6 border" style="border-color: rgba(154,179,163,0.3); background: rgba(242, 246, 243, 0.5);">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                                <i class="fas fa-sitemap text-white text-sm"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Affectation / Entité</h3>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Optionnel : liez votre compte à une DRANEF, DPANEF, ZDTF, DFP ou Province.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="dranef_id" class="block text-sm font-semibold text-gray-700 mb-2">DRANEF</label>
                                <select name="dranef_id" id="dranef_id" class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                    <option value="">— Aucune —</option>
                                    @foreach($dranefs as $d)
                                        <option value="{{ $d->id }}" @selected(old('dranef_id', $user->dranef_id) == $d->id)>{{ $d->dranef }}</option>
                                    @endforeach
                                </select>
                                @error('dranef_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="dpanef_id" class="block text-sm font-semibold text-gray-700 mb-2">DPANEF</label>
                                <select name="dpanef_id" id="dpanef_id" class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                    <option value="">— Aucune —</option>
                                    @foreach($dpanefs as $d)
                                        <option value="{{ $d->id }}" @selected(old('dpanef_id', $user->dpanef_id) == $d->id)>{{ $d->dpanef ?? $d->code }}</option>
                                    @endforeach
                                </select>
                                @error('dpanef_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="zdtf_id" class="block text-sm font-semibold text-gray-700 mb-2">ZDTF</label>
                                <select name="zdtf_id" id="zdtf_id" class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                    <option value="">— Aucune —</option>
                                    @foreach($zdtfs as $z)
                                        <option value="{{ $z->id }}" @selected(old('zdtf_id', $user->zdtf_id) == $z->id)>{{ $z->code ?? $z->zdtf ?? $z->sdtf }}</option>
                                    @endforeach
                                </select>
                                @error('zdtf_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="dfp_id" class="block text-sm font-semibold text-gray-700 mb-2">DFP</label>
                                <select name="dfp_id" id="dfp_id" class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                    <option value="">— Aucune —</option>
                                    @foreach($dfps as $d)
                                        <option value="{{ $d->id }}" @selected(old('dfp_id', $user->dfp_id) == $d->id)>{{ $d->code ?? $d->dfp }}</option>
                                    @endforeach
                                </select>
                                @error('dfp_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="province_id" class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                                <select name="province_id" id="province_id" class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" style="border-color: rgba(154,179,163,0.5);">
                                    <option value="">— Aucune —</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p->id }}" @selected(old('province_id', $user->province_id) == $p->id)>{{ $p->nom }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="rounded-xl p-6 border" style="border-color: rgba(217, 119, 6, 0.3); background: rgba(255, 251, 235, 0.5);">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center">
                                <i class="fas fa-lock text-white text-sm"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Changer le mot de passe</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" placeholder="Laissez vide pour ne pas modifier"
                                    class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" style="border-color: rgba(154,179,163,0.5);">
                                @error('current_password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
                                <input type="password" id="new_password" name="new_password" placeholder="Nouveau mot de passe"
                                    class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" style="border-color: rgba(154,179,163,0.5);">
                                @error('new_password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmer</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirmer le mot de passe"
                                    class="form-input w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" style="border-color: rgba(154,179,163,0.5);">
                            </div>
                        </div>
                        <div class="flex items-start gap-3 mt-4 p-3 rounded-lg bg-amber-50 border border-amber-200">
                            <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                            <p class="text-xs text-amber-800">Laissez les champs vides si vous ne souhaitez pas modifier le mot de passe.</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap items-center gap-3 pt-4 border-t" style="border-color: rgba(154,179,163,0.3);">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-white transition-all hover:shadow-md" style="background: var(--primary-gradient); box-shadow: var(--shadow-md);">
                            <i class="fas fa-save"></i>
                            Mettre à jour
                        </button>
                        <a href="{{ route('cessions.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-server-validation]');
    if (form) {
        form.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', function() {
                this.classList.toggle('is-invalid', this.hasAttribute('required') && !this.value.trim());
                if (this.value.trim()) this.classList.add('is-valid');
            });
        });
        form.addEventListener('submit', function(e) {
            const required = form.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            required.forEach(f => {
                if (!f.value.trim()) { f.classList.add('is-invalid'); valid = false; }
                else f.classList.remove('is-invalid');
            });
            if (!valid) { e.preventDefault(); alert('Veuillez remplir tous les champs obligatoires.'); }
        });
    }
});
</script>
@endpush
