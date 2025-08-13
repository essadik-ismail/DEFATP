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
                        
                                                 <button type="submit" class="btn btn-primary w-100">
                             <i class="material-icons me-2">file_download</i>
                             {{ $exportLabel }}
                         </button>
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
                        
                                                 <button type="submit" class="btn btn-success w-100">
                             <i class="material-icons me-2">cloud_upload</i>
                             {{ $importLabel }}
                         </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .import-export-section-wrapper {
        margin-bottom: 3rem;
    }

    .import-export-header {
        margin-bottom: 1rem;
    }

    .import-export-toggle {
        display: flex;
        align-items: center;
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 0.375rem;
    }

    .import-export-toggle:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .import-export-toggle.collapsed .import-export-icon {
        transform: rotate(-90deg);
    }

    .import-export-icon {
        transition: transform 0.3s ease;
        font-size: 1.25rem;
        margin-right: 0.5rem;
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
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .export-section:hover, .import-section:hover {
        border-color: #adb5bd;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 1rem;
    }

    .export-icon {
        background-color: #cce7ff;
        color: #0d6efd;
    }

    .import-icon {
        background-color: #d1e7dd;
        color: #198754;
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
        color: #212529;
        margin: 0 0 0.25rem 0;
    }

    .section-description {
        font-size: 0.875rem;
        color: #6c757d;
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
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 0.375rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        width: 100%;
    }

    .file-label:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }

    .file-name-display {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #6c757d;
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
        }
        
        .section-icon {
            width: 2.5rem;
            height: 2.5rem;
            margin-right: 0;
            margin-bottom: 0.75rem;
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
