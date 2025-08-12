@extends('layouts.app')

@section('title', 'Import/Export Excel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-excel text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Import/Export Excel
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez l'import et l'export de toutes vos données en format Excel.</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Succès!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Erreur!</h3>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('results'))
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 text-blue-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-2xl mt-1"></i>
                <div>
                    <h3 class="font-semibold text-lg mb-3">Résultats de l'import :</h3>
                    <ul class="space-y-2">
                        @foreach(session('results') as $result)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-arrow-right text-sm"></i>
                                <span>{{ $result }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Export Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-download text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Export des données</h2>
                    <p class="text-gray-600">Téléchargez vos données au format Excel</p>
                </div>
            </div>
            <button class="collapse-toggle" onclick="toggleCollapse('export-section')" id="export-toggle">
                <i class="fas fa-chevron-down" id="export-icon"></i>
            </button>
        </div>
        
        <div class="collapse-content" id="export-section">
            <!-- Export All -->
            <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-archive text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-blue-900">Export complet</h3>
                        <p class="text-blue-700">Exportez toutes les données dans un fichier ZIP contenant des fichiers Excel séparés.</p>
                    </div>
                </div>
                <a href="{{ route('excel.export-all') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-download"></i>
                    <span class="font-semibold">Exporter tout</span>
                </a>
            </div>

            <!-- Individual Exports -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-blue-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Articles</h4>
                    </div>
                    <a href="{{ route('excel.export.articles') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les articles</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-green-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-seedling text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Essences</h4>
                    </div>
                    <a href="{{ route('excel.export.essences') }}" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les essences</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-mountain text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Forêts</h4>
                    </div>
                    <a href="{{ route('excel.export.forets') }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les forêts</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-orange-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-axe text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Natures de coupe</h4>
                    </div>
                    <a href="{{ route('excel.export.nature-de-coupes') }}" class="text-orange-600 hover:text-orange-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les natures de coupe</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-indigo-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Situations administratives</h4>
                    </div>
                    <a href="{{ route('excel.export.situation-administratives') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les situations administratives</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-teal-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-tie text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Exploitants</h4>
                    </div>
                    <a href="{{ route('excel.export.exploitants') }}" class="text-teal-600 hover:text-teal-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les exploitants</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                

                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-pink-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Localisations</h4>
                    </div>
                    <a href="{{ route('excel.export.localisations') }}" class="text-pink-600 hover:text-pink-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        <span>Exporter les localisations</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-upload text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Import des données</h2>
                    <p class="text-gray-600">Importez vos données depuis des fichiers Excel</p>
                </div>
            </div>
            <button class="collapse-toggle" onclick="toggleCollapse('import-section')" id="import-toggle">
                <i class="fas fa-chevron-down" id="import-icon"></i>
            </button>
        </div>
        
        <div class="collapse-content" id="import-section">
            <!-- Import All -->
            <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-green-900">Import multiple</h3>
                        <p class="text-green-700">Importez plusieurs fichiers Excel en une seule fois. Les fichiers seront automatiquement détectés selon leur nom.</p>
                    </div>
                </div>
                <form action="{{ route('excel.import-all') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="files" class="block text-sm font-semibold text-gray-700 mb-3">Sélectionner les fichiers Excel</label>
                        <div class="relative">
                            <input type="file" name="files[]" id="files" multiple accept=".xlsx,.xls,.csv" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-green-500 file:to-emerald-600 file:text-white hover:file:from-green-600 hover:file:to-emerald-700 transition-all duration-300 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-2">Formats acceptés : .xlsx, .xls, .csv (max 10MB par fichier)</p>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span class="font-semibold">Importer tous les fichiers</span>
                    </button>
                </form>
            </div>

            <!-- Individual Imports -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-green-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Articles</h4>
                    </div>
                    <form action="{{ route('excel.import.articles') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 mb-3 transition-all">
                        <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les articles</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-green-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-seedling text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Essences</h4>
                    </div>
                    <form action="{{ route('excel.import.essences') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 mb-3 transition-all">
                        <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les essences</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-mountain text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Forêts</h4>
                    </div>
                    <form action="{{ route('excel.import.forets') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 mb-3 transition-all">
                        <button type="submit" class="text-purple-600 hover:text-purple-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les forêts</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-orange-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-axe text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Natures de coupe</h4>
                    </div>
                    <form action="{{ route('excel.import.nature-de-coupes') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 mb-3 transition-all">
                        <button type="submit" class="text-orange-600 hover:text-orange-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les natures de coupe</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-indigo-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Situations administratives</h4>
                    </div>
                    <form action="{{ route('excel.import.situation-administratives') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-3 transition-all">
                        <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les situations administratives</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-teal-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-tie text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Exploitants</h4>
                    </div>
                    <form action="{{ route('excel.import.exploitants') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 mb-3 transition-all">
                        <button type="submit" class="text-teal-600 hover:text-teal-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les exploitants</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
                

                
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 hover:border-pink-300 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Localisations</h4>
                    </div>
                    <form action="{{ route('excel.import.localisations') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 mb-3 transition-all">
                        <button type="submit" class="text-pink-600 hover:text-pink-800 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Importer les localisations</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Section -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl p-8 mt-8 border border-amber-200 shadow-xl">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-lightbulb text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-amber-900">Instructions d'utilisation</h3>
                <p class="text-amber-700">Conseils pour un import/export optimal</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-download text-white text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Export</h4>
                        <p class="text-gray-600 text-sm">Cliquez sur les liens d'export pour télécharger les données au format Excel.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-upload text-white text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Import</h4>
                        <p class="text-gray-600 text-sm">Sélectionnez un fichier Excel (.xlsx, .xls, .csv) et cliquez sur importer.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-file-alt text-white text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Format des fichiers</h4>
                        <p class="text-gray-600 text-sm">Les fichiers doivent contenir des en-têtes correspondant aux noms des colonnes.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-archive text-white text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Import multiple</h4>
                        <p class="text-gray-600 text-sm">Pour l'import multiple, nommez vos fichiers avec le nom de la table (ex: articles.xlsx, essences.xlsx).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .collapse-toggle {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }

    .collapse-toggle:hover {
        background: rgba(255, 255, 255, 1);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: scale(1.05);
    }

    .collapse-toggle i {
        transition: transform 0.3s ease;
    }

    .collapse-toggle.collapsed i {
        transform: rotate(-90deg);
    }

    .collapse-content {
        max-height: 2000px;
        opacity: 1;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .collapse-content.collapsed {
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleCollapse(sectionId) {
        const content = document.getElementById(sectionId);
        const toggle = document.getElementById(sectionId.replace('-section', '-toggle'));
        const icon = document.getElementById(sectionId.replace('-section', '-icon'));
        
        if (content.classList.contains('collapsed')) {
            // Expand
            content.classList.remove('collapsed');
            toggle.classList.remove('collapsed');
            icon.style.transform = 'rotate(0deg)';
            localStorage.setItem(`collapse_${sectionId}`, 'false');
        } else {
            // Collapse
            content.classList.add('collapsed');
            toggle.classList.add('collapsed');
            icon.style.transform = 'rotate(-90deg)';
            localStorage.setItem(`collapse_${sectionId}`, 'true');
        }
    }

    // Initialize collapse state from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const sections = ['export-section', 'import-section'];
        
        sections.forEach(sectionId => {
            const isCollapsed = localStorage.getItem(`collapse_${sectionId}`) === 'true';
            if (isCollapsed) {
                const content = document.getElementById(sectionId);
                const toggle = document.getElementById(sectionId.replace('-section', '-toggle'));
                const icon = document.getElementById(sectionId.replace('-section', '-icon'));
                
                content.classList.add('collapsed');
                toggle.classList.add('collapsed');
                icon.style.transform = 'rotate(-90deg)';
            }
        });
    });
</script>
@endpush
