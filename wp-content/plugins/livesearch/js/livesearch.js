$ = jQuery.noConflict();

$(document).ready(function() {
	
	var timer;
	
	function blacklistedKeys( key ){

		return 27 == key || 37 == key || 38 == key || 39 == key || 40 == key;

	}
	
	$( 'form.search-form input' ).on('keyup keypress', function ( e ) {
		
		if (timer && timer != undefined) clearTimeout(timer);
		
		var key         = e.which
		,	that        = this
		,	val 		= $.trim( $(this).val() )
		,	valEqual    = val == $(that).val()
		,	notEmpty    = '' !== val
		,	type        = $(this).data('object-type')
		,	total       = $( main ).data('number')
		,	url 		= customajax.url;	
		
		timer = setTimeout(function() {	
		
			if ( !valEqual && !notEmpty )
				return false;

			if ( val.length >= 3 || val.length >= 3 && 13 == key ) {
				
				data = { 'action' : 'livesearch', 'content' : val };	
				
				$.ajax({ 'type'   : 'POST',
				        'url'    :  url,
						'data'   :  data, 
						'success' : function( data ) {
						},
						'error'  : function () {
						}
										
				});
				
			}
		
        }, 600);		
		
		
	});
	
	
	
	
	
});