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
            <div class="pagination-wrapper mt-4">
                <div class="d-flex justify-content-center">
                    {{ $pagination }}
                </div>
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
        overflow: hidden;
    }

    .table-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .data-table {
        width: 100%;
        min-width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        table-layout: auto; /* Allow natural column sizing on mobile */
    }

    .table-header {
        background-color: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-header-cell {
        padding: 0.75rem 0.5rem;
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
        min-width: 80px; /* Minimum column width */
    }

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
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: top;
        border-bottom: 1px solid #f3f4f6;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 80px; /* Minimum column width */
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
        margin-top: 1.5rem;
        padding: 1rem 0;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-wrapper .pagination {
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-wrapper .page-link {
        color: #374151;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        min-width: 40px;
        text-align: center;
    }

    .pagination-wrapper .page-link:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
        color: #1f2937;
    }

    .pagination-wrapper .page-item.active .page-link {
        background-color: #4a7c59;
        border-color: #4a7c59;
        color: #ffffff;
    }

    .pagination-wrapper .page-item.disabled .page-link {
        color: #9ca3af;
        background-color: #f9fafb;
        border-color: #e5e7eb;
        cursor: not-allowed;
    }

    /* Ensure table responsiveness works properly */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        overflow-x: auto;
        overflow-y: hidden;
        max-width: 100%;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
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

    /* Responsive Design - Mobile First Approach */
    
    /* Extra Small devices (phones, 576px and down) */
    @media (max-width: 575.98px) {
        .data-table {
            min-width: 100%;
            font-size: 0.75rem;
        }
        
        .table-header-cell, .table-cell {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
            min-width: 60px;
        }
        
        .table-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .table-scroll-hint {
            display: none !important;
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
        
        .pagination-wrapper .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
            min-width: 35px;
        }
        
        .pagination-wrapper .pagination {
            gap: 0.25rem;
        }
    }

    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .data-table {
            min-width: 100%;
            font-size: 0.8rem;
        }
        
        .table-header-cell, .table-cell {
            padding: 0.625rem 0.375rem;
            font-size: 0.8rem;
            min-width: 70px;
        }
        
        .table-info {
            flex-direction: row;
            align-items: center;
        }
        
        .pagination-wrapper .page-link {
            padding: 0.5rem 0.625rem;
            min-width: 38px;
        }
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .data-table {
            min-width: 100%;
            font-size: 0.85rem;
        }
        
        .table-header-cell, .table-cell {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
            min-width: 80px;
        }
        
        .pagination-wrapper .page-link {
            padding: 0.5rem 0.75rem;
            min-width: 40px;
        }
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) and (max-width: 1199.98px) {
        .data-table {
            min-width: 100%;
            font-size: 0.875rem;
        }
        
        .table-header-cell, .table-cell {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
            min-width: 80px;
        }
    }

    /* Extra large devices (large desktops, 1200px and up) */
    @media (min-width: 1200px) {
        .data-table {
            min-width: 100%;
            font-size: 0.875rem;
        }
        
        .table-header-cell, .table-cell {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
            min-width: 80px;
        }
    }

    /* Landscape orientation adjustments */
    @media (orientation: landscape) and (max-height: 500px) {
        .table-header-cell, .table-cell {
            padding: 0.5rem 0.375rem;
        }
        
        .pagination-wrapper {
            margin-top: 1rem;
            padding: 0.75rem 0;
        }
    }

    /* High DPI displays */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .table-responsive {
            border-width: 0.5px;
        }
        
        .table-header-cell, .table-cell {
            border-bottom-width: 0.5px;
        }
    }

    /* Print styles */
    @media print {
        .table-responsive {
            overflow: visible;
            border: none;
            box-shadow: none;
        }
        
        .data-table {
            box-shadow: none;
            border: 1px solid #000;
        }
        
        .pagination-wrapper {
            display: none;
        }
        
        .table-scroll-hint {
            display: none;
        }
    }

    /* Accessibility improvements */
    .table-header-cell:focus,
    .table-cell:focus {
        outline: 2px solid #4a7c59;
        outline-offset: -2px;
    }

    /* Reduced motion preferences */
    @media (prefers-reduced-motion: reduce) {
        .table-row,
        .pagination-wrapper .page-link {
            transition: none;
        }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .table-header-cell,
        .table-cell {
            border-color: #000;
        }
        
        .table-row:hover {
            background-color: #000;
            color: #fff;
        }
    }
</style>
@endpush
