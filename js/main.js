var max_palette_colours = 5;
var ajax_url = 'index.php';
var $palettes_container;

jQuery(document).ready(function($) {
	$palettes_container = $('#clPalettes');
	$loading = $('.loading');
	$username = $('#clUser');
	defaultUsername = $username.val()
	input_clearer( $username, defaultUsername );
	
	$('#clActions a').bind('click', function(e) {
		e.preventDefault();
		var $this = $(this);
		
		var type = $this.html();
		if( type == 'mine' ) {
			type = $username.val();	
			if( !type || type == defaultUsername ) {
				alert("C'mon now! You can't do a search without a username!")
				return false;
			}
		}
		get_palettes( type );
	});
	
	$('.clPaletteActions a').live('click', function(e) {
		e.preventDefault();
		
		var $this = $(this);
		
		$this.parent().siblings('.clImagePalette').remove();
		
		var $palette = $this.parent().siblings('.clColorPalette');
		var match_all = ($this.html() == 'all') ? 1 : 0;
		var colours = get_palette_colours( $palette );
		
		get_images( $palette, colours, match_all );
	});
	
	$(document).bind('loadingstart', function(e) {
		$loading.slideDown();
	});
	$(document).bind('loadingend', function(e) {
		$loading.slideUp();
	});

});

function get_palettes( type ) {
	$(document).trigger('loadingstart');
	$.ajax({
		url: ajax_url,
		data: {
			doing_ajax: 1,
			action: 'palettes',
			type: type
		},
		success: function(data) {
			$(document).trigger('loadingend');
			$palettes_container.html(data);
		},
		dataType: 'html'
	});
}

function get_images( $palette, colours, match_all ) {
	$(document).trigger('loadingstart');
	$.ajax({
		url: ajax_url,
		data: {
			doing_ajax: 1,
			action: 'images',
			match_all: match_all,
			colours: colours
		},
		success: function(data) {
			$(document).trigger('loadingend');
			$palette.after(data);
			bind_image_events();
		},
		dataType: 'html'
	});
}

function get_palette_colours( $palette ) {
	colours = [];
	$.each( $palette.children(), function(i, elem) {
		var $this = $(this);
		var colour = $this.attr('rel').replace('#', '');
		colours.push(colour);
	});
	return colours;
}

function bind_image_events( ) {
	var $palettes = $('.clPalette');

	$.each( $palettes, function(i, elem) {
	
		var $singles = $(elem).find('.clImageSingle');
		
		if($singles.length > max_palette_colours) {
			
			var $images = $singles.find('img');
			
			$images.bind('click', function(e) {
				fade_palette( $singles );
			});
			
		} else {
			$.each($singles, function(i, elem) {
				var $single = $(elem);
				var $images = $single.find('img');
		
				if($images.length > 1) {
					$($images).bind('click', function(e) {
						var $this = $(this);
						fade_image($this);
					});
				}
			});
		}
	});
}

function fade_image( $img ) {
//	var $container = $img.parent();
	$img
		.hide()
		.appendTo($img.parent())
		.show()
		;
	/*
	$next = get_next_image( $img );
	$img.fadeOut('slow', function(e) {
		$next.fadeIn('fast');
	})
	*/
}

function fade_palette( $singles ) {
	$first = $singles.parent().children(':first');

	$first
		.hide()
		.appendTo($first.parent())
		.show();
}

function get_next_image( $img ) {
	var $next = $img.next();
	if( !$next.length ) $next = $img.parent().find('img:first');
	return $next;
}

/** 
 * Clears an input when user clicks on it, and reverts back to default text if input left empty
 * @param ID of the input
 * @param Default text for the input
 */
function input_clearer($input, defaultText) {
    
    $input.focus(function() {
        if($input.val() == defaultText) $input.val('');
    });
    $input.blur(function() {
        if( !$input.val() || $input.val() == '' ) $input.val(defaultText);
    })
}
