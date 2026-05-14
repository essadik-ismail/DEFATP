@extends('layouts.app')

@section('title', "Cr\u{00E9}er Contrat de Vente - DEFATP")

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">D&eacute;tail #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Cr&eacute;er contrat de vente</li>
@endsection

@section('content')
<div class="contract-vente-page min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        :title='"Cr\u{00E9}er un contrat de vente"'
        :subtitle="'Article #' . ($article->numero ?? $article->id)"
        icon="fas fa-file-contract"
        :backRoute="route('articles.show', $article)"
        backText="Retour"
    />

    <div class="max-w-6xl">
        @include('contract-ventes._form', [
            'formAction' => route('contract-ventes.store', $article),
            'formMethod' => 'POST',
            'submitLabel' => "Cr\u{00E9}er le contrat",
        ])
    </div>
</div>
@endsection
