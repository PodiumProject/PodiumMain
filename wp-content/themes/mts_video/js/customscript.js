jQuery.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

/*----------------------------------------------------
/* Tabs
/*---------------------------------------------------*/
jQuery(document).ready(function($){
    var tabItems = $('.cd-tabs-navigation a'),
        tabContentWrapper = $('.cd-tabs-content');

    tabItems.on('click', function(event){
        event.preventDefault();
        var selectedItem = $(this);
        if( !selectedItem.hasClass('selected') ) {
            var selectedTab = selectedItem.data('content'),
                selectedContent = tabContentWrapper.find('li[data-content="'+selectedTab+'"]'),
                slectedContentHeight = selectedContent.innerHeight();
            
            tabItems.removeClass('selected');
            selectedItem.addClass('selected');
            selectedContent.addClass('selected').siblings('li').removeClass('selected');
            //animate tabContentWrapper height when content changes 
            
        }
    });

    //hide the .cd-tabs::after element when tabbed navigation has scrolled to the end (mobile version)
    checkScrolling($('.cd-tabs nav'));
    $(window).on('resize', function(){
        checkScrolling($('.cd-tabs nav'));
        tabContentWrapper.css('height', 'auto');
    });
    $('.cd-tabs nav').on('scroll', function(){ 
        checkScrolling($(this));
    });

    function checkScrolling(tabs){
        var totalTabWidth = parseInt(tabs.children('.cd-tabs-navigation').width()),
            tabsViewport = parseInt(tabs.width());
        if( tabs.scrollLeft() >= totalTabWidth - tabsViewport) {
            tabs.parent('.cd-tabs').addClass('is-ended');
        } else {
            tabs.parent('.cd-tabs').removeClass('is-ended');
        }
    }
});

/*----------------------------------------------------
/* Post Toggle
/*---------------------------------------------------*/
jQuery(document).ready(function() {
    jQuery( ".post-toggle" ).click(function() {
        jQuery( ".togglecontent" ).slideToggle( "slow" );
    });
});

/*----------------------------------------------------
/* Watch Later
/*---------------------------------------------------*/
jQuery(document).ready(function($) {
    $(document).on('click', '.watchlater', function(event) {
        event.preventDefault();
        var $this = $(this),
            postid = $this.data('postid').toString(),
            bookmarked = $.cookie('bookmarked') ? $.cookie('bookmarked').split(',') : [];
        if ( $.inArray(postid, bookmarked) == -1 ) {
            // add it
            bookmarked.push(postid);
            //$this.addClass('active');
            // mark other thumbs of same post on current page
            $('.watchlater-'+postid).addClass('active');
        } else {
            // remove it
            bookmarked = jQuery.grep(bookmarked, function(value) {
              return value.toString() != postid.toString();
            });
            //$this.removeClass('active');
            // unmark other thumbs of same post on current page
            $('.watchlater-'+postid).removeClass('active');
        }
        $('.watchlater-counter').text('(' + bookmarked.length + ')');
        $.cookie('bookmarked', bookmarked, {expires: 30, path: '/'});

    });
    
    // Add 'active' class on document.ready
    // To overcome cache issues
    var bookmarked = $.cookie('bookmarked') ? $.cookie('bookmarked').split(',') : [];
    $.each(bookmarked, function(index, val) {
         $('.watchlater-'+val).addClass('active');
    });

    // Watch later menu item
    // triggered by css class 'menu-watch-later'
    if ($('.menu-watch-later').length) {
        bookmarked = $.cookie('bookmarked') ? $.cookie('bookmarked').split(',') : [];
        $('.menu-watch-later').each(function(index, el) {
            var $this = $(this);
            $this.children('a').append(' <span class="watchlater-counter"></span>');
            $this.find('.watchlater-counter').text('(' + bookmarked.length + ')');
        });
    }
});

/*----------------------------------------------------
/* Like / Dislike
/*---------------------------------------------------*/
if (mts_customscript.like) {
    jQuery(document).ready(function($) {
        if ($('#mts_like').length) {
            $('#mts_like').click(function() {
                var $this = $(this),
                    postid = $this.data('postid');
                if ($this.hasClass('active') || $this.hasClass('inactive')) {
                    return false;
                }
                // ajax
                $.ajax({
                    url: mts_customscript.ajaxurl,
                    type: 'POST',
                    data: {action: 'mts_rate', post_id: postid, rating: '1'},
                })
                .always(function() {
                    $this.addClass('active').find('.like-count').text(function() { return parseInt($(this).text())+1; });
                    $('#mts_dislike').addClass('inactive');
                });
            });
            $('#mts_dislike').click(function() {
                var $this = $(this),
                    postid = $this.data('postid');
                if ($this.hasClass('active') || $this.hasClass('inactive')) {
                    return false;
                }
                // ajax
                $.ajax({
                    url: mts_customscript.ajaxurl,
                    type: 'POST',
                    data: {action: 'mts_rate', post_id: postid, rating: '-1'},
                })
                .always(function() {
                    $this.addClass('active').find('.like-count').text(function() { return parseInt($(this).text())+1; });
                    $('#mts_like').addClass('inactive');
                });
            });

            // Retreive ratings via JS to prevent caching
            $(window).load(function() {
                $.ajax({
                    url: mts_customscript.ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {action: 'mts_ratings', post_id: $('#mts_like').data('postid')}
                })
                .done(function(data) {
                    var $like = $('#mts_like'),
                        $dislike = $('#mts_dislike');

                    $like.find('.like-count').text(data.likes);
                    $dislike.find('.like-count').text(data.dislikes);
                    var rated = parseInt(data.has_rated);
                    if (rated == 1) {
                        $like.addClass('active').removeClass('inactive');
                        $dislike.removeClass('active').addClass('inactive');
                    } else if (rated == -1) {
                        $dislike.addClass('active').removeClass('inactive');
                        $like.removeClass('active').addClass('inactive');
                    } else { // data.rated == 0
                        $like.removeClass('active inactive');
                        $dislike.removeClass('active inactive');
                    }
                });
            });
        }     
    });
}
/*----------------------------------------------------
/* Modal Window
/*---------------------------------------------------*/
    // Semicolon (;) to ensure closing of earlier scripting
    // Encapsulation
    // $ is assigned to jQuery
    ;(function($) {

         // DOM Ready
        $(function() {

            // Binding a click event
            // From jQuery v.1.7.0 use .on() instead of .bind()
            $('.share-button').bind('click', function(e) {

                // Prevents the default action to be triggered. 
                e.preventDefault();

                // Triggering bPopup when click event is fired
                $('#popup').bPopup({
                  closeClass: "fa-times"
              });

            });

        });

    })(jQuery);

/*----------------------------------------------------
/* Make all anchor links smooth scrolling
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
 // scroll handler
  var scrollToAnchor = function( id, event ) {
    // grab the element to scroll to based on the name
    var elem = $("a[name='"+ id +"']");
    // if that didn't work, look for an element with our ID
    if ( typeof( elem.offset() ) === "undefined" ) {
      elem = $("#"+id);
    }
    // if the destination element exists
    if ( typeof( elem.offset() ) !== "undefined" ) {
      // cancel default event propagation
      event.preventDefault();

      // do the scroll
      // also hide mobile menu
      var scroll_to = elem.offset().top;
      $('html, body').removeClass('mobile-menu-active').animate({
              scrollTop: scroll_to
      }, 600, 'swing', function() { if (scroll_to > 46) window.location.hash = id; } );
    }
  };
  // bind to click event
  $("a").click(function( event ) {
    // only do this if it's an anchor link
    var href = $(this).attr("href");
    if ( href && href.match("#") && href !== '#' ) {
      // scroll to the location
      var parts = href.split('#'),
        url = parts[0],
        target = parts[1];
      if ((!url || url == window.location.href.split('#')[0]) && target)
        scrollToAnchor( target, event );
    }
  });
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive && mts_customscript.nav_menu != 'none') {
    jQuery(document).ready(function($){
        $('#secondary-navigation').append('<div id="mobile-menu-overlay" />');
        // merge if two menus exist
        if (mts_customscript.nav_menu == 'both' && !$('.navigation.mobile-only').length) {
            $('.navigation').not('.mobile-menu-wrapper').find('.menu').clone().appendTo('.mobile-menu-wrapper').hide();
        }
    
        $('.toggle-mobile-menu').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('body').toggleClass('mobile-menu-active');

            if ( $('body').hasClass('mobile-menu-active') ) {
                if ( $(document).height() > $(window).height() ) {
                    var scrollTop = ( $('html').scrollTop() ) ? $('html').scrollTop() : $('body').scrollTop();
                    $('html').addClass('noscroll').css( 'top', -scrollTop );
                }
                $('#mobile-menu-overlay').fadeIn();
            } else {
                var scrollTop = parseInt( $('html').css('top') );
                $('html').removeClass('noscroll');
                $('html,body').scrollTop( -scrollTop );
                $('#mobile-menu-overlay').fadeOut();
            }
        });
    }).on('click', function(event) {

        var $target = jQuery(event.target);
        if ( ( $target.hasClass("fa") && $target.parent().hasClass("toggle-caret") ) ||  $target.hasClass("toggle-caret") ) {// allow clicking on menu toggles
            return;
        }
        jQuery('body').removeClass('mobile-menu-active');
        jQuery('html').removeClass('noscroll');
        jQuery('#mobile-menu-overlay').fadeOut();
    });
}

/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) {
    
    function mtsDropdownMenu() {
        var wWidth = $(window).width();
        if(wWidth > 865) {
            $('.navigation ul.sub-menu, .navigation ul.children').hide();
            var timer;
            var delay = 100;
            $('.navigation li').hover( 
              function() {
                var $this = $(this);
                timer = setTimeout(function() {
                    $this.children('ul.sub-menu, ul.children').slideDown('fast');
                }, delay);
                
              },
              function() {
                $(this).children('ul.sub-menu, ul.children').hide();
                clearTimeout(timer);
              }
            );
        } else {
            $('.navigation li').unbind('hover');
            $('.navigation li.active > ul.sub-menu, .navigation li.active > ul.children').show();
        }
    }

    mtsDropdownMenu();

    $(window).resize(function() {
        mtsDropdownMenu();
    });
});

/*---------------------------------------------------
/*  Vertical menus toggles
/* -------------------------------------------------*/
jQuery(document).ready(function($) {

    $('.widget_nav_menu, .navigation .menu').addClass('toggle-menu');
    $('.toggle-menu ul.sub-menu, .toggle-menu ul.children').addClass('toggle-submenu');
    $('.toggle-menu ul.sub-menu').parent().addClass('toggle-menu-item-parent');

    $('.toggle-menu .toggle-menu-item-parent').append('<span class="toggle-caret"><i class="fa fa-plus"></i></span>');

    $('.toggle-caret').click(function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('active').children('.toggle-submenu').slideToggle('fast');
    });
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function($){
    /* open share in popup window */
    $('.share-item a').on('click', function(){
        newwindow=window.open($(this).attr('href'),'','height=330,width=750');
        if (window.focus) {newwindow.focus()}
        return false;
    });
	(function(d, s) {
	  var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
	  };
	jQuery('.facebookbtn, .facebook_like').exists(function() {
	  load('//connect.facebook.net/en_US/all.js#xfbml=1&version=v2.3', 'fbjssdk');
	});
	}(document, 'script'));
});

/*----------------------------------------------------
/* Lazy load avatars
/*---------------------------------------------------*/
jQuery(document).ready(function($){
    var lazyloadAvatar = function(){
        $('.comment-author .avatar').each(function(){
            var distanceToTop = $(this).offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            var isVisible = distanceToTop - scroll < windowHeight;
            if( isVisible ){
                var hashedUrl = $(this).attr('data-src');
                if ( hashedUrl ) {
                    $(this).attr('src',hashedUrl).removeClass('loading');
                }
            }
        });
    };
    if ( $('.comment-author .avatar').length > 0 ) {
        $('.comment-author .avatar').each(function(i,el){
            $(el).attr('data-src', el.src).removeAttr('src').addClass('loading');
        });
        $(function(){
            $(window).scroll(function(){
                lazyloadAvatar();
            });
        });
        lazyloadAvatar();
    }
});