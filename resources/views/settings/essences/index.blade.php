@extends('layouts.app')

@section('title', 'Essences')

@section('page-actions')
    <x-button href="{{ route('settings.index') }}" variant="secondary" icon="material-icons mr-2 text-base">
        Retour aux Paramètres
    </x-button>
@endsection

@section('content')
    {{-- Enhanced Create Form Section --}}
    <x-card title="Ajouter une nouvelle essence" subtitle="Créez une nouvelle essence forestière" collapsible="false">
        <form action="{{ route('settings.essences.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form.input name="essence" label="Nom de l'essence" placeholder="Ex: Chêne, Pin, Eucalyptus..." required="true" />
                <div class="flex items-end">
                    <x-button type="submit" variant="primary" icon="material-icons mr-2 text-base">
                        Ajouter l'essence
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
                    
    {{-- Enhanced Advanced Filter Section --}}
    <x-filter-section title="Filtres Avancés" collapsible="true" collapsed="true" id="essence-filters">
        <form method="GET" action="{{ route('settings.essences') }}" class="space-y-4">
            <div class="filter-grid cols-3">
                <x-form.input name="search" label="Rechercher" placeholder="Rechercher dans les essences..." icon="material-icons" />
                <x-form.select name="sort" label="Trier par" :options="[
                    ['value' => '', 'label' => 'Sélectionner'],
                    ['value' => 'essence', 'label' => 'Nom'],
                    ['value' => 'created_at', 'label' => 'Date de création'],
                    ['value' => 'updated_at', 'label' => 'Date de modification']
                ]" :selected="request('sort')" />
                <x-form.select name="direction" label="Direction" :options="[
                    ['value' => 'asc', 'label' => 'Croissant'],
                    ['value' => 'desc', 'label' => 'Décroissant']
                ]" :selected="request('direction', 'asc')" />
            </div>
            <div class="filter-actions">
                <x-button type="submit" variant="primary" icon="fas fa-filter">Appliquer</x-button>
                <x-button href="{{ route('settings.essences') }}" variant="outline" icon="fas fa-undo">Réinitialiser</x-button>
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
    <x-card title="Liste des Essences" subtitle="{{ $essences->total() }} essence(s) trouvée(s)" collapsible="false">
        <x-data-table 
            :headers="['ID', 'Nom de l\'Essence', 'Date de Création', 'Date de Modification', 'Actions']"
            :total="$essences->total()"
            :pagination="$essences->appends(request()->query())->links()"
            emptyMessage="Aucune essence trouvée"
            emptySubmessage="Commencez par ajouter votre première essence"
        >
            @foreach($essences as $essence)
                <tr class="table-row">
                    <td class="table-cell">{{ $essence->id }}</td>
                    <td class="table-cell">
                        <span class="text-sm font-medium text-gray-900">{{ $essence->essence }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="text-sm text-gray-500">{{ $essence->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="text-sm text-gray-500">{{ $essence->updated_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="table-cell">
                        <div class="flex items-center gap-2">
                            <x-button href="{{ route('settings.essences.edit', $essence->id) }}" variant="primary" size="sm" icon="fas fa-edit">
                                Modifier
                            </x-button>
                            <form action="{{ route('settings.essences.destroy', $essence->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette essence ?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger" size="sm" icon="fas fa-trash">
                                    Supprimer
                                </x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>
@endsection 