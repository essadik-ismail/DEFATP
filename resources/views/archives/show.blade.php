@extends('layouts.app')

@section('title', 'Archive - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <x-page-header 
            title="Archive #{{ $archive->numero ?? $archive->id }}"
            subtitle="Détails et documents liés"
            icon="fas fa-archive"
            :backRoute="route('archives.index')"
            backText="Retour aux archives"
        >
            <x-slot name="actions">
                <a href="{{ route('archives.edit', $archive) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                   style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-edit"></i>
                    <span>Éditer</span>
                </a>
            </x-slot>
        </x-page-header>

        <!-- Archive Information -->
        <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
            <div class="bg-green-500 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-info-circle"></i>
                    Informations de l'Archive
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Date</label>
                        <p class="text-sm text-gray-900 font-medium">{{ optional($archive->date)->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Numéro</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->numero ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Expéditeur</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->expediteur ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Numéro expéditeur</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->num_expediteur ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Date expéditeur</label>
                        <p class="text-sm text-gray-900 font-medium">{{ optional($archive->date_expediteur)->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Objet</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->object ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Département</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->departement ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Service</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->service ?? '—' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-gray-600">Placement</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $archive->placement ?? '—' }}</p>
                    </div>
                </div>

                @if($archive->suite)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-xs font-semibold text-gray-600 mb-3">Suite</label>
                        <a href="{{ asset('storage/' . $archive->suite) }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                            <i class="fas fa-file-download"></i>
                            <span>Télécharger la suite</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Documents Section -->
        <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-green-500 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-file-alt"></i>
                    Documents
                </h2>
            </div>
            <div class="p-6">
                @forelse($archive->documents as $document)
                    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-100 rounded-xl hover:bg-green-100 transition-colors {{ !$loop->last ? 'mb-3' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $document->name ?? $document->file }}</p>
                                <p class="text-xs text-gray-600">{{ $document->file }}</p>
                            </div>
                        </div>
                        @if($document->path)
                            <a href="{{ asset('storage/' . $document->path) }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors text-sm font-medium">
                                <i class="fas fa-download"></i>
                                <span>Télécharger</span>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Aucun document trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
