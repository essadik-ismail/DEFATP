@extends('layouts.app')

@section('title', 'Excel - Gestion Forestiere')

@section('breadcrumb')
<li class="bc-item active">Excel</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8">
    <x-page-header
        title="Imports et exports Excel"
        subtitle="Telechargez les donnees existantes ou importez plusieurs fichiers d'un coup"
        icon="fas fa-file-excel"
    />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Export complet</h2>
            <p class="text-sm text-gray-600 mb-4">Genere un fichier ZIP contenant tous les exports disponibles.</p>
            <a href="{{ route('excel.export-all') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                <i class="fas fa-download"></i>
                <span>Telecharger le ZIP</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Import multiple</h2>
            <p class="text-sm text-gray-600 mb-4">Importe plusieurs fichiers Excel ou CSV en une seule action.</p>
            <form method="POST" action="{{ route('excel.import-all') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="file" name="files[]" multiple required
                       class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg p-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    <i class="fas fa-upload"></i>
                    <span>Importer les fichiers</span>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($dataTypes as $key => $dataType)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $dataType['name'] }}</h2>
                    <p class="text-sm text-gray-600 mt-1">Exportez ou importez ce jeu de donnees individuellement.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('excel.export.' . $key) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-download"></i>
                        <span>Exporter</span>
                    </a>
                </div>

                <form method="POST" action="{{ route('excel.import.' . $key) }}" enctype="multipart/form-data" class="space-y-3 mt-auto">
                    @csrf
                    <input type="file" name="file" required
                           class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg p-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors">
                        <i class="fas fa-upload"></i>
                        <span>Importer</span>
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    @if(session('results'))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Resultats du dernier import</h2>
            <ul class="space-y-2 text-sm text-gray-700">
                @foreach(session('results') as $result)
                    <li>{{ $result }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
