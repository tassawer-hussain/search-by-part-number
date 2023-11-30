(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	function isEmpty(value){
        return (value == null || value.length === 0);
    }

	$( window ).load(function() {
		
		$("#part-number").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#search-button").click();
            }
        });
		
		$('#search-button').on('click', function() {
			let part_number = $('#part-number').val();

			if( isEmpty(part_number) ) {
				$('#part-number').css('border', '1px solid red');
				$('.part-number-error').html('Please add value for part number');
				$('.part-number-error').css('display', 'block');
			} else {
				$('#part-number').css('border', '1px solid');
				$('.part-number-error').css('display', 'none');

				$.ajax({
					type: 'POST',
					url: frontend_ajax_object.ajaxurl,
					// dataType: 'json',
					data: {
						action: "render_seach_by_results",
						part_number: part_number,
					},
					beforeSend: function() {
						jQuery('body').append("<div class='loading'></div>");
					},
					success: function (response) {
						if(response) {
							response = JSON.parse(response);
	
							if(response['status'] == 'found' ) {
								$('.search-results').html( response['html'] );
							}
						}
					},
					complete: function(){
						jQuery('.loading').remove();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			}

		});

	});
	

})( jQuery );
