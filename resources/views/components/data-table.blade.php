@props([
    'headers' => [],
    'rows' => [],
    'pagination' => null,
    'searchable' => true,
    'exportable' => true,
    'responsive' => true
])

<div class="data-table-wrapper">
    @if($searchable)
    <div class="table-search-section mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Rechercher..." id="tableSearch">
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="table-info">
                    <span class="text-muted">Affichage de <span class="fw-bold">{{ count($rows) }}</span> résultats</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="table-container">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        @foreach($headers as $header)
                        <th class="table-header-cell">
                            {{ $header }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr class="table-row">
                        @foreach($row as $cell)
                        <td class="table-cell">
                            {!! $cell !!}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($pagination)
    <div class="pagination-wrapper mt-3">
        {{ $pagination }}
    </div>
    @endif

    @if($exportable)
    <div class="export-section mt-3">
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-outline-primary btn-sm" onclick="exportTable('csv')">
                    <i class="fas fa-download me-1"></i>Export CSV
                </button>
                <button class="btn btn-outline-primary btn-sm ms-2" onclick="exportTable('excel')">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* Data Table Wrapper - Fixed Layout */
    .data-table-wrapper {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Table Container - Fixed Dimensions */
    .table-container {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
        position: relative;
    }

    /* Table Responsive - Fixed Layout */
    .table-responsive {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        border-radius: 8px;
        background: white;
    }

    /* Data Table - Fixed Layout */
    .data-table {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        margin: 0;
        border-collapse: collapse;
        background: white;
        table-layout: fixed;
    }

    /* Table Header Cells - Fixed Width Distribution */
    .table-header-cell {
        background: #f8f9fa;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: sticky;
        top: 0;
        z-index: 10;
        width: auto !important;
        min-width: auto !important;
        max-width: none !important;
    }

    /* Table Cells - Fixed Width Distribution */
    .table-cell {
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        width: auto !important;
        min-width: auto !important;
        max-width: none !important;
    }

    /* Table Rows */
    .table-row {
        transition: background-color 0.2s ease;
    }

    .table-row:hover {
        background-color: #f8f9fa;
    }

    /* Search Section */
    .table-search-section {
        padding: 16px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 8px 8px 0 0;
    }

    .search-box {
        position: relative;
        max-width: 300px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 2;
    }

    .search-input {
        padding-left: 40px;
        border-radius: 20px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .table-info {
        font-size: 14px;
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        padding: 16px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: center;
    }

    /* Export Section */
    .export-section {
        padding: 16px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-search-section .row {
            flex-direction: column;
            gap: 16px;
        }

        .table-search-section .col-md-6 {
            width: 100%;
            text-align: center;
        }

        .search-box {
            max-width: 100%;
        }

        .export-section .row {
            flex-direction: column;
            gap: 16px;
        }

        .export-section .col-md-6 {
            width: 100%;
            text-align: center;
        }

        .data-table {
            font-size: 14px;
        }

        .table-header-cell,
        .table-cell {
            padding: 8px 12px;
        }
    }

    /* Scrollbar Styling */
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

    /* Fixed Column Widths for Common Scenarios */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1) {
        width: 60px; /* ID columns */
    }

    .data-table th:nth-child(2),
    .data-table td:nth-child(2) {
        width: 150px; /* Name/Title columns */
    }

    .data-table th:nth-child(3),
    .data-table td:nth-child(3) {
        width: 120px; /* Date columns */
    }

    .data-table th:nth-child(4),
    .data-table td:nth-child(4) {
        width: 100px; /* Status columns */
    }

    .data-table th:nth-child(5),
    .data-table td:nth-child(5) {
        width: 100px; /* Action columns */
    }

    /* Ensure minimum table width for readability */
    .data-table {
        min-width: 600px;
    }

    /* Table header sticky positioning */
    .table-header-cell {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa;
    }

    /* Ensure consistent spacing */
    .data-table-wrapper > * {
        margin: 0;
    }

    .data-table-wrapper > *:not(:last-child) {
        margin-bottom: 0;
    }
</style>

<script>
    // Table search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const table = this.closest('.data-table-wrapper').querySelector('.data-table');
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });

    // Export functionality
    function exportTable(format) {
        const table = document.querySelector('.data-table');
        const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(row => 
            Array.from(row.querySelectorAll('td')).map(td => td.textContent.trim())
        );

        if (format === 'csv') {
            exportToCSV(headers, rows);
        } else if (format === 'excel') {
            exportToExcel(headers, rows);
        }
    }

    function exportToCSV(headers, rows) {
        const csvContent = [headers, ...rows]
            .map(row => row.map(cell => `"${cell}"`).join(','))
            .join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'export.csv';
        link.click();
    }

    function exportToExcel(headers, rows) {
        // Simple Excel export (CSV with .xlsx extension)
        const csvContent = [headers, ...rows]
            .map(row => row.map(cell => `"${cell}"`).join(','))
            .join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'export.xlsx';
        link.click();
    }
</script>
