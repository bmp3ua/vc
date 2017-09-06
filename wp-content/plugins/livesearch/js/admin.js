$ = jQuery.noConflict();

$(document).ready(function() {
	$('#index_data').on( 'click', function(e) {
		$.ajax({ 'type'   : 'POST',
				'url'    :  ajaxurl,
				'data'   :  { action : 'index_search_data' }, 
				'success' : function( data ) {
				},
				'error'  : function () {
				}		
	    });
    });
});