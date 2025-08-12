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
            <p class="text-sm text-gray-600">{{ $total }} élément(s) trouvé(s)</p>
        </div>
        
        <div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
            <table class="data-table {{ $striped ? 'striped' : '' }} {{ $hover ? 'hover' : '' }}">
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
        border-collapse: collapse;
        font-size: 0.875rem;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background-color: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-header-cell {
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        white-space: nowrap;
        border-bottom: 1px solid #e5e7eb;
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
        padding: 0.75rem;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: top;
        border-bottom: 1px solid #f3f4f6;
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
</style>
@endpush
