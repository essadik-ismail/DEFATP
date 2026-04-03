{{--
    Import / Export Card
    =====================
    Renders the standard import + export action section used on every settings
    list page (essences, forêts, exploitants, nature-de-coupes, etc.).

    Props:
        exportRoute  (required) – named route for the Excel export
        importRoute  (required) – named route for the Excel import page / action
        resourceName (string)   – human-readable plural name, e.g. "essences"
        icon         (string)   – Font Awesome icon class for the resource
--}}

@props([
    'exportRoute',
    'importRoute',
    'resourceName' => 'données',
    'icon'         => 'fas fa-file-excel',
])

<div
    class="mb-5 rounded-2xl border bg-white overflow-hidden"
    style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
>
    {{-- Section header --}}
    <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/60 px-5 py-3.5">
        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-100">
            <i class="{{ $icon }} text-sm text-emerald-700"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Import / Export</p>
            <p class="text-xs text-gray-500">Gérez les {{ $resourceName }} via Excel</p>
        </div>
    </div>

    {{-- Action tiles --}}
    <div class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">

        {{-- Export --}}
        <div class="flex items-center justify-between gap-4 px-5 py-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-50">
                    <i class="fas fa-download text-sm text-emerald-600"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800">Exporter</p>
                    <p class="text-xs text-gray-500 truncate">Télécharger en Excel (.xlsx)</p>
                </div>
            </div>
            <a
                href="{{ route($exportRoute) }}"
                class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50
                       px-3.5 py-2 text-xs font-semibold text-emerald-700 transition
                       hover:bg-emerald-100 hover:border-emerald-300 focus:outline-none focus:ring-2
                       focus:ring-emerald-300 focus:ring-offset-1"
            >
                <i class="fas fa-download"></i>
                Exporter
            </a>
        </div>

        {{-- Import --}}
        <div class="flex items-center justify-between gap-4 px-5 py-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50">
                    <i class="fas fa-upload text-sm text-blue-600"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800">Importer</p>
                    <p class="text-xs text-gray-500 truncate">Depuis un fichier Excel (.xlsx)</p>
                </div>
            </div>
            <a
                href="{{ route($importRoute) }}"
                class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50
                       px-3.5 py-2 text-xs font-semibold text-blue-700 transition
                       hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:ring-2
                       focus:ring-blue-300 focus:ring-offset-1"
            >
                <i class="fas fa-upload"></i>
                Importer
            </a>
        </div>

    </div>
</div>
