$ = jQuery.noConflict();

$('.icon_manager_display i').on('click', function (e) {
    $('.icon_manager_selected_icon').find('i').attr( 'class', $(e.target).attr('class') );
	$('input.icon_manager_input').val( $(e.target).attr('class') );
});

$('.icons_set_select').on( 'change', function(e) {
	var t = $(e.target).val();
	
	if ( t == 'all' ) $('.icons_manager_block').css( { 'display' : 'block' } );
	else { 
	    $('.icons_manager_block').each( function ( i, el ) {
			var mode = 'none';
		    if ( $(el).attr( 'id' ) == t + '-icons' ) mode = 'block';
			$(el).css( { 'display' : mode } );
	    });
	}
});

$('.icon_manager_search_box input').on( 'keyup', function (e) {
		var icon_search_process = false;
		
		if (!icon_search_process) { 
			var needle = $(e.target).val(), j, font = '', rgxp;
			
		if ( $('.icons_set_select').val() != 'all' ) { font = $('.icons_set_select').val(); rgxp = new RegExp(font); } else rgxp = '.*';
			
			icon_search_process = true;
			$('.icons_manager_block').each(function(i, el) {
				if ( $(el).attr('id').match( rgxp ) ) {
					j = 0;
					$(el).find('span i').each(function(i, el) {
						var rgxp = new RegExp(needle);
						if ($(el).attr('class').match(rgxp)) { $(el).parent().css({ display : 'inline-block' }); j++; }
						else $(el).parent().css({display : 'none'});
					});
					$(el).find('.icons-count').html(j + ' icons');
				}
			});
			icon_search_process = false;
		}

});