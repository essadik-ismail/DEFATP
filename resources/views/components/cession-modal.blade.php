@props([
    'dranefs' => collect(),
])

<div
    x-data="{ open: false, type: 'adjudication' }"
    @open-cession-modal.window="open = true"
    class="relative"
>
    <button
        type="button"
        @click="open = true"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
        style="background: var(--primary-gradient); box-shadow: var(--shadow-md);"
    >
        <i class="fas fa-plus"></i>
        <span>Ajouter Cession</span>
    </button>

    <!-- Backdrop -->
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-black/40"
        @click="open = false"
    ></div>

    <!-- Modal -->
    <div
        x-cloak
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
    >
        <div
            @click.stop
            class="w-full max-w-xl rounded-2xl bg-white shadow-xl border border-gray-200"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Nouvelle Cession</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Créez une nouvelle cession par adjudication ou appel d'offre.
                    </p>
                </div>
                <button
                    type="button"
                    @click="open = false"
                    class="text-gray-400 hover:text-gray-600 transition"
                >
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form action="{{ route('cessions.store') }}" method="POST" class="px-6 py-4 space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- DRANEF -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            DRANEF <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="dranef_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        >
                            <option value="">Sélectionner une DRANEF</option>
                            @foreach($dranefs as $dranef)
                                <option value="{{ $dranef->id }}">{{ $dranef->dranef }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Année / Exercice -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Année / Exercice <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="annee_exercice"
                            min="2000"
                            max="{{ now()->year + 1 }}"
                            value="{{ now()->year }}"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        />
                    </div>

                    <!-- Type -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Type de cession <span class="text-red-500">*</span>
                        </label>
                        <div class="inline-flex rounded-full border border-gray-200 bg-gray-50 p-0.5 text-xs">
                            <button
                                type="button"
                                @click="type = 'adjudication'"
                                :class="type === 'adjudication' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600'"
                                class="px-3 py-1 rounded-full transition"
                            >
                                Adjudication
                            </button>
                            <button
                                type="button"
                                @click="type = 'appel_offre'"
                                :class="type === 'appel_offre' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600'"
                                class="px-3 py-1 rounded-full transition"
                            >
                                Appel d'offre
                            </button>
                        </div>
                        <input type="hidden" name="type" :value="type">
                    </div>
                </div>

                <!-- Dynamic fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                    <!-- Adjudication fields -->
                    <div x-show="type === 'adjudication'" x-transition>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Date d'adjudication <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="date_adjudication"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                        />
                    </div>

                    <!-- Appel d'offre fields -->
                    <div class="space-y-3" x-show="type === 'appel_offre'" x-transition>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Numéro AO <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="numero_ao"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Ex: AO-2026-001"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Date d'attribution <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                name="date_attribution"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button
                        type="button"
                        @click="open = false"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
                        style="background: var(--primary-gradient);"
                    >
                        <i class="fas fa-save mr-1.5"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

