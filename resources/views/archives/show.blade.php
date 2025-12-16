@extends('layouts.app')

@section('title', 'Archive')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Archive #{{ $archive->id }}</h1>
            <p class="text-gray-600">Détails et documents liés.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('archives.edit', $archive) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Éditer</a>
            <a href="{{ route('archives.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Retour</a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Date</p>
                <p class="text-gray-900 font-medium">{{ optional($archive->date)->format('d/m/Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Numéro</p>
                <p class="text-gray-900 font-medium">{{ $archive->numero ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Expéditeur</p>
                <p class="text-gray-900 font-medium">{{ $archive->expediteur ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Numéro expéditeur</p>
                <p class="text-gray-900 font-medium">{{ $archive->num_expediteur ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Date expéditeur</p>
                <p class="text-gray-900 font-medium">{{ optional($archive->date_expediteur)->format('d/m/Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Objet</p>
                <p class="text-gray-900 font-medium">{{ $archive->object ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Département</p>
                <p class="text-gray-900 font-medium">{{ $archive->departement ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Service</p>
                <p class="text-gray-900 font-medium">{{ $archive->service ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Placement</p>
                <p class="text-gray-900 font-medium">{{ $archive->placement ?? '—' }}</p>
            </div>
        </div>

        <div>
            <p class="text-gray-500">Suite</p>
            @if($archive->suite)
                <a href="{{ asset('storage/' . $archive->suite) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:underline">
                    <i class="fas fa-file"></i>
                    <span>Télécharger la suite</span>
                </a>
            @else
                <p class="text-gray-900">—</p>
            @endif
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Documents</h2>
        <div class="divide-y divide-gray-200">
            @forelse($archive->documents as $document)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $document->name ?? $document->file }}</p>
                        <p class="text-xs text-gray-600">{{ $document->file }}</p>
                    </div>
                    @if($document->path)
                        <a href="{{ asset('storage/' . $document->path) }}" target="_blank" class="text-blue-600 hover:underline">Télécharger</a>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-600">Aucun document.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

