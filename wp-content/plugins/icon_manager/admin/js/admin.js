jQuery(document).ready(function($){
	
	$('#sl_icons_upload').on('click', function(e) {
		e.preventDefault();
		var icons_loader = wp.media({
            frame:   $(this).data.frame,			
			title: 'Upload Icons Set',
			multiple: false
		})
		icons_loader.on('select update insert', function(e){
			var state = icons_loader.state(), selection = state.get('selection').first().toJSON();
			$.ajax({ 
				type : 'POST',
				url : ajaxurl,
				data : { action : 'sl_add_zipped_font', values : selection },
				cache : false,
				complete : function(data) {
				},
				success : function(data) {	
				    if ( data.match(/Error/) ) alert(data);
                    else location.reload(); 				
				}
			});           
		});	
	    icons_loader.open();
	});
	
	$('.remove_mega_icons_set').on('click', function(e) {
		$.ajax({ 
			type : 'POST',
			url : ajaxurl,
			data : { action : 'sl_remove_zipped_font', font : $(e.target).attr('data-delete') },
			cache : false,
			complete : function(data) {
				location.reload(); 
			},
			success : function(data) {					
			}
		});		
	});
	
    $('.available_data').on( 'click', function(e) {
		
		var x, offset = $('#adminmenuwrap').width();
		
		$('.sl_tooltip .sl_tooltip_content').empty()
		.append( $('<p class="title">Class Name : <span>' + $(e.target).attr('class') + '</span></p>' + '<p class="title">Unicode : <span>' + $(e.target).attr('data-unicode') + '</span></p>') );		
		
		if ( ( $(e.target).position()['left'] + $(e.target).width()/2 ) < $('.sl_tooltip').width()/2 ) { console.log("11"); x = $(e.target).parents('.sl_all_icons_container').offset()['left']; }
        else if ( ( $(this).offset()['left'] + $(this).width()/2 + $('.sl_tooltip').width()/2 ) > $(this).parents('.sl_all_icons_container').offset()['left'] + $(this).parents('.sl_all_icons_container').width() ) 
		    { console.log("22"); x = $(e.target).parents('.sl_all_icons_container').width() - $('.sl_tooltip').width()/2; }
        else { console.log("33"); x = $(this).offset()['left'] - $('.sl_tooltip').width()/2; }
		console.log( $(this).offset()['left'] + $(this).width()/2 + $('.sl_tooltip').width()/2 + '->' + ( $(this).parents('.sl_all_icons_container').offset()['left'] + $(this).parents('.sl_all_icons_container').width() ) );
		
		$('.sl_tooltip')
		.css( { top : $(this).offset()['top'] - $('.sl_tooltip').height() - 50 + 'px', left : x + 'px' } )
		.fadeIn(400);		
		
	});
	
	$('body').append('<div class="sl_tooltip"><div class="close_sl_tooltip"><span>x</span></div><div class="sl_tooltip_content"></div></div>');
	$('.sl_tooltip .close_sl_tooltip').on( 'click', function(e) {
		$('.sl_tooltip').fadeOut(400);
	});	
	
    function js_searcher() {
		var icon_search_process = false;
		
		$('#sl_admin_icons_search input, #item_icons_manager #icon_search').on('keyup', function(e) {
			if (!icon_search_process) { 
				var needle = $(e.target).val(), j;
				icon_search_process = true;
				$('.sl_icons_block, [id*="-icons_table"').each(function(i, el) {
					j = 0;
					$(el).find('span i').each(function(i, el) {
						var rgxp = new RegExp(needle);
						if ($(el).attr('class').match(rgxp)) { $(el).parent().css({ display : 'inline-block' }); j++; }
						else $(el).parent().css({display : 'none'});
					});
					$(el).find('.icons-count').html(j + ' icons');
				});
				icon_search_process = false;
			}
		});
    }		
	
	js_searcher();
	
});