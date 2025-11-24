/**
 * Excel-style Filter Dropdown for DataTables
 * Reusable component for adding Excel-like filter functionality
 */

(function($) {
    'use strict';

    // Excel-style filter functionality
    window.ExcelFilters = {
        init: function(tableId) {
            var table = $('#' + tableId).DataTable();
            var activeFilters = {};
            var filterDropdowns = {};
            var columnSearchFunctions = {};
            
            // Function to get unique values from a column
            function getUniqueValues(columnIndex) {
                var column = table.column(columnIndex);
                var data = column.data().toArray();
                var unique = [...new Set(data.map(function(val) {
                    if (val === null || val === undefined || val === '') {
                        return 'N/A';
                    }
                    // Handle HTML content - extract text
                    var text = $(val).text().trim();
                    return text === '' ? 'N/A' : text;
                }))].sort();
                return unique;
            }
            
            // Remove existing search function for a column
            function removeColumnSearch(columnIndex) {
                if (columnSearchFunctions[columnIndex]) {
                    var index = $.fn.dataTable.ext.search.indexOf(columnSearchFunctions[columnIndex]);
                    if (index !== -1) {
                        $.fn.dataTable.ext.search.splice(index, 1);
                    }
                    delete columnSearchFunctions[columnIndex];
                }
            }
            
            // Function to create filter dropdown
            function createFilterDropdown(columnIndex, columnName) {
                var uniqueValues = getUniqueValues(columnIndex);
                var selectedValues = activeFilters[columnIndex] || uniqueValues;
                
                var dropdown = $('<div class="filter-dropdown" data-column="' + columnIndex + '"></div>');
                
                // Header
                var header = $('<div class="filter-dropdown-header"></div>');
                header.append('<strong>Filtrer ' + columnName + '</strong>');
                dropdown.append(header);
                
                // Body
                var body = $('<div class="filter-dropdown-body"></div>');
                
                // Clear filter link
                var clearLink = $('<a href="#" class="filter-clear">Effacer le filtre de « ' + columnName + ' »</a>');
                clearLink.on('click', function(e) {
                    e.preventDefault();
                    activeFilters[columnIndex] = null;
                    removeColumnSearch(columnIndex);
                    table.column(columnIndex).search('').draw();
                    $('.filter-btn[data-column="' + columnIndex + '"]').removeClass('active');
                    dropdown.removeClass('show');
                });
                body.append(clearLink);
                
                // Search box
                var searchBox = $('<input type="text" class="filter-search" placeholder="Rechercher...">');
                body.append(searchBox);
                
                // Options list
                var optionsList = $('<ul class="filter-options"></ul>');
                
                // Select All option
                var selectAllOption = $('<li class="filter-option"></li>');
                var selectAllCheckbox = $('<input type="checkbox" id="select-all-' + tableId + '-' + columnIndex + '" checked>');
                var selectAllLabel = $('<label for="select-all-' + tableId + '-' + columnIndex + '">(Sélectionner tout)</label>');
                selectAllOption.append(selectAllCheckbox).append(selectAllLabel);
                optionsList.append(selectAllOption);
                
                // Individual options
                uniqueValues.forEach(function(value) {
                    var isSelected = selectedValues.includes(value);
                    var safeId = 'filter-' + tableId + '-' + columnIndex + '-' + value.replace(/[^a-zA-Z0-9]/g, '-').substring(0, 50);
                    var option = $('<li class="filter-option"></li>');
                    var checkbox = $('<input type="checkbox" id="' + safeId + '" value="' + value.replace(/"/g, '&quot;') + '" ' + (isSelected ? 'checked' : '') + '>');
                    var label = $('<label for="' + safeId + '">' + value + '</label>');
                    option.append(checkbox).append(label);
                    optionsList.append(option);
                });
                
                body.append(optionsList);
                dropdown.append(body);
                
                // Footer
                var footer = $('<div class="filter-dropdown-footer"></div>');
                var okBtn = $('<button class="filter-btn-ok">OK</button>');
                var cancelBtn = $('<button class="filter-btn-cancel">Annuler</button>');
                footer.append(okBtn).append(cancelBtn);
                dropdown.append(footer);
                
                // Search functionality
                searchBox.on('keyup', function() {
                    var searchTerm = $(this).val().toLowerCase();
                    optionsList.find('.filter-option').each(function() {
                        var text = $(this).text().toLowerCase();
                        if (text.includes(searchTerm) || text === '(sélectionner tout)') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
                
                // Select All functionality
                selectAllCheckbox.on('change', function() {
                    var isChecked = $(this).is(':checked');
                    optionsList.find('input[type="checkbox"]:not(#select-all-' + tableId + '-' + columnIndex + ')').prop('checked', isChecked);
                });
                
                // Individual checkbox change
                optionsList.on('change', 'input[type="checkbox"]:not(#select-all-' + tableId + '-' + columnIndex + ')', function() {
                    var visibleCheckboxes = optionsList.find('input[type="checkbox"]:not(#select-all-' + tableId + '-' + columnIndex + '):visible');
                    var allChecked = visibleCheckboxes.length === visibleCheckboxes.filter(':checked').length;
                    selectAllCheckbox.prop('checked', allChecked);
                });
                
                // OK button
                okBtn.on('click', function() {
                    var selected = [];
                    optionsList.find('input[type="checkbox"]:not(#select-all-' + tableId + '-' + columnIndex + '):checked').each(function() {
                        selected.push($(this).val());
                    });
                    
                    // Remove existing search function for this column
                    removeColumnSearch(columnIndex);
                    
                    if (selected.length === uniqueValues.length) {
                        // All selected, clear filter
                        activeFilters[columnIndex] = null;
                        table.column(columnIndex).search('').draw();
                        $('.filter-btn[data-column="' + columnIndex + '"]').removeClass('active');
                    } else {
                        // Apply filter
                        activeFilters[columnIndex] = selected;
                        table.column(columnIndex).search('').draw();
                        
                        // Create custom search function
                        var searchFunction = function(settings, data, dataIndex) {
                            if (settings.nTable.id !== tableId) {
                                return true;
                            }
                            var colIndex = columnIndex;
                            var cellValue = data[colIndex];
                            
                            // Handle HTML content
                            if (typeof cellValue === 'string' && cellValue.includes('<')) {
                                var $temp = $('<div>').html(cellValue);
                                cellValue = $temp.text().trim();
                            } else {
                                cellValue = cellValue === null || cellValue === '' ? 'N/A' : String(cellValue).trim();
                            }
                            
                            return selected.includes(cellValue);
                        };
                        
                        // Store the function reference
                        columnSearchFunctions[columnIndex] = searchFunction;
                        
                        // Add to DataTables search
                        $.fn.dataTable.ext.search.push(searchFunction);
                        table.draw();
                        $('.filter-btn[data-column="' + columnIndex + '"]').addClass('active');
                    }
                    
                    dropdown.removeClass('show');
                });
                
                // Cancel button
                cancelBtn.on('click', function() {
                    dropdown.removeClass('show');
                });
                
                return dropdown;
            }
            
            // Add filter buttons to headers
            $('#' + tableId + ' thead th').each(function(index) {
                var $th = $(this);
                var columnName = $th.text().trim();
                
                // Skip Actions column
                if (columnName.toLowerCase().includes('action') || columnName.toLowerCase().includes('actions')) {
                    return;
                }
                
                // Add filter button
                var $headerContent = $th.contents().filter(function() {
                    return this.nodeType === 3; // Text node
                });
                
                var $wrapper = $('<div class="flex items-center justify-between"></div>');
                var $text = $('<span>' + columnName + '</span>');
                var $filterBtn = $('<button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="' + index + '" title="Filtrer"><i class="fas fa-filter text-xs"></i></button>');
                
                $wrapper.append($text).append($filterBtn);
                $th.html($wrapper);
            });
            
            // Handle filter button clicks
            $(document).on('click', '#' + tableId + ' .filter-btn', function(e) {
                e.stopPropagation();
                var columnIndex = $(this).data('column');
                var columnName = table.column(columnIndex).header().textContent.trim();
                var th = $(this).closest('th');
                
                // Close other dropdowns
                $('.filter-dropdown').removeClass('show');
                
                // Create or get dropdown
                if (!filterDropdowns[columnIndex]) {
                    var dropdown = createFilterDropdown(columnIndex, columnName);
                    filterDropdowns[columnIndex] = dropdown;
                    $('body').append(dropdown);
                }
                
                var dropdown = filterDropdowns[columnIndex];
                
                // Position dropdown
                var thOffset = th.offset();
                var thWidth = th.outerWidth();
                dropdown.css({
                    top: (thOffset.top + th.outerHeight()) + 'px',
                    left: thOffset.left + 'px',
                    width: Math.max(250, thWidth) + 'px'
                });
                
                // Show dropdown
                dropdown.addClass('show');
                
                // Update checkboxes based on current filter
                var selectedValues = activeFilters[columnIndex];
                if (selectedValues) {
                    dropdown.find('input[type="checkbox"]').each(function() {
                        var value = $(this).val();
                        $(this).prop('checked', selectedValues.includes(value));
                    });
                }
            });
        }
    };
    
    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.filter-btn, .filter-dropdown').length) {
            $('.filter-dropdown').removeClass('show');
        }
    });
    
})(jQuery);

