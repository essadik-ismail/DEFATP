@extends('layouts.app')

@section('title', 'PV d\'Installation - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">PV d'installation</li>
@endsection

@section('content')
<div>
        
        <!-- Page Header Component -->
        <x-page-header 
            title="PV d'Installation"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-clipboard-check"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @if(isset($pvInstallation) && $pvInstallation)
            <!-- Display PV d'Installation Information -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-clipboard-check"></i>
                        PV d'Installation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Header with Status -->
                        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-green-800">PV d'Installation créé</p>
                                        <p class="text-xs text-green-600">Le {{ $pvInstallation->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                                <x-status-badge type="success" icon="fas fa-check-circle">Créé</x-status-badge>
                            </div>
                        </div>

                        <!-- Section 1: Informations principales -->
                        <x-form-section title="Informations principales" icon="fas fa-info-circle" color="green" columns="2">
                            <div>
                                <label class="block text-sm font-medium text-green-700 mb-1">PVN</label>
                                <p class="text-green-900 font-semibold">{{ $pvInstallation->pvn ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-green-700 mb-1">Date</label>
                                <p class="text-green-900 font-semibold">
                                    @if($pvInstallation->date)
                                        {{ $pvInstallation->date->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-green-700 mb-1">Exploitant</label>
                                <p class="text-green-900 font-semibold">{{ $pvInstallation->exploitant ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-green-700 mb-1">MO</label>
                                <p class="text-green-900 font-semibold">{{ $pvInstallation->emo ?? 'N/A' }}</p>
                            </div>
                        </x-form-section>

                        <!-- Section 2: Participants -->
                        <x-form-section title="Participants" icon="fas fa-users" color="blue" columns="1">
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Participants</label>
                                <p class="text-blue-900 font-semibold whitespace-pre-line">{{ $pvInstallation->participants ?? 'N/A' }}</p>
                            </div>
                        </x-form-section>

                        <!-- Section 3: Détails techniques -->
                        <x-form-section title="Détails techniques" icon="fas fa-cogs" color="purple" columns="2">
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Charbonnière</label>
                                <p class="text-purple-900 font-semibold">{{ $pvInstallation->charbonniére ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Mise en charge</label>
                                <p class="text-purple-900 font-semibold">{{ $pvInstallation->mise_en_charge ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Ravalement souches</label>
                                <p class="text-purple-900 font-semibold">{{ $pvInstallation->ravalement_souches ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Remarient</label>
                                <p class="text-purple-900 font-semibold">{{ $pvInstallation->remarient ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Mise en défens</label>
                                <p class="text-purple-900 font-semibold">{{ $pvInstallation->mise_en_defens ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Invitation caporal</label>
                                <p class="text-purple-900 font-semibold">{{ $pvInstallation->invitation_caporal ?? 'N/A' }}</p>
                            </div>
                        </x-form-section>

                        <!-- Section 4: Réserve -->
                        @if($pvInstallation->reserve)
                        <x-form-section title="Réserve" icon="fas fa-exclamation-triangle" color="yellow" columns="1">
                            <div>
                                <label class="block text-sm font-medium text-yellow-700 mb-1">Réserve</label>
                                <p class="text-yellow-900 font-semibold whitespace-pre-line">{{ $pvInstallation->reserve }}</p>
                            </div>
                        </x-form-section>
                        @endif
                    </div>

                    <!-- Section PV Signé -->
                    <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-file-signature text-green-600"></i> PV Signé
                        </h3>
                        @if($pvInstallation->fichier_pv_signe)
                            <div class="flex items-center gap-3 mb-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                                <i class="fas fa-check-circle text-emerald-600"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-emerald-800">PV signé importé</p>
                                    @if($pvInstallation->pv_signed_at)
                                        <p class="text-xs text-emerald-600">Le {{ $pvInstallation->pv_signed_at->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                                <a href="{{ asset('storage/' . $pvInstallation->fichier_pv_signe) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-white border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </div>
                        @endif
                        <form action="{{ route('articles.store-pv-installation', $article) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- carry all existing values so update doesn't reset them --}}
                            <input type="hidden" name="pvn" value="{{ $pvInstallation->pvn }}">
                            <input type="hidden" name="date" value="{{ $pvInstallation->date?->format('Y-m-d') }}">
                            <input type="hidden" name="participants" value="{{ $pvInstallation->participants }}">
                            <input type="hidden" name="exploitant" value="{{ $pvInstallation->exploitant }}">
                            <input type="hidden" name="reserve" value="{{ $pvInstallation->reserve }}">
                            <input type="hidden" name="emo" value="{{ $pvInstallation->emo }}">
                            <div class="flex items-end gap-3 flex-wrap">
                                <div class="flex-1 min-w-48">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        {{ $pvInstallation->fichier_pv_signe ? 'Remplacer le PV signé' : 'Importer le PV signé' }}
                                    </label>
                                    <input type="file" name="fichier_pv_signe" accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-green-50 file:px-2 file:py-1.5 file:font-medium file:text-green-700 hover:file:bg-green-100"
                                           required>
                                    <p class="text-xs text-gray-400 mt-0.5">PDF / JPG / PNG</p>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg font-medium transition-colors"
                                        style="background:#059669;">
                                    <i class="fas fa-upload"></i> Importer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                        <a href="{{ route('articles.show', $article) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour à l'Article</span>
                        </a>
                        <a href="{{ route('articles.pv-installation.print', $article) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-8 py-4 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                           style="background: linear-gradient(135deg, #059669, #047857);">
                            <i class="fas fa-print"></i>
                            <span>Imprimer</span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Create Form -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-clipboard-check"></i>
                        Générer le PV d'Installation
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('articles.store-pv-installation', $article) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <x-validation-errors />

                        <div class="space-y-6 mb-6">
                            <!-- Section 1: Informations principales -->
                            <x-form-section title="Informations principales" icon="fas fa-info-circle" color="green" columns="2">
                                <x-form-input
                                    type="text"
                                    name="pvn"
                                    label="PVN"
                                    :value="old('pvn')"
                                    placeholder="Numéro PVN"
                                    focusColor="green"
                                />

                                <x-form-input
                                    type="date"
                                    name="date"
                                    label="Date"
                                    :required="true"
                                    :value="old('date', date('Y-m-d'))"
                                    focusColor="green"
                                />

                                <x-form-input
                                    type="text"
                                    name="exploitant"
                                    label="Exploitant"
                                    :value="old('exploitant', $contractVente->exploitant->nom_complet ?? '')"
                                    placeholder="Nom de l'exploitant"
                                    focusColor="green"
                                />

                                <x-form-input
                                    type="text"
                                    name="emo"
                                    label="MO"
                                    :value="old('emo')"
                                    placeholder="MO"
                                    focusColor="green"
                                />
                            </x-form-section>

                            <!-- Section 2: Participants -->
                            <x-form-section title="Participants" icon="fas fa-users" color="blue" columns="1">
                                <x-form-input
                                    type="textarea"
                                    name="participants"
                                    label="Participants"
                                    :value="old('participants')"
                                    placeholder="Liste des participants..."
                                    rows="4"
                                    focusColor="blue"
                                />
                            </x-form-section>

                            <!-- Section 3: Détails techniques -->
                            <x-form-section title="Détails techniques" icon="fas fa-cogs" color="purple" columns="2">
                                <x-form-input
                                    type="text"
                                    name="charbonniére"
                                    label="Charbonnière"
                                    :value="old('charbonniére')"
                                    placeholder="Charbonnière"
                                    focusColor="purple"
                                />

                                <x-form-input
                                    type="text"
                                    name="mise_en_charge"
                                    label="Mise en charge"
                                    :value="old('mise_en_charge')"
                                    placeholder="Mise en charge"
                                    focusColor="purple"
                                />

                                <x-form-input
                                    type="text"
                                    name="ravalement_souches"
                                    label="Ravalement souches"
                                    :value="old('ravalement_souches')"
                                    placeholder="Ravalement souches"
                                    focusColor="purple"
                                />

                                <x-form-input
                                    type="text"
                                    name="remarient"
                                    label="Remarient"
                                    :value="old('remarient')"
                                    placeholder="Remarient"
                                    focusColor="purple"
                                />

                                <x-form-input
                                    type="text"
                                    name="mise_en_defens"
                                    label="Mise en défens"
                                    :value="old('mise_en_defens')"
                                    placeholder="Mise en défens"
                                    focusColor="purple"
                                />

                                <x-form-input
                                    type="text"
                                    name="invitation_caporal"
                                    label="Invitation caporal"
                                    :value="old('invitation_caporal')"
                                    placeholder="Invitation caporal"
                                    focusColor="purple"
                                />
                            </x-form-section>

                            <!-- Section 4: Réserve -->
                            <x-form-section title="Réserve" icon="fas fa-exclamation-triangle" color="yellow" columns="1">
                                <x-form-input
                                    type="textarea"
                                    name="reserve"
                                    label="Réserve (optionnel)"
                                    :value="old('reserve')"
                                    placeholder="Observations ou réserves..."
                                    rows="3"
                                    focusColor="yellow"
                                />
                            </x-form-section>

                            <!-- Section 5: PV Signé -->
                            <x-form-section title="PV Signé" icon="fas fa-file-signature" color="green" columns="1">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Importer le PV signé <span class="text-red-500">*</span></label>
                                    <input type="file" name="fichier_pv_signe" accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-sm text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-medium file:text-green-700 hover:file:bg-green-100"
                                           required>
                                    <p class="text-xs text-gray-400 mt-0.5">PDF / JPG / PNG, max 10 Mo — requis pour la validation</p>
                                </div>
                            </x-form-section>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                            <a href="{{ route('articles.show', $article) }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
                                <i class="fas fa-times"></i>
                                <span>Annuler</span>
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-8 py-4 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                                    style="background: linear-gradient(135deg, #059669, #047857);">
                                <i class="fas fa-save"></i>
                                <span>Enregistrer le PV</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
