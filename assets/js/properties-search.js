jQuery(document).ready(function($) {
    $('#properties-search-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: properties_ajax.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=properties_search',
            success: function(response) {
                $('#properties-search-results').html(response);
            },
            error: function() {
                $('#properties-search-results').html('<p>An error occurred. Please try again.</p>');
            }
        });
    });
});
