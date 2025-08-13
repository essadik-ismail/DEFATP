@extends('layouts.app')

@section('title', 'Articles')

@section('page-actions')
    <a href="{{ route('articles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel Article
    </a>
@endsection

@section('content')
<div class="content-area">
    <!-- Welcome Header -->
    <div class="content-header">
        <div class="header-content">
            <div class="greeting-section">
                <div class="greeting">
                    <h1>Gestion des Articles</h1>
                    <p>Consultez et gérez tous les articles de vente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Import/Export Section -->
    <x-import-export-section 
        :import-route="route('articles.import')"
        :export-route="route('articles.export')"
        import-label="Importer des Articles"
        export-label="Exporter les Articles"
    />

    <!-- Articles DataTable -->
    <x-data-table 
        :headers="['ID', 'Année', 'Numéro', 'Date Adjudication', 'Forêt', 'Essence', 'Localisation', 'Prix Retrait', 'Prix Vente', 'Type', 'Statut', 'Actions']"
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
                    @if($article->localisation)
                        <span class="text-sm text-gray-700" title="{{ $article->localisation->CODE }}">
                            {{ Str::limit($article->localisation->CODE, 20) }}
                        </span>
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
                    @if($article->is_validated)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Validé
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-clock mr-1"></i>
                            En attente
                        </span>
                    @endif
                </td>
                <td class="table-cell">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>
</div>
@endsection
