@props([
    'headers' => [],
    'rows' => [],
    'total' => 0,
    'emptyMessage' => 'Aucune donnée trouvée',
    'emptySubmessage' => 'Essayez de modifier vos filtres ou ajoutez de nouvelles données',
    'emptyIcon' => 'fas fa-inbox',
    'pagination' => null,
    'responsive' => true,
    'striped' => true,
    'hover' => true
])

<div class="data-table-wrapper">
    @if($total > 0)
        <div class="table-info mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-sm text-muted mb-0">{{ $total }} élément(s) trouvé(s)</p>
                <div class="table-scroll-hint d-none d-md-block">
                    <small class="text-muted">
                        <i class="fas fa-arrows-alt-h me-1"></i>
                        Faites défiler horizontalement pour voir toutes les colonnes
                    </small>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover data-table {{ $striped ? 'striped' : '' }} {{ $hover ? 'hover' : '' }}">
                <thead class="table-header">
                    <tr>
                        @foreach($headers as $header)
                            <th class="table-header-cell">
                                @if(is_array($header))
                                    {{ $header['label'] ?? $header['key'] }}
                                @else
                                    {{ $header }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="table-body">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
        
        @if($pagination)
            <div class="pagination-wrapper mt-6">
                {{ $pagination }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="{{ $emptyIcon }}"></i>
            </div>
            <p class="empty-title">{{ $emptyMessage }}</p>
            <p class="empty-subtitle">{{ $emptySubmessage }}</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .data-table-wrapper {
        width: 100%;
    }

    .table-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .data-table {
        width: 100%;
        min-width: 800px; /* Ensure minimum width for readability */
        border-collapse: collapse;
        font-size: 0.875rem;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        table-layout: fixed; /* Better column width distribution */
    }

    .table-header {
        background-color: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-header-cell {
        padding: 0.5rem 0.75rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        white-space: nowrap;
        border-bottom: 1px solid #e5e7eb;
        overflow: hidden;
        text-overflow: ellipsis;
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    /* Column width management for wide tables */
    .data-table th:nth-child(1), .data-table td:nth-child(1) { width: 60px; } /* ID */
    .data-table th:nth-child(2), .data-table td:nth-child(2) { width: 80px; } /* Année */
    .data-table th:nth-child(3), .data-table td:nth-child(3) { width: 100px; } /* Numéro */
    .data-table th:nth-child(4), .data-table td:nth-child(4) { width: 120px; } /* Date */
    .data-table th:nth-child(5), .data-table td:nth-child(5) { width: 120px; } /* Forêt */
    .data-table th:nth-child(6), .data-table td:nth-child(6) { width: 120px; } /* Essence */
    .data-table th:nth-child(7), .data-table td:nth-child(7) { width: 120px; } /* Localisation */
    .data-table th:nth-child(8), .data-table td:nth-child(8) { width: 120px; } /* Prix Retrait */
    .data-table th:nth-child(9), .data-table td:nth-child(9) { width: 120px; } /* Prix Vente */
    .data-table th:nth-child(10), .data-table td:nth-child(10) { width: 120px; } /* Type */
    .data-table th:nth-child(11), .data-table td:nth-child(11) { width: 100px; } /* Statut */
    .data-table th:nth-child(12), .data-table td:nth-child(12) { width: 150px; } /* Actions */

    .table-body {
        background-color: white;
    }

    .table-row {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s;
    }

    .table-row:hover {
        background-color: #f9fafb;
    }

    .table-cell {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: top;
        border-bottom: 1px solid #f3f4f6;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Allow text wrapping for specific columns that need it */
    .table-cell.text-wrap {
        white-space: normal;
        word-wrap: break-word;
    }

    .data-table.striped .table-row:nth-child(even) {
        background-color: #fafafa;
    }

    .data-table.hover .table-row:hover {
        background-color: #f3f4f6;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }

    .empty-icon {
        width: 4rem;
        height: 4rem;
        background-color: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 500;
        color: #374151;
        margin: 0 0 0.5rem 0;
    }

    .empty-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
        
        .data-table {
            min-width: 600px; /* Smaller minimum width on mobile */
        }
        
        .table-header-cell, .table-cell {
            padding: 0.5rem;
            font-size: 0.75rem;
        }
        
        .empty-state {
            padding: 2rem 1rem;
        }
        
        .empty-icon {
            width: 3rem;
            height: 3rem;
        }
        
        .empty-icon i {
            font-size: 1.5rem;
        }
    }

    /* Ensure table responsiveness works properly */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        overflow-x: auto;
        overflow-y: hidden;
        max-width: 100%;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Table scroll hint styling */
    .table-scroll-hint {
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }

    .table-scroll-hint:hover {
        opacity: 1;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush
