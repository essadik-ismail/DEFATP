@props([
    'title' => 'Import/Export',
    'collapsible' => true,
    'collapsed' => true,
    'id' => 'import-export-section',
    'exportRoute' => null,
    'importRoute' => null,
    'exportLabel' => 'Exporter',
    'importLabel' => 'Importer',
    'exportDescription' => 'Télécharger les données au format Excel',
    'importDescription' => 'Importer des données depuis un fichier Excel',
    'fileTypes' => '.xlsx,.xls,.csv',
    'exportFilters' => true
])

<div class="import-export-section-wrapper">
    @if($collapsible)
        <div class="import-export-header">
            <button type="button" 
                    class="import-export-toggle {{ $collapsed ? 'collapsed' : '' }}"
                    onclick="toggleImportExportSection('{{ $id }}')"
                    title="{{ $collapsed ? 'Afficher l\'import/export' : 'Masquer l\'import/export' }}"
            >
                <i class="material-icons import-export-icon" id="{{ $id }}-icon">
                    {{ $collapsed ? 'expand_more' : 'expand_less' }}
                </i>
                <span class="import-export-title">{{ $title }}</span>
            </button>
        </div>
    @endif

    <div class="import-export-content {{ $collapsible ? 'import-export-collapsible' : '' }} {{ $collapsed ? 'collapsed' : '' }}" 
         id="{{ $id }}-content">
        <div class="import-export-grid">
            {{-- Export Section --}}
            @if($exportRoute)
                <div class="export-section">
                    <div class="section-header">
                        <div class="section-icon export-icon">
                            <i class="material-icons">file_download</i>
                        </div>
                        <div class="section-info">
                            <h3 class="section-title">{{ $exportLabel }}</h3>
                            <p class="section-description">{{ $exportDescription }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ $exportRoute }}" method="GET" class="export-form">
                        @if($exportFilters)
                            @foreach(request()->except(['page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        @endif
                        
                        <x-button type="submit" variant="primary" fullWidth>
                            <i class="material-icons mr-2">file_download</i>
                            {{ $exportLabel }}
                        </x-button>
                    </form>
                </div>
            @endif

            {{-- Import Section --}}
            @if($importRoute)
                <div class="import-section">
                    <div class="section-header">
                        <div class="section-icon import-icon">
                            <i class="material-icons">cloud_upload</i>
                        </div>
                        <div class="section-info">
                            <h3 class="section-title">{{ $importLabel }}</h3>
                            <p class="section-description">{{ $importDescription }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ $importRoute }}" method="POST" enctype="multipart/form-data" class="import-form">
                        @csrf
                        <div class="file-upload-wrapper">
                            <div class="file-upload">
                                <input type="file" 
                                       name="file" 
                                       accept="{{ $fileTypes }}" 
                                       class="file-input" 
                                       onchange="updateFileName(this, '{{ $id }}-file-name')" 
                                       required
                                >
                                <label for="file" class="file-label">
                                    <i class="material-icons mr-2">attach_file</i>
                                    Choisir un fichier
                                </label>
                            </div>
                            <div id="{{ $id }}-file-name" class="file-name-display hidden"></div>
                        </div>
                        
                        <x-button type="submit" variant="success" fullWidth>
                            <i class="material-icons mr-2">cloud_upload</i>
                            {{ $importLabel }}
                        </x-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .import-export-section-wrapper {
        margin-bottom: 1.5rem;
    }

    .import-export-header {
        margin-bottom: 1rem;
    }

    .import-export-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
    }

    .import-export-toggle:hover {
        background-color: #f3f4f6;
        color: #374151;
    }

    .import-export-toggle.collapsed .import-export-icon {
        transform: rotate(-90deg);
    }

    .import-export-icon {
        transition: transform 0.3s ease;
        font-size: 1.25rem;
    }

    .import-export-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .import-export-content {
        transition: all 0.3s ease;
    }

    .import-export-content.import-export-collapsible {
        overflow: hidden;
    }

    .import-export-content.import-export-collapsible.collapsed {
        max-height: 0;
        opacity: 0;
        margin: 0;
        padding: 0;
    }

    .import-export-content:not(.collapsed) {
        max-height: 2000px;
        opacity: 1;
    }

    .import-export-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .export-section, .import-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .export-section:hover, .import-section:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .export-icon {
        background-color: #dbeafe;
        color: #2563eb;
    }

    .import-icon {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .section-icon i {
        font-size: 1.5rem;
    }

    .section-info {
        flex: 1;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.25rem 0;
    }

    .section-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    .file-upload-wrapper {
        margin-bottom: 1rem;
    }

    .file-upload {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        position: absolute;
        left: -9999px;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .file-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        width: 100%;
    }

    .file-label:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
        color: #374151;
    }

    .file-name-display {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        word-break: break-all;
    }

    .file-name-display.hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .import-export-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .export-section, .import-section {
            padding: 1rem;
        }
        
        .section-header {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }
        
        .section-icon {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .section-icon i {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleImportExportSection(sectionId) {
        const content = document.getElementById(sectionId + '-content');
        const toggle = document.querySelector(`[onclick="toggleImportExportSection('${sectionId}')"]`);
        const icon = document.getElementById(sectionId + '-icon');
        
        if (content.classList.contains('collapsed')) {
            // Expand
            content.classList.remove('collapsed');
            toggle.classList.remove('collapsed');
            icon.textContent = 'expand_less';
            localStorage.setItem(`import_export_${sectionId}`, 'expanded');
        } else {
            // Collapse
            content.classList.add('collapsed');
            toggle.classList.add('collapsed');
            icon.textContent = 'expand_more';
            localStorage.setItem(`import_export_${sectionId}`, 'collapsed');
        }
    }

    function updateFileName(input, displayId) {
        const fileName = input.files[0]?.name;
        const displayElement = document.getElementById(displayId);
        if (displayElement && fileName) {
            displayElement.textContent = fileName;
            displayElement.classList.remove('hidden');
        }
    }

    // Initialize import/export section state from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const importExportSections = document.querySelectorAll('.import-export-section-wrapper');
        importExportSections.forEach(section => {
            const content = section.querySelector('.import-export-content');
            const toggle = section.querySelector('.import-export-toggle');
            const icon = section.querySelector('.import-export-icon');
            const sectionId = content.id.replace('-content', '');
            
            if (content && toggle && icon) {
                const isCollapsed = localStorage.getItem(`import_export_${sectionId}`) === 'collapsed';
                if (isCollapsed) {
                    content.classList.add('collapsed');
                    toggle.classList.add('collapsed');
                    icon.textContent = 'expand_more';
                }
            }
        });
    });
</script>
@endpush
