jQuery(document).ready(function($) {
	$.getJSON( "http://www.touchsize.com/red-area/lastnews.php", function( data ) {
		var items = [];
		data['action'] = 'save_touchsize_news';
		data['token'] = RedArea.token;
		$.post( ajaxurl, data, function(data, textStatus, xhr) {});
	});
});
