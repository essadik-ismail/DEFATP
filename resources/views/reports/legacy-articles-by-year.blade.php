@extends('layouts.app')

@section('title', 'Articles Historiques par Année - Rapports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Articles par Année
                        </h1>
                        <p class="text-gray-600 text-lg mt-2">Analysez les articles historiques par année</p>
                    </div>
                </div>
                <a href="{{ route('reports.legacy-articles') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-8">
        <x-card 
            title="Filtres" 
            subtitle="Filtrez les articles par année"
            variant="colored"
            color="blue"
            icon="fas fa-filter"
            padding="compact"
        >
            <form method="GET" action="{{ route('reports.legacy-articles-by-year') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar text-blue-500 mr-2"></i>
                        Année
                    </label>
                    <select 
                        id="year" 
                        name="year" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                        <option value="">Toutes les années</option>
                        @foreach($years as $yearOption)
                            <option value="{{ $yearOption->year }}" {{ $year == $yearOption->year ? 'selected' : '' }}>
                                20{{ $yearOption->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                    >
                        <i class="fas fa-filter"></i>
                        Filtrer
                    </button>
                    
                    <a 
                        href="{{ route('reports.legacy-articles-by-year') }}" 
                        class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                    >
                        <i class="fas fa-times"></i>
                        Effacer
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Total Articles</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Revenus Totaux</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_revenue'], 0) }} DH</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cube text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Volume Total</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_volume'], 2) }} m³</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Table -->
    <x-card 
        title="Articles Historiques" 
        subtitle="Liste des articles historiques{{ $year ? ' pour l\'année 20' . $year : '' }}"
        variant="gradient"
        color="blue"
        icon="fas fa-table"
    >
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DREF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forêt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Essence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surface</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($articles as $article)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $article->dref ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->foret ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->province ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($article->date && strlen(trim($article->date)) >= 6)
                                @php
                                    $dateStr = trim($article->date);
                                    $formattedDate = 'N/A';
                                    
                                    // Try different date formats
                                    try {
                                        if (preg_match('/^\d{6}$/', $dateStr)) {
                                            // Format: YYMMDD
                                            $formattedDate = \Carbon\Carbon::createFromFormat('ymd', $dateStr)->format('d/m/Y');
                                        } elseif (preg_match('/^\d{8}$/', $dateStr)) {
                                            // Format: YYYYMMDD
                                            $formattedDate = \Carbon\Carbon::createFromFormat('Ymd', $dateStr)->format('d/m/Y');
                                        } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateStr)) {
                                            // Format: DD/MM/YYYY
                                            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr)->format('d/m/Y');
                                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                                            // Format: YYYY-MM-DD
                                            $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateStr)->format('d/m/Y');
                                        } else {
                                            // If none match, just show the raw value
                                            $formattedDate = $dateStr;
                                        }
                                    } catch (\Exception $e) {
                                        // If all parsing fails, show the raw value
                                        $formattedDate = $dateStr;
                                    }
                                @endphp
                                {{ $formattedDate }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->essence ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->surface ? number_format($article->surface, 2) . ' ha' : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                                $totalVolume = ($article->bom3 ?? 0) + ($article->bim3 ?? 0) + ($article->bfst ?? 0) + 
                                             ($article->lcst ?? 0) + ($article->ett ?? 0) + ($article->pst ?? 0);
                            @endphp
                            {{ $totalVolume > 0 ? number_format($totalVolume, 2) . ' m³' : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->ppdh ? number_format($article->ppdh, 0) . ' DH' : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Aucun article trouvé{{ $year ? ' pour l\'année 20' . $year : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="mt-6">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
