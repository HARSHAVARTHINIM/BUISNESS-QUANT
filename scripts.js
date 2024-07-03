jQuery(document).ready(function($) {
    var table = $('#csv-table').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });

    // Function to add new filter
    function addFilter() {
        var filterContainer = $('#filter-container');
        var columnOptions = '';

        // Generate options for columns not already used as filters
        for (var i = 0; i < headers.length; i++) {
            if (!filterContainer.find('select[data-column="' + i + '"]').length) {
                columnOptions += '<option value="' + i + '">' + headers[i] + '</option>';
            }
        }

        if (columnOptions === '') {
            alert('All columns are already used as filters.');
            return;
        }

        var filterHTML = '<div class="filter-group">' +
            '<select class="filter-column">' +
            '<option value="">Select Column</option>' +
            columnOptions +
            '</select>' +
            '<select class="filter-value" style="display: none;">' +
            '<option value="">Select Value</option>' +
            '</select>' +
            '<button class="individual-search-button">Search</button>' +
            '</div>';

        filterContainer.append(filterHTML);
    }

    // Event listener for adding new filter
    $('#add-filter-button').on('click', function() {
        addFilter();
    });

    // Event listener for column selection change
    $(document).on('change', '.filter-column', function() {
        var columnIndex = $(this).val();
        var valueSelect = $(this).siblings('.filter-value');
        valueSelect.empty();
        valueSelect.append('<option value="">Select Value</option>');

        if (columnIndex !== "") {
            filterData[columnIndex].forEach(function(value) {
                valueSelect.append('<option value="' + value.trim() + '">' + value.trim() + '</option>');
            });
            valueSelect.show();
        } else {
            valueSelect.hide();
        }
    });

    // Function to apply filters
    function applyFilters() {
        var filters = [];

        // Collecting filters from primary select elements
        $('.filter').each(function() {
            var columnIndex = $(this).siblings('.filter-column').val();
            var value = $(this).siblings('.filter-value').val();
            if (columnIndex && value) {
                filters.push({ column: columnIndex, value: value.trim().toLowerCase() });
            }
        });

        // Applying custom search function
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            for (var i = 0; i < filters.length; i++) {
                var filter = filters[i];
                if (data[filter.column].trim().toLowerCase() !== filter.value) {
                    return false;
                }
            }
            return true;
        });

        // Redraw the table
        table.draw();

        // Removing custom search function
        $.fn.dataTable.ext.search.pop();
    }

    // Event listener for applying filters on column or value change
    $(document).on('change', '.filter-column, .filter-value', applyFilters);

    // Event listener for search button click
    $(document).on('click', '.individual-search-button', function() {
        applyFilters();
    });

    // Event listener for change in ticker and sector filters
    $('#ticker-filter, #sector-filter').on('change', function() {
        var columnIndex = $(this).data('column');
        var value = $(this).val();
        
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (data[columnIndex].trim().toLowerCase() === value.toLowerCase()) {
                return true;
            }
            return false;
        });

        table.draw();

        $.fn.dataTable.ext.search.pop();
    });
});
