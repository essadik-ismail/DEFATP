@extends('layouts.app')

@section('title', 'Modifier Contrat de Vente - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">D&eacute;tail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">Modifier contrat de vente</li>
@endsection

@section('content')
<div class="contract-vente-page min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Modifier le contrat de vente"
        :subtitle="'Article #' . ($article->numero ?? $article->id)"
        icon="fas fa-file-signature"
        :backRoute="route('articles.show', $article)"
        backText="Retour"
    />

    <x-flash-messages />

    <div class="max-w-6xl">
        @include('contract-ventes._form', [
            'formAction' => route('contract-ventes.update', [$article, $contractVente]),
            'formMethod' => 'PUT',
            'submitLabel' => 'Enregistrer les modifications',
        ])
    </div>
</div>
@endsection
