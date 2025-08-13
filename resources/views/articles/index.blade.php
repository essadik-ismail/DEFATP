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
                    <span class="badge bg-primary">
                        {{ $article->annee }}
                    </span>
                </td>
                <td class="table-cell">
                    @if($article->numero)
                        <span class="badge bg-secondary">
                            {{ $article->numero }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($article->date_adjudication)
                        <span class="text-body">{{ $article->date_adjudication->format('d/m/Y') }}</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($article->foret)
                        <span class="text-body">{{ $article->foret->foret }}</span>
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
                        <span class="text-body" title="{{ $article->localisation->CODE }}">
                            {{ Str::limit($article->localisation->CODE, 20) }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($article->prix_de_retrait)
                        <span class="badge bg-warning text-dark">
                            {{ number_format($article->prix_de_retrait, 2) }} DH
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($article->prix_vente)
                        <span class="badge bg-success">
                            {{ number_format($article->prix_vente, 2) }} DH
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($article->type)
                        <span class="badge {{ $article->type == 'appel_doffre' ? 'bg-info' : 'bg-primary' }}">
                            {{ $article->type == 'appel_doffre' ? 'Appel d\'Offre' : 'Adjudication' }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($article->is_validated)
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>
                            Validé
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>
                            En attente
                        </span>
                    @endif
                </td>
                <td class="table-cell">
                    <div class="d-flex align-items-center gap-2">
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
