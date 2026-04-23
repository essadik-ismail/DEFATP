@php
    $contract = $contractVente ?? null;
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
        } catch (\Throwable $exception) {
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
    $fraisAdjudicationCharge = $findCharge(["frais d'adjudication", 'frais adjudication']);
    $taxeProvincialeCharge = $findCharge(['taxe provinciale']);
    $taxeRefectionCharge = $findCharge([
        'refection des chemins forestiers',
        'taxe pour la refection des chemins forestiers',
        'refection',
    ]);
    $serviceRenduCharge = $findCharge(["service rendu par l'anef", 'service rendu']);
    $prixVenteValue = (float) old('prix_vente', $contract->prix_vente ?? 0);
    $boisChauffageVolumeValue = old('bois_chauffage_volume_st', $contract->bois_chauffage_volume_st ?? '');
    $selectedExploitantId = old('exploitant_id', $contract->exploitant_id ?? '');
    $selectedExploitant = collect($exploitants ?? [])->first(
        fn($exploitant) => (string) $exploitant->id === (string) $selectedExploitantId,
    );
    $initialExploitantCin = old('exploitant_cin_search', $selectedExploitant?->n_cin ?? '');

    $trancheRows = collect(
        old(
            'tranches',
            $storedTranches
                ->map(function ($tranche) use ($readChargeValue) {
                    return [
                        'montant' => $readChargeValue($tranche, 'montant'),
                        'date_echeance' => $readChargeValue($tranche, 'date_echeance'),
                    ];
                })
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
        $trancheRows = collect(range(1, $nombreTranche))->map(
            fn() => [
                'montant' => '',
                'date_echeance' => '',
            ],
        );
    }

    $existingTranches = $trancheRows
        ->values()
        ->map(function ($tranche) use ($readChargeValue) {
            return [
                'montant' => is_array($tranche) ? $tranche['montant'] ?? '' : $readChargeValue($tranche, 'montant'),
                'date_echeance' => is_array($tranche)
                    ? $tranche['date_echeance'] ?? ''
                    : $readChargeValue($tranche, 'date_echeance'),
            ];
        })
        ->all();
    $isEditing = filled($contract?->id);
    $modeCessionLabel = $article->cession?->mode_cession
        ? \Illuminate\Support\Str::headline((string) $article->cession->mode_cession)
        : 'Selon la cession liée';
@endphp

<form action="{{ $formAction }}" method="POST" id="contractVenteForm" class="space-y-6">
    @csrf
    @if (($formMethod ?? 'POST') !== 'POST')
        @method($formMethod)
    @endif

    <x-validation-errors />

    <div class="rounded-2xl border bg-white p-6 md:p-8"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50/80 p-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                        <i class="fas fa-file-contract text-sm"></i>
                    </div>
                    <div>
                        <h2 class="contract-vente-intro-title text-base font-semibold text-gray-900">
                            {{ $isEditing ? 'Mise à jour du contrat de vente' : 'Création du contrat de vente' }}
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:min-w-[360px]">
                    <div class="rounded-lg border border-white/80 bg-white px-4 py-3 shadow-sm">
                        <span
                            class="block text-xs font-semibold uppercase tracking-[0.12em] text-gray-500">Article</span>
                        <span
                            class="mt-1 block text-sm font-semibold text-gray-900">#{{ $article->numero ?? $article->id }}</span>
                    </div>
                    <div class="rounded-lg border border-white/80 bg-white px-4 py-3 shadow-sm">
                        <span class="block text-xs font-semibold uppercase tracking-[0.12em] text-gray-500">Mode</span>
                        <span class="mt-1 block text-sm font-semibold text-gray-900">{{ $modeCessionLabel }}</span>
                    </div>
                </div>
            </div>
        </div>

        <x-form-section number="1" title="Informations de l&apos;article" icon="fas fa-info-circle" color="green">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="form-group">
                    <label for="article_number" class="mb-2 block text-sm font-semibold text-gray-700">
                        Num&eacute;ro d&apos;article
                    </label>
                    <input type="text" id="article_number" value="{{ $article->numero ?? $article->id }}"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label for="duree_decheache" class="mb-2 block text-sm font-semibold text-gray-700">
                        Dur&eacute;e de contrat
                    </label>
                    <input type="number" id="duree_decheache" name="duree_decheache"
                        value="{{ old('duree_decheache', $contract->duree_decheache ?? '') }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Ex: 12 mois">
                    @error('duree_decheache')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </x-form-section>

        <x-form-section number="2" title="Informations sur l&apos;exploitant" icon="fas fa-user-tie" color="blue">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="form-group">
                    <label for="exploitant_cin_search" class="mb-2 block text-sm font-semibold text-gray-700">
                        CIN <span class="text-red-500">*</span>
                    </label>
                    <input type="hidden" id="exploitant_id" name="exploitant_id" value="{{ $selectedExploitantId }}">
                    <select id="exploitant_lookup" class="hidden" aria-hidden="true" tabindex="-1">
                        <option value="">S&eacute;lectionner un exploitant</option>
                        @foreach ($exploitants as $exploitant)
                            <option value="{{ $exploitant->id }}" data-cin="{{ $exploitant->n_cin }}"
                                data-name="{{ $exploitant->nom_complet }}"
                                data-raison-sociale="{{ $exploitant->raison_sociale }}"
                                data-numero="{{ $exploitant->numero }}" data-adresse="{{ $exploitant->adresse }}"
                                data-categorie="{{ $exploitant->categorie }}" @selected((string) $selectedExploitantId === (string) $exploitant->id)>
                                {{ $exploitant->nom_complet }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex gap-3">
                        <input type="text" id="exploitant_cin_search" name="exploitant_cin_search"
                            value="{{ $initialExploitantCin }}"
                            class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Saisir le CIN" autocomplete="off">
                        <button type="button" id="load_exploitant_btn"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Charger
                        </button>
                    </div>
                    <div id="exploitant_lookup_feedback" class="mt-2 text-sm text-gray-500" aria-live="polite">
                        Saisissez le CIN puis cliquez sur Charger pour remplir les informations.
                    </div>
                    @error('exploitant_id')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exploitant_nom_complet" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nom
                    </label>
                    <input type="text" id="exploitant_nom_complet"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label for="exploitant_numero" class="mb-2 block text-sm font-semibold text-gray-700">
                        Num&eacute;ro de patente
                    </label>
                    <input type="text" id="exploitant_numero"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label for="exploitant_adresse" class="mb-2 block text-sm font-semibold text-gray-700">
                        Adresse
                    </label>
                    <input type="text" id="exploitant_adresse"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>

                <div class="form-group">
                    <label for="exploitant_categorie" class="mb-2 block text-sm font-semibold text-gray-700">
                        Cat&eacute;gorie
                    </label>
                    <input type="text" id="exploitant_categorie"
                        class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3" readonly>
                </div>
            </div>
        </x-form-section>

        <x-form-section number="3" title="D&eacute;tails de la vente" icon="fas fa-money-bill-wave"
            color="orange">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="form-group">
                    <label for="prix_vente" class="mb-2 block text-sm font-semibold text-gray-700">
                        Prix de vente <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="prix_vente" name="prix_vente" step="0.01" min="0"
                        value="{{ old('prix_vente', $contract->prix_vente ?? '') }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="0.00" required>
                    @error('prix_vente')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nombre_tranche" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nombre de tranches <span class="text-red-500">*</span>
                    </label>
                    <select id="nombre_tranche" name="nombre_tranche"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                        <option value="1" @selected($nombreTranche === 1)>1</option>
                        <option value="2" @selected($nombreTranche === 2)>2</option>
                        <option value="4" @selected($nombreTranche === 4)>4</option>
                    </select>
                    @error('nombre_tranche')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_limite_tranche" class="mb-2 block text-sm font-semibold text-gray-700">
                        Date limite tranche
                    </label>
                    <input type="date" id="date_limite_tranche" name="date_limite_tranche"
                        value="{{ old('date_limite_tranche', $formatDateValue($contract?->date_limite_tranche)) }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('date_limite_tranche')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_limite_taxes" class="mb-2 block text-sm font-semibold text-gray-700">
                        Date limite taxes
                    </label>
                    <input type="date" id="date_limite_taxes" name="date_limite_taxes"
                        value="{{ old('date_limite_taxes', $formatDateValue($contract?->date_limite_taxes)) }}"
                        class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('date_limite_taxes')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </x-form-section>

        <x-form-section number="4" title="R&eacute;capitulatif des charges" icon="fas fa-calculator"
            color="yellow">

            <div class="space-y-4">

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Cautionnement
                                d&eacute;finitif (10%)</label>
                            <input type="number" id="charge_cautionnement" name="charges[0][montant]"
                                step="0.01" value="{{ number_format($prixVenteValue * 0.1, 2, '.', '') }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                            <input type="hidden" name="charges[0][nom]" value="Cautionnement d&eacute;finitif">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance</label>
                            <input type="date" name="charges[0][date_echeance]"
                                value="{{ old('charges.0.date_echeance', $readChargeValue($cautionCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('charges.0.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe FNF (20%)</label>
                            <input type="number" id="charge_taxe_fnf" name="charges[1][montant]" step="0.01"
                                value="{{ number_format($prixVenteValue * 0.2, 2, '.', '') }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                            <input type="hidden" name="charges[1][nom]" value="Taxe FNF">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance</label>
                            <input type="date" name="charges[1][date_echeance]"
                                value="{{ old('charges.1.date_echeance', $readChargeValue($fnfCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('charges.1.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Frais d&apos;adjudication
                                (1.6%)</label>
                            <input type="number" id="charge_frais_adjudication" name="charges[2][montant]"
                                step="0.01" value="{{ number_format($prixVenteValue * 0.016, 2, '.', '') }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                            <input type="hidden" name="charges[2][nom]" value="Frais d&apos;adjudication">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance</label>
                            <input type="date" name="charges[2][date_echeance]"
                                value="{{ old('charges.2.date_echeance', $readChargeValue($fraisAdjudicationCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('charges.2.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe provinciale
                                (10%)</label>
                            <input type="number" id="charge_taxe_provinciale" name="charges[3][montant]"
                                step="0.01" value="{{ number_format($prixVenteValue * 0.1, 2, '.', '') }}"
                                class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3"
                                readonly>
                            <input type="hidden" name="charges[3][nom]" value="Taxe provinciale">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance</label>
                            <input type="date" name="charges[3][date_echeance]"
                                value="{{ old('charges.3.date_echeance', $readChargeValue($taxeProvincialeCharge, 'date_echeance')) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('charges.3.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Taxe pour la r&eacute;fection
                                des chemins forestiers</label>
                            <input type="number" id="charge_taxe_refection" name="charges[4][montant]"
                                step="0.01"
                                value="{{ old('charges.4.montant', $readChargeValue($taxeRefectionCharge, 'montant') ?: $article->taxe_refection_chemins ?? 0) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <input type="hidden" name="charges[4][nom]"
                                value="Taxe pour la refection des chemins forestiers">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance</label>
                            <input type="date" name="charges[4][date_echeance]"
                                value="{{ old('charges.4.date_echeance', $readChargeValue($taxeRefectionCharge, 'date_echeance') ?: $formatDateValue($article->date_echeance_taxe_refection_chemins)) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('charges.4.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Service rendu par
                                l&apos;ANEF</label>
                            <input type="number" id="charge_service_anef" name="charges[5][montant]" step="0.01"
                                value="{{ old('charges.5.montant', $readChargeValue($serviceRenduCharge, 'montant') ?: $article->service_rendu_anef ?? 0) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <input type="hidden" name="charges[5][nom]" value="Service rendu par l'ANEF">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Date
                                d&apos;&eacute;ch&eacute;ance</label>
                            <input type="date" name="charges[5][date_echeance]"
                                value="{{ old('charges.5.date_echeance', $readChargeValue($serviceRenduCharge, 'date_echeance') ?: $formatDateValue($article->date_echeance_service_rendu_anef)) }}"
                                class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('charges.5.date_echeance')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-amber-200 bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label for="recap_bois_chauffage_volume_st"
                                class="mb-2 block text-sm font-semibold text-gray-700">Bois de chauffage &ndash; Volume
                                (st)</label>
                            <input type="number" id="recap_bois_chauffage_volume_st" step="0.01"
                                value="{{ $boisChauffageVolumeValue }}"
                                class="form-input w-full rounded-xl border border-amber-200 bg-white px-4 py-3">
                        </div>
                        <div class="rounded-xl border border-white/80 bg-white/80 px-4 py-3 text-sm text-gray-600">
                            Bois de chauffage &ndash; Volume <span class="font-semibold text-gray-800">st</span>.
                        </div>
                    </div>
                </div>
            </div>
        </x-form-section>

        <x-form-section number="5" title="Tranches de paiement" icon="fas fa-calendar-alt" color="purple">
            <p class="mb-4 text-sm text-gray-600">
                Choisissez 1, 2 ou 4 tranches. Chaque montant est calcul&eacute; automatiquement &agrave; partir du prix
                de vente.
            </p>

            <div id="tranches_container" class="space-y-4"></div>
        </x-form-section>

        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 pt-6">
            <a href="{{ route('articles.show', $article) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition-all duration-200 hover:border-emerald-300 hover:text-emerald-700">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Retour</span>
            </a>
            <button type="submit"
                class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:from-emerald-700 hover:to-emerald-800">
                <i class="fas fa-save"></i>
                <span>{{ $submitLabel }}</span>
            </button>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exploitantIdInput = document.getElementById('exploitant_id');
            const exploitantLookup = document.getElementById('exploitant_lookup');
            const exploitantCinSearchInput = document.getElementById('exploitant_cin_search');
            const loadExploitantButton = document.getElementById('load_exploitant_btn');
            const exploitantFeedback = document.getElementById('exploitant_lookup_feedback');
            const prixVenteInput = document.getElementById('prix_vente');
            const boisChauffageVolumeInput = document.getElementById('bois_chauffage_volume_st');
            const nombreTrancheInput = document.getElementById('nombre_tranche');
            const tranchesContainer = document.getElementById('tranches_container');
            const existingTranches = @json($existingTranches);

            function normalizeCin(value) {
                return String(value || '')
                    .replace(/[^a-z0-9]/gi, '')
                    .toUpperCase();
            }

            function setExploitantFeedback(message, tone) {
                if (!exploitantFeedback) {
                    return;
                }

                exploitantFeedback.textContent = message || '';
                exploitantFeedback.className = 'mt-2 text-sm';

                if (tone === 'success') {
                    exploitantFeedback.classList.add('text-emerald-600');
                    return;
                }

                if (tone === 'error') {
                    exploitantFeedback.classList.add('text-red-500');
                    return;
                }

                exploitantFeedback.classList.add('text-gray-500');
            }

            function getExploitantOptions() {
                if (!exploitantLookup) {
                    return [];
                }

                return Array.from(exploitantLookup.options).filter(function(option) {
                    return option.value;
                });
            }

            function getSelectedExploitantOption() {
                if (!exploitantIdInput || !exploitantIdInput.value) {
                    return null;
                }

                return getExploitantOptions().find(function(option) {
                    return option.value === exploitantIdInput.value;
                }) || null;
            }

            function populateExploitantFields(option) {
                const fieldMap = {
                    exploitant_nom_complet: 'name',
                    exploitant_numero: 'numero',
                    exploitant_adresse: 'adresse',
                    exploitant_categorie: 'categorie'
                };

                Object.keys(fieldMap).forEach(function(fieldId) {
                    const field = document.getElementById(fieldId);

                    if (!field) {
                        return;
                    }

                    field.value = option ? (option.dataset[fieldMap[fieldId]] || '') : '';
                });

                if (exploitantCinSearchInput && option && option.dataset.cin) {
                    exploitantCinSearchInput.value = option.dataset.cin;
                }
            }

            function resetExploitantFields() {
                populateExploitantFields(null);
            }

            function loadExploitantByCin() {
                const searchValue = exploitantCinSearchInput ? exploitantCinSearchInput.value : '';
                const normalizedCin = normalizeCin(searchValue);

                if (!normalizedCin) {
                    if (exploitantIdInput) {
                        exploitantIdInput.value = '';
                    }

                    resetExploitantFields();
                    setExploitantFeedback('Saisissez un CIN avant de charger les informations.', 'error');

                    return;
                }

                const matchedOption = getExploitantOptions().find(function(option) {
                    return normalizeCin(option.dataset.cin) === normalizedCin;
                });

                if (!matchedOption) {
                    if (exploitantIdInput) {
                        exploitantIdInput.value = '';
                    }

                    resetExploitantFields();
                    setExploitantFeedback('Aucun exploitant ne correspond a ce CIN.', 'error');

                    return;
                }

                if (exploitantIdInput) {
                    exploitantIdInput.value = matchedOption.value;
                }

                if (exploitantLookup) {
                    exploitantLookup.value = matchedOption.value;
                }

                populateExploitantFields(matchedOption);
                setExploitantFeedback('Les informations de l exploitant ont ete chargees.', 'success');
            }

            function invalidateExploitantSelectionOnInput() {
                const selectedOption = getSelectedExploitantOption();

                if (!selectedOption || !exploitantCinSearchInput) {
                    return;
                }

                if (normalizeCin(exploitantCinSearchInput.value) === normalizeCin(selectedOption.dataset.cin)) {
                    return;
                }

                if (exploitantIdInput) {
                    exploitantIdInput.value = '';
                }

                if (exploitantLookup) {
                    exploitantLookup.value = '';
                }

                resetExploitantFields();
                setExploitantFeedback('Cliquez sur Charger pour valider le nouveau CIN saisi.', 'neutral');
            }

            function initializeExploitantFields() {
                const selectedOption = getSelectedExploitantOption();

                if (!selectedOption) {
                    resetExploitantFields();

                    if (exploitantCinSearchInput && exploitantCinSearchInput.value) {
                        setExploitantFeedback('Cliquez sur Charger pour rechercher cet exploitant.', 'neutral');
                    }

                    return;
                }

                populateExploitantFields(selectedOption);

                if (exploitantCinSearchInput && !exploitantCinSearchInput.value && selectedOption.dataset.cin) {
                    exploitantCinSearchInput.value = selectedOption.dataset.cin;
                }

                setExploitantFeedback('Les informations de l exploitant ont ete chargees.', 'success');
            }

            function setInputValue(id, value) {
                const input = document.getElementById(id);

                if (input) {
                    input.value = value;
                }
            }

            function calculateCharges() {
                const prixVente = parseFloat(prixVenteInput ? prixVenteInput.value : 0) || 0;

                setInputValue('charge_cautionnement', (prixVente * 0.10).toFixed(2));
                setInputValue('charge_taxe_fnf', (prixVente * 0.20).toFixed(2));
                setInputValue('charge_frais_adjudication', (prixVente * 0.016).toFixed(2));
                setInputValue('charge_taxe_provinciale', (prixVente * 0.10).toFixed(2));
            }

            function syncBoisChauffageVolume() {
                setInputValue('recap_bois_chauffage_volume_st', boisChauffageVolumeInput ? boisChauffageVolumeInput
                    .value : '');
            }

            function buildTrancheRow(index, amount, dateEcheance) {
                return [
                    '<div class="tranche-row rounded-xl border border-gray-200 bg-white p-4 shadow-sm">',
                    '<div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">',
                    '<div>',
                    '<label class="mb-2 block text-sm font-semibold text-gray-700">Montant tranche ' + (index +
                        1) + '</label>',
                    '<input type="number" name="tranches[' + index + '][montant]" value="' + amount +
                    '" step="0.01" readonly class="form-input w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3">',
                    '</div>',
                    '<div>',
                    '<label class="mb-2 block text-sm font-semibold text-gray-700">Date d&apos;&eacute;ch&eacute;ance</label>',
                    '<input type="date" name="tranches[' + index + '][date_echeance]" value="' + (
                        dateEcheance || '') +
                    '" class="form-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500" required>',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
            }

            function getCurrentTrancheValues() {
                if (!tranchesContainer) {
                    return [];
                }

                return Array.from(tranchesContainer.querySelectorAll('.tranche-row')).map(function(row) {
                    const dateInput = row.querySelector('input[name$="[date_echeance]"]');

                    return {
                        date_echeance: dateInput ? dateInput.value : ''
                    };
                });
            }

            function generateTranches() {
                if (!nombreTrancheInput || !tranchesContainer) {
                    return;
                }

                const currentTranches = getCurrentTrancheValues();
                const nombreTranche = parseInt(nombreTrancheInput.value, 10) || 1;
                const prixVente = parseFloat(prixVenteInput ? prixVenteInput.value : 0) || 0;
                const montantParTranche = nombreTranche > 0 ? (prixVente / nombreTranche) : 0;
                const formattedMontant = montantParTranche.toFixed(2);
                let markup = '';

                for (let index = 0; index < nombreTranche; index += 1) {
                    const existing = currentTranches[index] || existingTranches[index] || {};

                    markup += buildTrancheRow(index, formattedMontant, existing.date_echeance || '');
                }

                tranchesContainer.innerHTML = markup;
            }

            if (loadExploitantButton) {
                loadExploitantButton.addEventListener('click', loadExploitantByCin);
            }

            if (exploitantCinSearchInput) {
                exploitantCinSearchInput.addEventListener('input', invalidateExploitantSelectionOnInput);
                exploitantCinSearchInput.addEventListener('keydown', function(event) {
                    if (event.key !== 'Enter') {
                        return;
                    }

                    event.preventDefault();
                    loadExploitantByCin();
                });
            }

            if (prixVenteInput) {
                prixVenteInput.addEventListener('input', function() {
                    calculateCharges();
                    generateTranches();
                });
            }

            if (boisChauffageVolumeInput) {
                boisChauffageVolumeInput.addEventListener('input', syncBoisChauffageVolume);
            }

            if (nombreTrancheInput) {
                nombreTrancheInput.addEventListener('change', generateTranches);
            }

            initializeExploitantFields();
            calculateCharges();
            syncBoisChauffageVolume();
            generateTranches();
        });
    </script>
@endpush

@push('styles')
    <style>
        .contract-vente-page .ph-title {
            font-size: clamp(1.5rem, 2.3vw, 2rem);
            line-height: 1.15;
        }

        .contract-vente-page .ph-subtitle {
            font-size: 0.95rem;
            line-height: 1.35;
        }

        .contract-vente-page .contract-vente-intro-title {
            font-size: clamp(1.25rem, 1.9vw, 1.75rem);
            line-height: 1.2;
        }

        .contract-vente-page .contract-vente-intro-copy {
            font-size: 1rem;
            line-height: 1.45;
        }

        .form-input {
            background-image: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.10);
        }
    </style>
@endpush
