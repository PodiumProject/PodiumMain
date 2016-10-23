jQuery( document ).ready(function( $ )
{
	"use strict";
	
	var media_filter = function( )
	{
		var xhr = false,
			endResult = false,
			msnry,
			container = $('#exc-media-container'),
			loader = '#exc-media-loader',
			filters =  $('#exc-media-filter'),
			counter = $('#exc-media-count'),
			searchBtn = $('#exc-media-search'),
			catFilter = $('#all-fields'),
			loadMoreBtn = container.parent().children('.exc-load-more'),
			pagination	= container.parent().children('.wp-pagination'),
			//loader = $('<div />', { class: 'loader', id: 'exc-media-loader' } ).hide(),
			loader = $('<div class="loader" id="exc-media-loader"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>').hide(),
			isMasonry = true,
			emptyMarkup = $('<div />', { id: 'exc-empty-media' } ).hide();

		function init()
		{
			if ( ! container.length ) {
				return;
			}
			
			catFilter.siblings('ul.exc-media-filter').prepend('<li><a class="active" data-id="" href="#">' + catFilter.text() + '</a></li>');

			if ( exc_media_filter.autoload === '1' || exc_media_filter.autoload === 'on' )
			{
				filters.on( 'click', '.exc-media-filter a:not(".skip-filter")', update_filter );
				filters.on( 'submit', media_search );

				$( window ).on( 'scroll', onScroll );
				$( window ).trigger( 'scroll' );
				
			} else
			{
				// Hide filters with support for autoload
				$('.autoload-only').hide();
				
				loadMoreBtn.on( 'click', load_more );
			}

	
			// Delete Post
			$('body').on( 'click', '.exc-delete-post', deletePost );

			update_counter();
			
			loader.insertAfter( container );
			emptyMarkup.insertAfter( container );
			
			isMasonry = exc_media_filter.masonry;

			if ( isMasonry )
			{
				masonry();	
			}
		}
		
		function media_search(e)
		{
			e.preventDefault();
			
			endResult = false;
			
			exc_media_filter['s'] = searchBtn.find( 'input:first' ).val();
			apply_filter();
		}
		
		function update_filter( e )
		{
			e.preventDefault();
			
			if( xhr && xhr.readyState !== 4 ) // abort previous request
			{
				xhr.abort();
			}
			
			var $this = $( this ),
				parent = $this.parents( '.exc-media-filter' ),
				type = parent.data('name');
			
			if ( type === 'cat' )
			{
				parent.find('a.active').removeClass('active');
				catFilter.text( $this.text() );
			} else if ( type === 'post_type' ) 
			{
				parent.find('a.active').removeClass('active');
			}

			parent.children('a').removeClass('active');

			$this.addClass('active');
			
			if ( type )
			{
				exc_media_filter[ type ] = $this.data('id');
			}

			endResult = false; // Reset if we have no result
			
			apply_filter();
		}
		
		function apply_filter()
		{
			if ( Masonry.data( container[0] ) )
			{
				container.masonry('destroy');
			}
			
			exc_media_filter['paged'] = 0;
			container.html( '' );
			loader.show();
			
			fetch_result( true );
		}
		
		function fetch_result( is_filter )
		{
			exc_media_filter['offset'] = container.children().length;
			emptyMarkup.hide();
			
			xhr = $.post( ajaxurl, exc_media_filter, function(r)
			{
				if( ! r.success )
				{			
					endResult = true;
					
					if ( is_filter )
					{
						update_counter( 0 );
					}
					
					( r.data ) ? emptyMarkup.html( r.data ).show() : emptyMarkup.hide();

					loader.hide();
				} else
				{
					var html = $( r.data.html );
					
					if ( true === is_filter )
					{
						container.html( html );

						if ( typeof r.data.class !== 'undefined' )
						{
							container.attr( 'class', r.data.class );
						}

						isMasonry = r.data.masonry
						
						if ( isMasonry )
						{
							masonry();
							
						} else if( Masonry.data( container[0] ) )
						{
							container.masonry('destroy');
						}
						
						loader.hide();

					} else
					{
						if ( ! Masonry.data( container[0] ) )
						{
							container.append( html );
							loader.hide();
						} else
						{
							xhr.readyState = 1;

							//container.append( html ).imagesLoaded( function(){
							html.imagesLoaded( function(){

								xhr = '';
								container.append( html );
		                    	container.masonry( 'appended', html, true );
		                    	loader.hide();
		                    });
						}
					}
					
					pagination.replaceWith( r.data.pagi );

					//pagination.html( r.data.pagi );
					update_counter( r.data.counter );
				}
				
			}, 'json');
		}
		
		function load_more( e )
		{
			if( e )
			{
				e.preventDefault();
			}
			
			if( ( xhr && xhr.readyState === 1 ) || !!endResult )
			{
				return;
			}
			
			loader.show();
			
			fetch_result();
		}
		
		function update_counter(value)
		{
			if( typeof value === 'undefined' )
			{
				value = exc_media_filter.counter;
			}
			
			counter.text( exc_media_filter.counterString.replace(/%d/, value) );
		}
		
		function onScroll()
		{
			if ( container.height() <= $( window ).scrollTop() + $( window ).height() )
			{
				load_more();
			}
		}
		
		function deletePost()
		{
			var $this = $(this),
				item = $this.parents('li:first'),
				caption = $this.parents('.thumbnail:first');

			if ( item.find('.fa-spin').length || item.find('.confirm-delete').length ){

				return;
			}
			
			var message = $( _.template( $('#tmpl-delete-post').html(), eXc.tmpl )() ).appendTo( caption );

			message.find('a.confirm-delete').on('click', function(e){
				e.preventDefault();

				//message.remove();

				var el = $(this),
					icon = el.find('i'),
					icoClass = icon.attr('class');

				icon.attr('class', 'fa fa-gear fa-spin');

				$.post( ajaxurl, {action: 'exc_delete_post', id: $this.data('id'), security: exc_media_filter.security }, function(r){

					if ( ! r.success )
					{
						eXc.notification( {id: 'item-deleted', message: r.data, effect: 'slidetop', type: 'error', 'icon': 'icon fa fa-times', insertion: 'replace', ttl: 3000, layout: 'bar'} );
					} else 
					{
						eXc.notification( {id: 'item-deleted', message: r.data, effect: 'slidetop', type: 'success', 'icon': 'icon fa fa-check', insertion: 'replace', ttl: 3000, layout: 'bar'} );
						//item.remove();
						container.masonry( 'remove', item ).masonry('layout');
					}

					icon.attr('class', icoClass);
				});

				container.masonry('layout');
			});

			message.find('a.cancel-delete').on('click', function(e){
				e.preventDefault();
				
				message.remove();
			});
		}

		function masonry()
		{
			container.imagesLoaded( function() {
				msnry = container.masonry({ 
					itemSelector: '.mason-item',
					columnWidth: '.mason-item',
        			percentPosition: true,
					isOriginLeft: exc_media_filter ? true : false,
					isAnimated: true,
					animationOptions: { duration: 750, easing: 'linear', queue: false } });
			});
		}
		
		init();
	}

	new media_filter();
});