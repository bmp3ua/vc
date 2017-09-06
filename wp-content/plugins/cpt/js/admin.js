    
$(document).ready( function() {	
	$('.tabs').on( 'click', function(e, data) {
		var href;
		e.preventDefault();
		if ( data && data.href ) e.target = $('[href="' + data.href + '"]')[0];
		if ( $(e.target).hasClass('tabs_pointer') && $(e.target).hasClass('clickable') ) {
			href = $(e.target).attr('href').replace('#', '');
			$(e.target).parents('.tabs').find('.tabs_target').each( function(i, el) {
				if ( href == $(el).attr('id') ) 
				    { $(el).addClass('active').fadeIn(1800); }
				else 
				    { $(el).removeClass('active').fadeOut(400); } 
		    });
            $(e.target).parents('.tabs').find('a.tabs_pointer').removeClass('active');
            $(e.target).addClass('active');			
		}
	});
	
	//$('#portfolio.tabs').trigger( 'click', { 'href' : '#type2' } );
	var id = $('.categorydiv').find('.categorychecklist li input:checked');
	if (id.length > 0) { 
	    id = id[0]; id = $(id).attr('id').match(/-[0-9]+/);
		$('.tabs').trigger( 'click', { 'href' : '#term' + id } );
	}
	else { $('.tabs_target').css({'display':'none'}); }
	
});