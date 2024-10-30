jQuery(function ($) {
	// multiple select with AJAX search
    $('#melibo_environment_select_pages, #melibo_excluded_pages').select2({
  		ajax: {
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 250, // delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    q: params.term, // search query
                    action: 'melibopages' // AJAX action for admin-ajax.php
                };
            },
            processResults: function (data) {
                var options = [];
                if ( data ) {
                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    $.each(data, function (index, page) { // do not forget that "index" is just auto incremented value
                        options.push( { id: page.ID, text: page.post_title, value: page.ID } );
                    });            
                }
                return {
                    results: options
                };
            },
			cache: true
		},
	});
});