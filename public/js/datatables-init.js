/**
 * DataTables Initialization Helper
 * Provides a reusable function to initialize DataTables with Excel-style filters
 */

(function($) {
    'use strict';

    window.initDataTableWithFilters = function(tableId, options) {
        var defaults = {
            order: [[0, 'desc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            processing: false,
            serverSide: false
        };

        var config = $.extend({}, defaults, options || {});
        
        // Initialize DataTable
        var table = $('#' + tableId).DataTable(config);
        
        // Initialize Excel-style filters
        if (typeof ExcelFilters !== 'undefined') {
            ExcelFilters.init(tableId);
        }
        
        return table;
    };

    // Auto-initialize tables with data-table attribute
    $(document).ready(function() {
        $('[data-table]').each(function() {
            var tableId = $(this).attr('id') || $(this).data('table');
            var options = $(this).data('options') || {};
            initDataTableWithFilters(tableId, options);
        });
    });

})(jQuery);

