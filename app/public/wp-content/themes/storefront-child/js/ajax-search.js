jQuery(document).ready(function($) {
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        var searchQuery = $('#search-input').val();

        $.ajax({
            url: ajaxobject.ajaxurl, // URL for admin-ajax.php
            type: 'POST',
            data: {
                action: 'search_cities',
                query: searchQuery
            },
            success: function(response) {
                $('#countries-cities-table').html(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });
});