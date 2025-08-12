@extends('layouts.app')

@section('title', 'Articles - Exploitation')

@section('page-actions')
    <div class="flex items-center gap-3">
        <x-button href="{{ route('articles.create') }}" variant="primary" icon="material-icons mr-2 text-base">
            <i class="material-icons mr-2 text-base">add</i>
            Nouvel Article
        </x-button>
    </div>
@endsection

@section('content')
    {{-- Statistics Cards --}}
    <x-stats-grid :stats="[
        ['value' => $articles->total(), 'label' => 'Total Articles', 'icon' => 'fas fa-list', 'color' => 'purple'],
        ['value' => $articles->where('invendu', false)->count(), 'label' => 'Articles Vendus', 'icon' => 'fas fa-check-circle', 'color' => 'green'],
        ['value' => $articles->where('invendu', true)->count(), 'label' => 'Articles Invendus', 'icon' => 'fas fa-clock', 'color' => 'orange'],
        ['value' => number_format($articles->sum('prix_vente'), 2) . ' DH', 'label' => 'Valeur Totale', 'icon' => 'fas fa-money-bill-wave', 'color' => 'blue'],
        ['value' => $articles->where('type', 'adjudication')->count(), 'label' => 'Adjudications', 'icon' => 'fas fa-gavel', 'color' => 'indigo'],
        ['value' => $articles->where('type', 'appel_doffre')->count(), 'label' => 'Appels d\'Offre', 'icon' => 'fas fa-file-contract', 'color' => 'teal'],
        ['value' => $articles->where('is_validated', true)->count(), 'label' => 'Articles Validés', 'icon' => 'fas fa-verified', 'color' => 'amber'],
        ['value' => $articles->where('annee', now()->year)->count(), 'label' => 'Cette Année', 'icon' => 'fas fa-calendar-alt', 'color' => 'rose']
    ]" />

    {{-- Filter Section --}}
    <x-filter-section title="Filtres Avancés" id="article-filters">
        <form method="GET" action="{{ route('articles.index') }}">
            <div class="filter-grid cols-4">
                <x-form.input name="annee" label="Année" type="number" min="2000" max="2100" placeholder="Ex: 2024" />
                <x-form.input name="numero" label="Numéro" placeholder="Ex: 001, 002..." />
                <x-form.input name="date_adjudication" label="Date d'Adjudication" type="date" />
                <x-form.select name="invendu" label="Statut" :options="[
                    ['value' => '', 'label' => 'Tous'],
                    ['value' => '0', 'label' => 'Vendus'],
                    ['value' => '1', 'label' => 'Invendus']
                ]" :selected="request('invendu')" />
                
                <x-form.input name="prix_de_retrait_min" label="Prix de Retrait Min" type="number" step="0.01" placeholder="Min" />
                <x-form.input name="prix_de_retrait_max" label="Prix de Retrait Max" type="number" step="0.01" placeholder="Max" />
                <x-form.select name="foret_id" label="Forêt" :options="collect($forets)->map(fn($f) => ['value' => $f->id, 'label' => $f->foret])->prepend(['value' => '', 'label' => 'Toutes'])" :selected="request('foret_id')" />
                <x-form.select name="essence_id" label="Essence" :options="collect($essences)->map(fn($e) => ['value' => $e->id, 'label' => $e->essence])->prepend(['value' => '', 'label' => 'Toutes'])" :selected="request('essence_id')" />
                
                <x-form.select name="nature_de_coupe_id" label="Nature de Coupe" :options="collect($natureDeCoupes)->map(fn($n) => ['value' => $n->id, 'label' => $n->nature_de_coupe])->prepend(['value' => '', 'label' => 'Toutes'])" :selected="request('nature_de_coupe_id')" />

                <x-form.select name="localisation_id" label="Localisation" :options="collect($localisations)->map(fn($l) => ['value' => $l->id, 'label' => $l->localisation])->prepend(['value' => '', 'label' => 'Toutes'])" :selected="request('localisation_id')" />
                <x-form.input name="lot" label="Lot" type="number" placeholder="Ex: 1, 2..." />
                
                <x-form.input name="parcelle" label="Parcelle" type="number" placeholder="Ex: 1, 2..." />
                <x-form.input name="superficie" label="Superficie" placeholder="Ex: 100 ha" />
                <x-form.input name="prix_vente_min" label="Prix de Vente Min" type="number" step="0.01" placeholder="Min" />
                <x-form.input name="prix_vente_max" label="Prix de Vente Max" type="number" step="0.01" placeholder="Max" />
                
                <x-form.select name="type" label="Type" :options="[
                    ['value' => '', 'label' => 'Tous'],
                    ['value' => 'appel_doffre', 'label' => 'Appel d\'Offre'],
                    ['value' => 'adjudication', 'label' => 'Adjudication']
                ]" :selected="request('type')" />
                <x-form.select name="exploitant_id" label="Exploitant" :options="collect($exploitants)->map(fn($e) => ['value' => $e->id, 'label' => $e->nom_complet ?? $e->raison_sociale])->prepend(['value' => '', 'label' => 'Tous'])" :selected="request('exploitant_id')" />
                <x-form.select name="is_validated" label="Validation" :options="[
                    ['value' => '', 'label' => 'Tous'],
                    ['value' => '1', 'label' => 'Validés'],
                    ['value' => '0', 'label' => 'Non validés']
                ]" :selected="request('is_validated')" />
                <x-form.select name="is_deleted" label="Statut" :options="[
                    ['value' => '', 'label' => 'Tous'],
                    ['value' => '0', 'label' => 'Actifs'],
                    ['value' => '1', 'label' => 'Supprimés']
                ]" :selected="request('is_deleted')" />
                
                <x-form.input name="date_from" label="Date de début" type="date" />
                <x-form.input name="date_to" label="Date de fin" type="date" />
                <x-form.select name="sort" label="Trier par" :options="[
                    ['value' => '', 'label' => 'Sélectionner'],
                    ['value' => 'annee', 'label' => 'Année'],
                    ['value' => 'numero', 'label' => 'Numéro'],
                    ['value' => 'date_adjudication', 'label' => 'Date d\'Adjudication'],
                    ['value' => 'prix_vente', 'label' => 'Prix de Vente'],
                    ['value' => 'prix_de_retrait', 'label' => 'Prix de Retrait'],
                    ['value' => 'created_at', 'label' => 'Date de création'],
                    ['value' => 'updated_at', 'label' => 'Date de modification']
                ]" :selected="request('sort')" />
                <x-form.select name="direction" label="Direction" :options="[
                    ['value' => 'desc', 'label' => 'Décroissant'],
                    ['value' => 'asc', 'label' => 'Croissant']
                ]" :selected="request('direction')" />
                <x-form.select name="per_page" label="Par page" :options="[
                    ['value' => '10', 'label' => '10'],
                    ['value' => '15', 'label' => '15'],
                    ['value' => '25', 'label' => '25'],
                    ['value' => '50', 'label' => '50'],
                    ['value' => '100', 'label' => '100']
                ]" :selected="request('per_page')" />
            </div>

            <div class="filter-actions">
                <x-button type="submit" variant="primary" icon="fas fa-filter">Appliquer</x-button>
                <x-button href="{{ route('articles.index') }}" variant="outline" icon="fas fa-undo">Réinitialiser</x-button>
            </div>
        </form>
    </x-filter-section>

    {{-- Alert Messages --}}
    @if(session('success'))
        <x-alert type="success" title="Succès!" dismissible="true" autoHide="true">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" title="Erreur!" dismissible="true" autoHide="true">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Data Table --}}
    <x-card title="Liste des Articles" subtitle="{{ $articles->total() }} article(s) trouvé(s)">
        <x-data-table 
            :headers="['ID', 'Année', 'Numéro', 'Date d\'Adjudication', 'Statut', 'Forêt', 'Essence', 'Nature de Coupe', 'Localisation', 'Lot/Parcelle', 'Superficie', 'Prix de Retrait', 'Prix de Vente', 'Type', 'Exploitant', 'Validation', 'Actions']"
            :total="$articles->total()"
            :pagination="$articles->appends(request()->query())->links()"
            emptyMessage="Aucun article trouvé"
            emptySubmessage="Essayez de modifier vos filtres ou ajoutez un nouvel article"
        >
            @foreach($articles as $article)
                                <tr class="table-row">
                                    <td class="table-cell">{{ $article->id }}</td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $article->annee }}
                                        </span>
                                    </td>
                                    <td class="table-cell">
                        @if($article->numero)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $article->numero }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                                    </td>
                                    <td class="table-cell">
                        @if($article->date_adjudication)
                            <span class="text-sm text-gray-700">{{ $article->date_adjudication->format('d/m/Y') }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                                    </td>
                                    <td class="table-cell">
                                        @if($article->invendu)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="material-icons mr-1 text-xs">schedule</i>
                                                Invendu
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="material-icons mr-1 text-xs">check</i>
                                                Vendu
                                            </span>
                                        @endif
                                    </td>
                    <td class="table-cell">
                        @if($article->foret)
                            <span class="text-sm text-gray-700">{{ $article->foret->foret }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->essence)
                            <span class="text-sm text-gray-700">{{ $article->essence->essence }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->natureDeCoupe)
                            <span class="text-sm text-gray-700">{{ $article->natureDeCoupe->nature_de_coupe }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <td class="table-cell">
                        @if($article->localisation)
                            <span class="text-sm text-gray-700" title="{{ $article->localisation->localisation }}">
                                {{ Str::limit($article->localisation->localisation, 20) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->lot || $article->parcelle)
                            <span class="text-sm text-gray-700">
                                @if($article->lot)Lot: {{ $article->lot }}@endif
                                @if($article->lot && $article->parcelle) / @endif
                                @if($article->parcelle)Parcelle: {{ $article->parcelle }}@endif
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->superficie)
                            <span class="text-sm text-gray-700">{{ $article->superficie }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->prix_de_retrait)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ number_format($article->prix_de_retrait, 2) }} DH
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->prix_vente)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ number_format($article->prix_vente, 2) }} DH
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->type)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $article->type == 'appel_doffre' ? 'bg-blue-100 text-blue-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ $article->type == 'appel_doffre' ? 'Appel d\'Offre' : 'Adjudication' }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->exploitant)
                            <span class="text-sm text-gray-700" title="{{ $article->exploitant->nom_complet ?? $article->exploitant->raison_sociale }}">
                                {{ Str::limit($article->exploitant->nom_complet ?? $article->exploitant->raison_sociale, 20) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($article->is_validated)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="material-icons mr-1 text-xs">verified</i>
                                Validé
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="material-icons mr-1 text-xs">pending</i>
                                En attente
                            </span>
                        @endif
                    </td>
                                    <td class="table-cell">
                                        <div class="flex items-center gap-2">
                            <x-button href="{{ route('articles.edit', $article) }}" variant="primary" size="sm" icon="fas fa-edit">Modifier</x-button>
                                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                @csrf
                                                @method('DELETE')
                                <x-button type="submit" variant="danger" size="sm" icon="fas fa-trash">Supprimer</x-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
        </x-data-table>
    </x-card>

    {{-- Import/Export Section --}}
    <x-import-export-section 
        exportRoute="{{ route('excel.export.articles') }}"
        importRoute="{{ route('excel.import.articles') }}"
        exportLabel="Export Excel"
        importLabel="Import Excel"
        exportDescription="Télécharger les articles au format Excel"
        importDescription="Importer des articles depuis un fichier Excel"
        id="articles-import-export"
    />
@endsection
