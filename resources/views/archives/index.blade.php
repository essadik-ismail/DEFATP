@extends('layouts.app')

@section('title', 'Archives')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Archives</h1>
            <p class="text-gray-600">Gérez les courriers et leurs documents</p>
        </div>
        <a href="{{ route('archives.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-plus"></i> Nouvelle archive
        </a>
    </div>

    <form method="GET" action="{{ route('archives.index') }}" class="mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Recherche globale</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Numéro, objet..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Numéro</label>
                <input type="text" name="numero" value="{{ request('numero') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Expéditeur</label>
                <input type="text" name="expediteur" value="{{ request('expediteur') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Département</label>
                <select name="departement" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="">Tous</option>
                    @foreach($departements as $dept)
                        <option value="{{ $dept }}" {{ request('departement') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Service</label>
                <select name="service" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="">Tous</option>
                    @foreach($services as $service)
                        <option value="{{ $service }}" {{ request('service') === $service ? 'selected' : '' }}>{{ $service }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Filtrer</button>
            @if(request()->except('page'))
                <a href="{{ route('archives.index') }}" class="px-3 py-2 text-gray-600 hover:text-gray-900">Réinitialiser</a>
            @endif
        </div>
    </form>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Numéro</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Expéditeur</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Département</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Service</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Placement</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Documents</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($archives as $archive)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $archive->numero ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $archive->expediteur ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $archive->departement ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $archive->service ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $archive->placement ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $archive->documents_count }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ optional($archive->date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 text-right space-x-2">
                            <a href="{{ route('archives.show', $archive) }}" class="text-blue-600 hover:underline">Voir</a>
                            <a href="{{ route('archives.edit', $archive) }}" class="text-indigo-600 hover:underline">Éditer</a>
                            <form action="{{ route('archives.destroy', $archive) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Supprimer cette archive ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucune archive trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $archives->withQueryString()->links() }}
    </div>
</div>
@endsection

