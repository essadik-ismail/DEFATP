@extends('layouts.app')

@section('title', 'Archives - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item active">Archives</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <x-page-header 
            title="Archives"
            subtitle="Gérez les courriers et leurs documents"
            icon="fas fa-archive"
        >
            <x-slot name="actions">
                <a href="{{ route('archives.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                   style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle archive</span>
                </a>
            </x-slot>
        </x-page-header>

        <!-- Filters Section -->
        <x-filters-card :action="route('archives.index')" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
            <x-form-input
                type="text"
                name="search"
                label="Recherche globale"
                placeholder="Numéro, objet..."
                :value="request('search')"
            />
            
            <x-form-input
                type="text"
                name="numero"
                label="Numéro"
                :value="request('numero')"
            />
            
            <x-form-input
                type="text"
                name="expediteur"
                label="Expéditeur"
                :value="request('expediteur')"
            />
            
            <x-form-input
                type="select"
                name="departement"
                label="Département"
            >
                <option value="">Tous</option>
                @foreach($departements as $dept)
                    <option value="{{ $dept }}" {{ request('departement') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </x-form-input>
            
            <x-form-input
                type="select"
                name="service"
                label="Service"
            >
                <option value="">Tous</option>
                @foreach($services as $service)
                    <option value="{{ $service }}" {{ request('service') === $service ? 'selected' : '' }}>{{ $service }}</option>
                @endforeach
            </x-form-input>
            
            <x-form-input
                type="date"
                name="date"
                label="Date"
                :value="request('date')"
            />
            
            <div class="flex items-end gap-3 lg:col-span-5">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-xl transition-all duration-300" style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-filter"></i>
                    <span>Filtrer</span>
                </button>
                @if(request()->except('page'))
                    <a href="{{ route('archives.index') }}" class="inline-flex items-center gap-2 px-4 py-3 text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                @endif
            </div>
        </x-filters-card>

        <!-- Archives Table -->
        <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50 border-b border-green-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Numéro</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Expéditeur</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Département</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Service</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Placement</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Documents</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($archives as $archive)
                            <tr class="hover:bg-green-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $archive->numero ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $archive->expediteur ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $archive->departement ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $archive->service ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $archive->placement ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-medium">
                                        <i class="fas fa-file"></i>
                                        {{ $archive->documents_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ optional($archive->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('archives.show', $archive) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs font-medium">
                                            <i class="fas fa-eye"></i>
                                            <span>Voir</span>
                                        </a>
                                        <a href="{{ route('archives.edit', $archive) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium">
                                            <i class="fas fa-edit"></i>
                                            <span>Éditer</span>
                                        </a>
                                        <form action="{{ route('archives.destroy', $archive) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium" 
                                                    onclick="return confirm('Supprimer cette archive ?')">
                                                <i class="fas fa-trash"></i>
                                                <span>Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-inbox text-gray-300 text-5xl"></i>
                                        <p class="text-gray-500 font-medium">Aucune archive trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $archives->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
