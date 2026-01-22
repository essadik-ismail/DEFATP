@extends('layouts.app')

@section('title', 'Lettre Adjudicataire - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Page Header Component -->
        <x-page-header 
            title="Lettre Adjudicataire"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-file-alt"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        <!-- Success/Error Messages -->
        @if(session('success'))
            <x-alert type="success" title="Succès!" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>
                {{ session('error') }}
            </x-alert>
        @endif

        @if(session('info'))
            <x-alert type="info" title="Information" dismissible>
                {{ session('info') }}
            </x-alert>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-file-alt"></i>
                    Générer la Lettre Adjudicataire
                </h2>
            </div>
            <div class="p-6">
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4">
                        <i class="fas fa-file-alt text-blue-500 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Fonctionnalité en cours de développement</h3>
                    <p class="text-sm text-blue-600">La Lettre Adjudicataire sera disponible prochainement.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                    <a href="{{ route('articles.show', $article) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour à l'Article</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
