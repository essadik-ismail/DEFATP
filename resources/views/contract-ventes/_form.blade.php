@php
    $contract = $contractVente ?? null;
    $isValidated = (bool) $contract?->is_validated;

    $storedCharges = collect(
        $charges ??
            ($contract?->chargeApayer
                ?->filter(fn($charge) => !str_starts_with($charge->nom, 'Tranche'))
                ->values()
                ->all() ??
                []),
    );

    $storedTranches = collect(
        $tranches ??
            ($contract?->chargeApayer
                ?->filter(fn($charge) => str_starts_with($charge->nom, 'Tranche'))
                ->sortBy(function ($charge) {
                    preg_match('/Tranche (\d+)/', $charge->nom, $matches);
                    return isset($matches[1]) ? (int) $matches[1] : 0;
                })
                ->values()
                ->all() ??
                []),
    );

    $formatDateValue = function ($value) {
        if (blank($value)) {
            return '';
        }
        try {
            return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return $value;
        }
    };

    $readChargeValue = function ($charge, string $field) use ($formatDateValue) {
        if (!$charge) {
            return '';
        }
        $value = is_array($charge) ? $charge[$field] ?? '' : data_get($charge, $field);
        if ($field === 'date_echeance' && !blank($value)) {
            return $formatDateValue($value);
        }
        return $value;
    };

    $findCharge = function (array $terms) use ($storedCharges) {
        return $storedCharges->first(function ($charge) use ($terms) {
            $name = strtolower((string) (is_array($charge) ? $charge['nom'] ?? '' : $charge->nom ?? ''));
            foreach ($terms as $term) {
                if (str_contains($name, strtolower($term))) {
                    return true;
                }
            }
            return false;
        });
    };

    $cautionCharge = $findCharge(['caution']);
    $fnfCharge = $findCharge(['fnf']);
    $fraisAdjCharge = $findCharge(["frais d'adjudication", 'frais adjudication']);
    $taxeProvCharge = $findCharge(['taxe provinciale']);
    $taxeRefectionCharge = $findCharge(['refection']);
    $serviceANEFCharge = $findCharge(["service rendu par l'anef", 'service rendu']);

    $prixVenteValue = (float) old('prix_vente', $contract->prix_vente ?? 0);
    $selectedExploitantId = old('exploitant_id', $contract->exploitant_id ?? '');
    $selectedExploitant = collect($exploitants ?? [])->first(
        fn($e) => (string) $e->id === (string) $selectedExploitantId,
    );
    $initialCartePro = old('exploitant_carte_search', $selectedExploitant?->numero ?? '');

    $trancheRows = collect(
        old(
            'tranches',
            $storedTranches
                ->map(
                    fn($t) => [
                        'montant' => $readChargeValue($t, 'montant'),
                        'date_echeance' => $readChargeValue($t, 'date_echeance'),
                    ],
                )
                ->all(),
        ),
    );

    $allowedTrancheCounts = [1, 2, 4];
    $rawNombreTranche = (int) old('nombre_tranche', $contract->nombre_tranche ?? 0);
    $storedNombreTranche = (int) $trancheRows->count();
    $nombreTranche = in_array($rawNombreTranche, $allowedTrancheCounts, true)
        ? $rawNombreTranche
        : (in_array($storedNombreTranche, $allowedTrancheCounts, true)
            ? $storedNombreTranche
            : 1);

    if ($trancheRows->isEmpty()) {
        $trancheRows = collect(range(1, $nombreTranche))->map(fn() => ['montant' => '', 'date_echeance' => '']);
    }

    $existingTranches = $trancheRows
        ->values()
        ->map(function ($t) use ($readChargeValue) {
            return [
                'montant' => is_array($t) ? $t['montant'] ?? '' : $readChargeValue($t, 'montant'),
                'date_echeance' => is_array($t) ? $t['date_echeance'] ?? '' : $readChargeValue($t, 'date_echeance'),
            ];
        })
        ->all();

    $isEditing = filled($contract?->id);
    $modeCession = $article->cession?->mode_cession ?? ($contract?->type ?? null);

    $dateAdjValue = $formatDateValue($article->cession?->DateAdj ?? $contract?->date_adjudication);
    $dateExpirationValue = $formatDateValue($contract?->date_expiration);

    $dateLimiteTaxesValue = $formatDateValue(old('date_limite_taxes', $contract?->date_limite_taxes));
    $dateLimiteTrancheValue = $formatDateValue(old('date_limite_tranche', $contract?->date_limite_tranche));

    // Determine if current user can create exploitant (Région / Central)
    $canCreateExploitant = in_array(
        \Illuminate\Support\Facades\Auth::user()?->role?->value ?? \Illuminate\Support\Facades\Auth::user()?->role,
        ['admin', 'central', 'dranef', 'zdtf', 'zdtfdpanef'],
    );

    // Article-sourced readonly values
    $articleRefectionMontant = $article->taxe_refection_chemins ?? 0;
    $articleRefectionEcheance = $formatDateValue($article->date_echeance_taxe_refection_chemins);
    $articleANEFMontant = $article->service_rendu_anef ?? 0;
    $articleANEFEcheance = $formatDateValue($article->date_echeance_service_rendu_anef);
    $articleBoisVolume = $article->bois_chauffage_volume ?? '';
    $articleBoisDestination = $article->bois_chauffage_destination ?? '';
    $articleBoisDateLivraison = $formatDateValue($article->date_livraison_bois_chauffage);
@endphp

@if ($isValidated)
    <div class="mb-4 rounded-xl border border-amber-300 bg-amber-50 p-4 flex items-center gap-3">
        <i class="fas fa-lock text-amber-600"></i>
        <div>
            <p class="text-sm font-semibold text-amber-800">Contrat valid&eacute; — lecture seule</p>
            <p class="text-xs text-amber-600">Valid&eacute; le {{ $contract->validated_at?->format('d/m/Y à H:i') }}. Les
                modifications sont verrouill&eacute;es.</p>
        </div>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" id="contractVenteForm" class="space-y-6">
    @csrf
    @if (($formMethod ?? 'POST') !== 'POST')
        @method($formMethod)
    @endif

    <x-validation-errors />

    <div class="rounded-2xl border bg-white p-6 md:p-8"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">

        {{-- Header banner --}}
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50/80 p-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                        <i class="fas fa-file-contract text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">
                            {{ $isEditing ? 'Mise à jour du contrat de vente' : 'Création du contrat de vente' }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Article #{{ $article->numero ?? $article->id }} &mdash;
                            {{ $modeCession ? \Illuminate\Support\Str::headline($modeCession) : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             Section 1 — Conditions contractuelles
        ══════════════════════════════════════ --}}
        <x-form-section number="1" title="Conditions contractuelles" icon="fas fa-file-signature" color="green">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div class="form-group">
                    <label for="duree_decheache" class="mb-2 block text-sm font-semibold text-gray-700">
                        Dur&eacute;e de contrat (en mois) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="duree_decheache" name="duree_decheache"
                        value="{{ old('duree_decheache', $contract->duree_decheache ?? '') }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $isValidated ? 'bg-gray-100' : '' }}"
                        placeholder="Ex: 12" min="1" required {{ $isValidated ? 'readonly' : '' }}>
                    @error('duree_decheache')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_expiration_display" class="mb-2 block text-sm font-semibold text-gray-700">
                        Date d&apos;expiration
                    </label>
                    <input type="date" id="date_expiration_display" value="{{ $dateExpirationValue }}"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                    {{-- date expiration is computed server-side --}}
                </div>

                <div class="form-group">
                    <label for="prix_vente" class="mb-2 block text-sm font-semibold text-gray-700">
                        Prix de vente (DH) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="prix_vente" name="prix_vente" step="0.01" min="0"
                        value="{{ old('prix_vente', $contract->prix_vente ?? '') }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $isValidated ? 'bg-gray-100' : '' }}"
                        placeholder="0.00" required {{ $isValidated ? 'readonly' : '' }}>
                    @error('prix_vente')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="percepteur" class="mb-2 block text-sm font-semibold text-gray-700">
                        Percepteur
                    </label>
                    <input type="text" id="percepteur" name="percepteur"
                        value="{{ old('percepteur', $contract->percepteur ?? '') }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $isValidated ? 'bg-gray-100' : '' }}"
                        placeholder="Nom du percepteur" {{ $isValidated ? 'readonly' : '' }}>
                    @error('percepteur')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </x-form-section>

        {{-- ══════════════════════════════════════
             Section 2 — Informations sur l'adjudicataire
        ══════════════════════════════════════ --}}
        <x-form-section number="2" title="Informations sur l&apos;adjudicataire" icon="fas fa-user-tie"
            color="blue">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="exploitant_carte_search" class="mb-2 block text-sm font-semibold text-gray-700">
                        N&deg; carte professionnelle <span class="text-red-500">*</span>
                    </label>
                    <input type="hidden" id="exploitant_id" name="exploitant_id" value="{{ $selectedExploitantId }}">
                    <select id="exploitant_lookup" class="hidden" aria-hidden="true" tabindex="-1">
                        <option value="">S&eacute;lectionner</option>
                        @foreach ($exploitants as $exploitant)
                            <option value="{{ $exploitant->id }}" data-carte="{{ $exploitant->numero }}"
                                data-name="{{ $exploitant->nom_complet }}"
                                data-raison="{{ $exploitant->raison_sociale }}"
                                data-numero="{{ $exploitant->numero }}" data-adresse="{{ $exploitant->adresse }}"
                                data-categorie="{{ $exploitant->categorie }}" @selected((string) $selectedExploitantId === (string) $exploitant->id)>
                                {{ $exploitant->nom_complet }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex gap-3">
                        <input type="text" id="exploitant_carte_search" name="exploitant_carte_search"
                            value="{{ $initialCartePro }}"
                            class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $isValidated ? 'bg-gray-100' : '' }}"
                            placeholder="Saisir le n° de carte professionnelle" autocomplete="off"
                            {{ $isValidated ? 'readonly' : '' }}>
                        @if (!$isValidated)
                            <button type="button" id="load_exploitant_btn"
                                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
                                Rechercher
                            </button>
                        @endif
                    </div>
                    <div id="exploitant_lookup_feedback" class="mt-2 text-sm text-gray-500" aria-live="polite">
                        Saisissez le num&eacute;ro de carte professionnelle puis cliquez sur Rechercher.
                    </div>
                    @if (!$isValidated && $canCreateExploitant)
                        <div id="create_exploitant_hint" class="mt-2 hidden">
                            <a href="{{ route('exploitants.create') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700 transition-colors">
                                <i class="fas fa-user-plus"></i> Cr&eacute;er adjudicataire
                            </a>
                        </div>
                    @endif
                    @error('exploitant_id')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nom complet</label>
                    <input type="text" id="exploitant_nom_complet"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Raison sociale</label>
                    <input type="text" id="exploitant_raison"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Adresse</label>
                    <input type="text" id="exploitant_adresse"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Cat&eacute;gorie</label>
                    <input type="text" id="exploitant_categorie"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>
            </div>
        </x-form-section>

        {{-- ══════════════════════════════════════
             Section 3 — Taxes et charges
        ══════════════════════════════════════ --}}
        <x-form-section number="3" title="Taxes et charges" icon="fas fa-calculator" color="yellow">

            <div class="space-y-4">

                {{-- Date limite globale pour les taxes --}}
                <div class="rounded-xl border border-yellow-200 bg-yellow-50/60 p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-calendar-exclamation mt-0.5 text-yellow-600"></i>
                        <div class="flex-1">
                            <label for="date_limite_taxes" class="mb-1 block text-sm font-semibold text-gray-700">
                                Date limite &mdash; Taxes et charges
                            </label>
                            <p class="mb-2 text-xs text-gray-500">Appliqu&eacute;e automatiquement &agrave; toutes les
                                taxes. Chaque date reste modifiable individuellement.</p>
                            <input type="date" id="date_limite_taxes" name="date_limite_taxes"
                                value="{{ $dateLimiteTaxesValue }}"
                                class="form-input w-full max-w-xs rounded-xl border border-yellow-300 px-4 py-2.5 focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 {{ $isValidated ? 'bg-gray-100' : '' }}"
                                {{ $isValidated ? 'readonly' : '' }}>
                            @error('date_limite_taxes')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Cautionnement définitif 10% --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Cautionnement d&eacute;finitif (10%)
                            </label>
                            <div class="relative">
                                <input type="number" id="charge_cautionnement" name="charges[0][montant]"
                                    step="0.01" value="{{ number_format($prixVenteValue * 0.1, 2, '.', '') }}"
                                    class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12"
                                    readonly>
                            </div>
                            <input type="hidden" name="charges[0][nom]" value="Cautionnement définitif">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance <span class="text-red-500">*</span></label>
                            <input type="date" name="charges[0][date_echeance]"
                                value="{{ old('charges.0.date_echeance', $readChargeValue($cautionCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none {{ $isValidated ? 'bg-gray-100' : '' }}"
                                required {{ $isValidated ? 'readonly' : '' }}>
                            @error('charges.0.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Taxe FNF 20% --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe FNF (20%)</label>
                            <div class="relative">
                                <input type="number" id="charge_taxe_fnf" name="charges[1][montant]" step="0.01"
                                    value="{{ number_format($prixVenteValue * 0.2, 2, '.', '') }}"
                                    class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12"
                                    readonly>
                            </div>
                            <input type="hidden" name="charges[1][nom]" value="Taxe FNF">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance <span class="text-red-500">*</span></label>
                            <input type="date" name="charges[1][date_echeance]"
                                value="{{ old('charges.1.date_echeance', $readChargeValue($fnfCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none {{ $isValidated ? 'bg-gray-100' : '' }}"
                                required {{ $isValidated ? 'readonly' : '' }}>
                            @error('charges.1.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Frais d'adjudication 1.6% --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Frais d&apos;adjudication
                                (1.6%)</label>
                            <div class="relative">
                                <input type="number" id="charge_frais_adjudication" name="charges[2][montant]"
                                    step="0.01" value="{{ number_format($prixVenteValue * 0.016, 2, '.', '') }}"
                                    class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12"
                                    readonly>
                            </div>
                            <input type="hidden" name="charges[2][nom]" value="Frais d'adjudication">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance <span class="text-red-500">*</span></label>
                            <input type="date" name="charges[2][date_echeance]"
                                value="{{ old('charges.2.date_echeance', $readChargeValue($fraisAdjCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none {{ $isValidated ? 'bg-gray-100' : '' }}"
                                required {{ $isValidated ? 'readonly' : '' }}>
                            @error('charges.2.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Taxe provinciale 10% --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe provinciale
                                (10%)</label>
                            <div class="relative">
                                <input type="number" id="charge_taxe_provinciale" name="charges[3][montant]"
                                    step="0.01" value="{{ number_format($prixVenteValue * 0.1, 2, '.', '') }}"
                                    class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12"
                                    readonly>
                            </div>
                            <input type="hidden" name="charges[3][nom]" value="Taxe provinciale">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance <span class="text-red-500">*</span></label>
                            <input type="date" name="charges[3][date_echeance]"
                                value="{{ old('charges.3.date_echeance', $readChargeValue($taxeProvCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none {{ $isValidated ? 'bg-gray-100' : '' }}"
                                required {{ $isValidated ? 'readonly' : '' }}>
                            @error('charges.3.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Réfection des chemins forestiers — from Article, readonly --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe r&eacute;fection des
                                chemins forestiers</label>
                            <div class="relative">
                                <input type="number" id="charge_taxe_refection" name="charges[4][montant]"
                                    step="0.01"
                                    value="{{ old('charges.4.montant', $readChargeValue($taxeRefectionCharge, 'montant') ?: $articleRefectionMontant) }}"
                                    class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12"
                                    readonly>
                            </div>
                            <input type="hidden" name="charges[4][nom]"
                                value="Taxe réfection des chemins forestiers">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance <span class="text-red-500">*</span></label>
                            <input type="date" name="charges[4][date_echeance]"
                                value="{{ old('charges.4.date_echeance', $readChargeValue($taxeRefectionCharge, 'date_echeance') ?: $articleRefectionEcheance) }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 focus:outline-none"
                                readonly>
                            @error('charges.4.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Service rendu par l'ANEF — from Article, readonly --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe service rendu
                                ANEF</label>
                            <div class="relative">
                                <input type="number" id="charge_service_anef" name="charges[5][montant]"
                                    step="0.01"
                                    value="{{ old('charges.5.montant', $readChargeValue($serviceANEFCharge, 'montant') ?: $articleANEFMontant) }}"
                                    class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12"
                                    readonly>
                            </div>
                            <input type="hidden" name="charges[5][nom]" value="Taxe service rendu ANEF">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance <span class="text-red-500">*</span></label>
                            <input type="date" name="charges[5][date_echeance]"
                                value="{{ old('charges.5.date_echeance', $readChargeValue($serviceANEFCharge, 'date_echeance') ?: $articleANEFEcheance) }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 focus:outline-none"
                                readonly>
                            @error('charges.5.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Fourniture bois de chauffage — from Article, readonly --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4 shadow-sm">
                    <input type="hidden" name="bois_chauffage_volume_st" value="{{ $articleBoisVolume }}">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Volume bois de chauffage
                                (st)</label>
                            <input type="text" value="{{ $articleBoisVolume ?: '—' }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Destination</label>
                            <input type="text" value="{{ $articleBoisDestination ?: '—' }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date de livraison</label>
                            <input type="date" value="{{ $articleBoisDateLivraison }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                        </div>
                    </div>
                </div>

            </div>
        </x-form-section>

        {{-- ══════════════════════════════════════
             Section 4 — Tranches de paiement
        ══════════════════════════════════════ --}}
        <x-form-section number="4" title="Tranches de paiement" icon="fas fa-calendar-alt" color="purple">

            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="nombre_tranche" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nombre de tranches <span class="text-red-500">*</span>
                    </label>
                    <select id="nombre_tranche" name="nombre_tranche"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $isValidated ? 'bg-gray-100' : '' }}"
                        required {{ $isValidated ? 'disabled' : '' }}>
                        <option value="1" @selected($nombreTranche === 1)>1</option>
                        <option value="2" @selected($nombreTranche === 2)>2</option>
                        <option value="4" @selected($nombreTranche === 4)>4</option>
                    </select>
                    @if ($isValidated)
                        <input type="hidden" name="nombre_tranche" value="{{ $nombreTranche }}">
                    @endif
                    @error('nombre_tranche')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_limite_tranche" class="mb-1 block text-sm font-semibold text-gray-700">
                        Date limite &mdash; Tranches de paiement
                    </label>
                    <input type="date" id="date_limite_tranche" name="date_limite_tranche"
                        value="{{ $dateLimiteTrancheValue }}"
                        class="form-input w-full rounded-xl border border-purple-300 px-4 py-2.5 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 {{ $isValidated ? 'bg-gray-100' : '' }}"
                        {{ $isValidated ? 'readonly' : '' }}>
                    @error('date_limite_tranche')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <p class="mb-4 text-sm text-gray-500">
                Montant par tranche calcul&eacute; automatiquement (prix de vente &div; nombre de tranches).
            </p>

            <div id="tranches_container" class="space-y-4"></div>
        </x-form-section>

        {{-- Action buttons --}}
        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 pt-6">
            <a href="{{ $isEditing && $contract ? route('contract-ventes.show', [$article, $contract]) : route('articles.show', $article) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition-all hover:border-emerald-300 hover:text-emerald-700">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Retour</span>
            </a>

            @if ($isValidated && $contract)
                <a href="{{ route('contract-ventes.show', [$article, $contract]) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-eye"></i>
                    <span>Consulter contrat</span>
                </a>
            @else
                <button type="submit"
                    class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:-translate-y-0.5 hover:from-emerald-700 hover:to-emerald-800">
                    <i class="fas fa-save"></i>
                    <span>{{ $submitLabel }}</span>
                </button>

                @if ($isEditing && $contract)
                    <button type="button" id="btn_valider_contrat"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-700 to-green-800 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:-translate-y-0.5">
                        <i class="fas fa-check-circle"></i>
                        <span>Valider le contrat</span>
                    </button>
                @endif
            @endif
        </div>
    </div>
</form>

{{-- Confirmation popup for validation --}}
@if (!$isValidated && $isEditing && $contract)
    <div id="modal_valider" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                    <i class="fas fa-exclamation-triangle text-amber-600"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">Attention : action irr&eacute;versible</h3>
            </div>
            <p class="mb-2 text-sm text-gray-700">Une fois valid&eacute;, ce contrat ne pourra plus &ecirc;tre
                modifi&eacute;.</p>
            <p class="mb-6 text-sm font-semibold text-gray-800">Confirmez-vous la validation ?</p>
            <div class="flex gap-3 justify-end">
                <button type="button" id="btn_annuler_validation"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <form method="POST" action="{{ route('contract-ventes.validate', [$article, $contract]) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-5 py-2 text-sm font-semibold text-white hover:bg-green-800">
                        <i class="fas fa-check"></i> Confirmer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isValidated = {{ $isValidated ? 'true' : 'false' }};
            const exploitantIdInput = document.getElementById('exploitant_id');
            const exploitantLookup = document.getElementById('exploitant_lookup');
            const carteSearchInput = document.getElementById('exploitant_carte_search');
            const loadBtn = document.getElementById('load_exploitant_btn');
            const feedbackEl = document.getElementById('exploitant_lookup_feedback');
            const createHint = document.getElementById('create_exploitant_hint');
            const prixVenteInput = document.getElementById('prix_vente');
            const nombreTrancheInput = document.getElementById('nombre_tranche');
            const tranchesContainer = document.getElementById('tranches_container');
            const dureeInput = document.getElementById('duree_decheache');
            const dateExpiDisplay = document.getElementById('date_expiration_display');
            const dateAdjValue = '{{ $dateAdjValue }}';
            const existingTranches = @json($existingTranches);
            const dateLimiteTaxesInput = document.getElementById('date_limite_taxes');
            const dateLimiteTrancheInput = document.getElementById('date_limite_tranche');

            // Modal
            const btnValider = document.getElementById('btn_valider_contrat');
            const modalValider = document.getElementById('modal_valider');
            const btnAnnuler = document.getElementById('btn_annuler_validation');

            function openModal() {
                if (modalValider) {
                    modalValider.classList.remove('hidden');
                    modalValider.style.display = 'flex';
                }
            }

            function closeModal() {
                if (modalValider) {
                    modalValider.classList.add('hidden');
                    modalValider.style.display = '';
                }
            }

            if (btnValider) btnValider.addEventListener('click', openModal);
            if (btnAnnuler) btnAnnuler.addEventListener('click', closeModal);
            if (modalValider) modalValider.addEventListener('click', e => {
                if (e.target === modalValider) closeModal();
            });

            // Adjudicataire search by carte professionnelle
            function normalize(v) {
                return String(v || '').replace(/[^a-z0-9]/gi, '').toUpperCase();
            }

            function setFeedback(msg, tone) {
                if (!feedbackEl) return;
                feedbackEl.textContent = msg || '';
                feedbackEl.className = 'mt-2 text-sm ' + (tone === 'success' ? 'text-emerald-600' : tone ===
                    'error' ? 'text-red-500' : 'text-gray-500');
            }

            function getOptions() {
                return exploitantLookup ? Array.from(exploitantLookup.options).filter(o => o.value) : [];
            }

            function populateFields(option) {
                const map = {
                    exploitant_nom_complet: 'name',
                    exploitant_raison: 'raison',
                    exploitant_adresse: 'adresse',
                    exploitant_categorie: 'categorie'
                };
                Object.entries(map).forEach(([id, key]) => {
                    const el = document.getElementById(id);
                    if (el) el.value = option ? (option.dataset[key] || '') : '';
                });
                if (carteSearchInput && option && option.dataset.carte) {
                    carteSearchInput.value = option.dataset.carte;
                }
            }

            function searchByCarte() {
                const val = carteSearchInput ? carteSearchInput.value : '';
                const norm = normalize(val);
                if (!norm) {
                    if (exploitantIdInput) exploitantIdInput.value = '';
                    populateFields(null);
                    setFeedback('Saisissez un numéro de carte professionnelle.', 'error');
                    if (createHint) createHint.classList.remove('hidden');
                    return;
                }
                const match = getOptions().find(o => normalize(o.dataset.carte) === norm || normalize(o.dataset
                    .numero) === norm);
                if (!match) {
                    if (exploitantIdInput) exploitantIdInput.value = '';
                    populateFields(null);
                    setFeedback('Aucune personne trouvée avec ce numéro. Veuillez contacter la DRANEF pour procéder à son ajout.', 'error');
                    if (createHint) createHint.classList.remove('hidden');
                    return;
                }
                if (exploitantIdInput) exploitantIdInput.value = match.value;
                if (exploitantLookup) exploitantLookup.value = match.value;
                populateFields(match);
                if (createHint) createHint.classList.add('hidden');
                setFeedback('Adjudicataire chargé avec succès.', 'success');
            }

            function initExploitant() {
                if (!exploitantIdInput || !exploitantIdInput.value) {
                    populateFields(null);
                    return;
                }
                const sel = getOptions().find(o => o.value === exploitantIdInput.value);
                if (sel) {
                    populateFields(sel);
                    if (carteSearchInput && !carteSearchInput.value && sel.dataset.carte) {
                        carteSearchInput.value = sel.dataset.carte;
                    }
                    setFeedback('Adjudicataire chargé.', 'success');
                }
            }

            function setVal(id, v) {
                const el = document.getElementById(id);
                if (el) el.value = v;
            }

            function calcCharges() {
                const p = parseFloat(prixVenteInput ? prixVenteInput.value : 0) || 0;
                setVal('charge_cautionnement', (p * 0.10).toFixed(2));
                setVal('charge_taxe_fnf', (p * 0.20).toFixed(2));
                setVal('charge_frais_adjudication', (p * 0.016).toFixed(2));
                setVal('charge_taxe_provinciale', (p * 0.10).toFixed(2));
            }

            function calcDateExpiration() {
                if (!dureeInput || !dateExpiDisplay || !dateAdjValue) return;
                const duree = parseInt(dureeInput.value, 10);
                if (!duree || duree <= 0) {
                    dateExpiDisplay.value = '';
                    return;
                }
                const d = new Date(dateAdjValue);
                if (isNaN(d.getTime())) return;
                d.setMonth(d.getMonth() + duree);
                dateExpiDisplay.value = d.toISOString().slice(0, 10);
            }

            function buildTrancheRow(i, amount, dateEch) {
                const ro = isValidated ? ' readonly' : '';
                const bg = isValidated ? ' bg-gray-100' : '';
                const finalDate = dateEch || '';
                return `<div class="tranche-row rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
  <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
    <div>
      <label class="mb-2 block text-sm font-semibold text-gray-700">Montant tranche ${i + 1} (DH)</label>
      <div class="relative">
        <input type="number" name="tranches[${i}][montant]" value="${amount}" step="0.01" readonly
          class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 pr-12">
      </div>
    </div>
    <div>
      <label class="mb-2 block text-sm font-semibold text-gray-700">Date d'échéance <span class="text-red-500">*</span></label>
      <input type="date" name="tranches[${i}][date_echeance]" value="${finalDate}"
        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none${bg}"${ro}>
    </div>
  </div>
</div>`;
            }

            function getCurrentTranches() {
                if (!tranchesContainer) return [];
                return Array.from(tranchesContainer.querySelectorAll('.tranche-row')).map(row => ({
                    date_echeance: (row.querySelector('input[name$="[date_echeance]"]') || {}).value ||
                        '',
                }));
            }

            function generateTranches() {
                if (!nombreTrancheInput || !tranchesContainer) return;
                const cur = getCurrentTranches();
                const n = parseInt(nombreTrancheInput.value, 10) || 1;
                const p = parseFloat(prixVenteInput ? prixVenteInput.value : 0) || 0;
                const amt = n > 0 ? (p / n).toFixed(2) : '0.00';
                let html = '';
                for (let i = 0; i < n; i++) {
                    const ex = cur[i] || existingTranches[i] || {};
                    html += buildTrancheRow(i, amt, ex.date_echeance || '');
                }
                tranchesContainer.innerHTML = html;
            }

            // Event listeners
            if (loadBtn) loadBtn.addEventListener('click', searchByCarte);
            if (carteSearchInput && !isValidated) {
                carteSearchInput.addEventListener('keydown', e => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchByCarte();
                    }
                });
                carteSearchInput.addEventListener('input', () => {
                    if (createHint) createHint.classList.add('hidden');
                });
            }
            if (prixVenteInput && !isValidated) {
                prixVenteInput.addEventListener('input', () => {
                    calcCharges();
                    generateTranches();
                });
            }
            if (nombreTrancheInput && !isValidated) {
                nombreTrancheInput.addEventListener('change', generateTranches);
            }
            if (dureeInput && !isValidated) {
                dureeInput.addEventListener('input', calcDateExpiration);
            }

            // Init
            initExploitant();
            calcCharges();
            calcDateExpiration();
            generateTranches();
        });
    </script>
@endpush

@push('styles')
    <style>
        .form-input {
            background-image: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .10);
        }
    </style>
@endpush
