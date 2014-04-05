(function($) {
    $(function() {
        // checkbox control
        $('#db-table-list').on('change', '#cb-all', function(e) {
            var totalSelectedCheckbox = $('.cb-item:checked').length;
            if (totalSelectedCheckbox > 0 && totalSelectedCheckbox == $('#db-table-list tbody tr').length) {
                $('.cb-item').prop('checked', false);
            } else {
                $('.cb-item').prop('checked', true);
            }
        }).on('change', '.cb-item', function(e) {
            if ($('.cb-item:checked').length == $('#db-table-list tbody tr').length) {
                $('#cb-all').prop('checked', true);
            } else {
                $('#cb-all').prop('checked', false);
            }
        });

        // load databases
        $.ajax({
            url: 'ajax.php',
            data: {
                action: 'db-list'
            },
            success: function(data, textStatus, jqXHR) {
                $('#db-list').empty();
                for (i = 0; i < data.data.length; i++) {
                    $('#db-list').append($('<li>').append($('<a>').attr({
                        href: '#',
                        'data-dbname': data.data[i]
                    }).text(data.data[i])));
                }
                $('#db-list li:first-child > a').click();
            },
            complete: function(jqXHR, textStatus) {
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
        });

        // load xml list for database
        $('#db-list').on('click', 'a', function(e) {
            e.preventDefault();
            var selectedDb = $(this).attr('data-dbname');
            $('#db-name').text(selectedDb);
            $.ajax({
                url: 'ajax.php',
                data: {
                    action: 'schema',
                    database: selectedDb,
                },
                success: function(data, textStatus, jqXHR) {
                    $('#db-table-list tbody').empty();
                    if (data.data.length > 0) {
                        for (i = 0; i < data.data.length; i++) {
                            var tdCheckbox = $('<td>').addClass('center').append($('<input>').addClass('cb-item').attr({
                                'id': 'db-' + data.data[i],
                                'type': 'checkbox'
                            }));
                            var tdFilename = $('<td>').text(data.data[i]);
                            var row = $('<tr>').addClass((i % 2 == 0) ? 'even' : 'odd').append(tdCheckbox).append(tdFilename);
                            $('#db-table-list tbody').append(row);
                        }
                    } else {
                        var row = $('<tr>').append($('<td>').addClass('text-center').attr({
                            colspan: 2
                        }).text('No schema found'));
                        $('#db-table-list tbody').append(row);
                    }
                },
                complete: function(jqXHR, textStatus) {
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
        });
    });
} (jQuery));
