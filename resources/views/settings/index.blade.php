@extends('layouts.app')

@section('title', 'Parametres - Gestion Forestiere')
@section('page-title', 'Parametres')

@section('breadcrumb')
<li class="breadcrumb-item active">Parametres</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-cog text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Parametres
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gerez les donnees de base du systeme forestier</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Essences</h3>
                    <p class="text-gray-600 text-sm">Types d'arbres forestiers</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['essences'] }}</div>
                <div class="text-sm text-gray-600">essences</div>
            </div>
            <a href="{{ route('settings.essences.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tree text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Forets</h3>
                    <p class="text-gray-600 text-sm">Zones forestieres</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['forets'] }}</div>
                <div class="text-sm text-gray-600">forets</div>
            </div>
            <a href="{{ route('settings.forets.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cut text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Nature de Coupes</h3>
                    <p class="text-gray-600 text-sm">Methodes d'exploitation</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['nature_de_coupes'] }}</div>
                <div class="text-sm text-gray-600">types</div>
            </div>
            <a href="{{ route('settings.nature-de-coupes.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-yellow-600 text-white rounded-lg hover:from-orange-700 hover:to-yellow-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Situations Administratives</h3>
                    <p class="text-gray-600 text-sm">Communes et provinces</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['situation_administratives'] }}</div>
                <div class="text-sm text-gray-600">situations</div>
            </div>
            <a href="{{ route('settings.situation-administratives.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Exploitants</h3>
                    <p class="text-gray-600 text-sm">Gestion des exploitants</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['exploitants'] }}</div>
                <div class="text-sm text-gray-600">exploitants</div>
            </div>
            <a href="{{ route('exploitants.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-lg hover:from-indigo-700 hover:to-blue-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">DRANEF</h3>
                    <p class="text-gray-600 text-sm">Directions regionales</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['dranefs'] }}</div>
                <div class="text-sm text-gray-600">entites</div>
            </div>
            <a href="{{ route('settings.dranefs.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-download text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Import/Export</h3>
                    <p class="text-gray-600 text-sm">Gestion des donnees</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">-</div>
                <div class="text-sm text-gray-600">fonctionnalites</div>
            </div>
            <a href="{{ route('excel.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-600 to-slate-600 text-white rounded-lg hover:from-gray-700 hover:to-slate-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gerer</span>
            </a>
        </div>
    </div>
</div>
@endsection
