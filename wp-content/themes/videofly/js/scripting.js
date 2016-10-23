'use strict';
// Get all localized variables

var main_color = Videofly.main_color;
var images_loaded_active = Videofly.ts_enable_imagesloaded;
var ts_logo_content = Videofly.ts_logo_content;
var ts_onepage_layout = Videofly.ts_onepage_layout;

if (typeof ts_logo_content !== 'undefined') {
	addLogoToMenu(ts_logo_content);
}

jQuery(document).on('click', '.ts-get-calendar', function(){
	var tsYear  = jQuery(this).attr('data-year');
	var tsMonth = jQuery(this).attr('data-month');
	var classSize = 'ts-big-calendar';

	if(jQuery(this).parent().find('.ts-events-calendar').hasClass('ts-small-calendar')){
		classSize = 'ts-small-calendar';
	}

	var tsCalendar = jQuery(this).parent();
	var data = {};

	data = {
		action  : 'ts_draw_calendar',
		nonce   : Videofly.ts_security,
		tsYear  : tsYear,
		tsMonth : tsMonth,
		size    : classSize
	};

	jQuery.post(Videofly.ajaxurl, data, function(response) {

		if( response ) {
			jQuery(tsCalendar).html(response);
		}
	});
	return false;
});

function ts_set_like(){
	jQuery('.touchsize-likes, .touchsize-dislikes').on('click',function() {

		if ( jQuery(this).attr('href') !== '#' ) return;

		var link     = jQuery(this),
		 	id       = link.data('id'),
		 	likes    = link.parent().find('.touchsize-likes'),
		 	disLikes = link.parent().find('.touchsize-dislikes');

		if( link.hasClass('active') ) return false;

		link.parent().find('.active').removeClass('active');

		jQuery.post(Videofly.ajaxurl, { action:'touchsize-likes', post_id: id, act: (link.hasClass('touchsize-likes') ? 'like' : 'dislike') }, function(data){
			link.addClass('active');
			likes.attr('title', data.titleLikes)
			likes.html(data.htmlLikes);
			disLikes.attr('title', data.titleDislikes)
			disLikes.html(data.htmlDislikes);
		});

		return false;
	});
}

function setScrollContainerWidth(){

	if( jQuery('.scroll-container').length > 0 ){
		jQuery('.scroll-container').each(function(){
			// Set this element
			var element = jQuery(this);

			// Get the width of the parent.
			var elementParent = jQuery(element).parent().parent().parent().parent();
			var parentWidth = jQuery(elementParent).width();

			// Check if grid or thumb view
			if ( jQuery(elementParent).hasClass('ts-grid-view') && jQuery(window).width() > 1024 || jQuery(elementParent).hasClass('ts-thumbnail-view') && jQuery(window).width() > 1024 ) {

				// Set the width of the scroller.
				if ( jQuery(elementParent).hasClass('no-gutter') ) {
					jQuery(element).css('width', parentWidth);
				} else{
					jQuery(element).css('width', parentWidth + 39);
				}
			} else {
				jQuery(element).css('width', 1200);
			}
			// Check if mosaic view
			if ( jQuery(elementParent).find('.mosaic-view').length > 0 && jQuery(window).width() < 1024 ) {
				jQuery(element).css('width', 800);
			}
		});
	}else{
		return;
	}
}

function ts_filters(){
	if( jQuery('.ts-filters-container').length > 0 ){
		// cache container
		var $container = jQuery('.ts-filters-container');

		// initialize isotope

		$container.isotope({
			itemSelector : '.item'
		});

		jQuery(window).on('resize', function(){
			setTimeout(function(){
				$container.isotope('layout');
			}, 400);
		});

		jQuery(".ts-filters a").click(function(){
			var selector = jQuery(this).attr("data-filter");
			$container.isotope({ filter: selector });
			return false;
		});
	}else{
		return;
	}
}

/* Article carousel */

function initCarousel() {
	jQuery('.carousel-wrapper').each(function () {
		var thisElem = jQuery(this);
		var numberOfElems = parseInt(jQuery('.carousel-container', thisElem).children().length, 10);
		var oneElemWidth;
		var numberOfColumns = [
			['col-lg-2', 6],
			['col-lg-3', 4],
			['col-lg-4', 3],
			['col-lg-6', 2],
			['col-lg-12', 1]
		];
		var curentNumberOfColumns;
		var moveMargin;
		var leftHiddenElems = 0;
		var rightHiddenElems;
		var curentMargin = 0;
		var numberOfElemsDisplayed;
		var index = 0;
		var carouselContainerWidth;
		var carouselContainerWidthPercentage;
		var elemWidth;
		var elemWidthPercentage;

		while (index < numberOfColumns.length) {
			if (jQuery('.carousel-container>div', thisElem).hasClass(numberOfColumns[index][0])) {
				curentNumberOfColumns = numberOfColumns[index][1];
				break;
			}
			index++;
		}

		elemWidth = 100 / numberOfElems;
		elemWidth = elemWidth.toFixed(4);
		elemWidthPercentage = elemWidth + '%';

		function showHideArrows(){
			if(curentNumberOfColumns >= numberOfElems){

				jQuery('ul.carousel-nav > li.carousel-nav-left', thisElem).css('opacity','0.4');
				jQuery('ul.carousel-nav > li.carousel-nav-right', thisElem).css('opacity','0.4');

			} else if(leftHiddenElems === 0){

				jQuery('ul.carousel-nav > li.carousel-nav-left', thisElem).css('opacity','0.4');
				jQuery('ul.carousel-nav > li.carousel-nav-right', thisElem).css('opacity','1');

			} else if (rightHiddenElems === 0 ){

				jQuery('ul.carousel-nav > li.carousel-nav-left', thisElem).css('opacity','1');
				jQuery('ul.carousel-nav > li.carousel-nav-right', thisElem).css('opacity','0.4');

			} else {
				jQuery('ul.carousel-nav > li.carousel-nav-left', thisElem).css('opacity','1');
				jQuery('ul.carousel-nav > li.carousel-nav-right', thisElem).css('opacity','1');
			}
		}

		function reinitCarousel() {

			showHideArrows();
			jQuery('.carousel-container', thisElem).css('margin-left', 0);
			leftHiddenElems = 0;
			jQuery('ul.carousel-nav > li', thisElem).unbind('click');

			if (jQuery(window).width() <= 973) {

				carouselContainerWidth = 100 * numberOfElems;
				carouselContainerWidthPercentage = carouselContainerWidth + '%';
				rightHiddenElems = numberOfElems - 1;
				moveMargin = 100;
				curentMargin = 0;

				jQuery('ul.carousel-nav > li', thisElem).unbind('click');

				jQuery('ul.carousel-nav > li', thisElem).click(function () {

					if (jQuery(this).hasClass('carousel-nav-left')) {
						if (leftHiddenElems > 0) {
							curentMargin = curentMargin + moveMargin;
							jQuery('.carousel-container', thisElem).css('margin-left', curentMargin + '%');
							rightHiddenElems++;
							leftHiddenElems--;
						}
					} else {
						if (rightHiddenElems > 0) {
							curentMargin = curentMargin - moveMargin;
							jQuery('.carousel-container', thisElem).css('margin-left', curentMargin + '%');
							rightHiddenElems--;
							leftHiddenElems++;
						}
					}
					// Trigger arrows color change
					showHideArrows();

					echo.render();
				});

			} else {

				while (index < numberOfColumns.length) {
					if (jQuery('.carousel-container>div', thisElem).hasClass(numberOfColumns[index][0])) {
						numberOfElemsDisplayed = numberOfColumns[index][1];
						moveMargin = 100 / numberOfElemsDisplayed;
						rightHiddenElems = numberOfElems - numberOfElemsDisplayed;
						oneElemWidth = 100 / numberOfColumns[index][1];
						break;
					}
					index++;
				}

				carouselContainerWidth = oneElemWidth * numberOfElems;
				carouselContainerWidthPercentage = carouselContainerWidth + '%';

				curentMargin = 0;

				jQuery('ul.carousel-nav > li', thisElem).click(function () {

					if (jQuery(this).hasClass('carousel-nav-left')) {
						if (leftHiddenElems > 0) {
							curentMargin = curentMargin + moveMargin + 0.00001;
							jQuery('.carousel-container', thisElem).css('margin-left', curentMargin + '%');
							rightHiddenElems++;
							leftHiddenElems--;
						}
					} else {
						if (rightHiddenElems > 0) {
							curentMargin = curentMargin - moveMargin;
							jQuery('.carousel-container', thisElem).css('margin-left', curentMargin + '%');
							rightHiddenElems--;
							leftHiddenElems++;
						}
					}
					// Trigger arrows color change
					showHideArrows();
				});
			}

			//Set the container total width
			jQuery('.carousel-container', thisElem).width(carouselContainerWidthPercentage).css({
				'max-height': '9999px',
				'opacity': '1'
			});

			//Set width for each element
			jQuery('.carousel-container>div', thisElem).each(function () {
				jQuery(this).attr('style', 'width: ' + elemWidthPercentage + ' !important; float:left;');
			});
		}

		reinitCarousel();

		jQuery(window).resize(function () {
			reinitCarousel();
		});
	});
}

function visibleBeforeAnimation(){

	jQuery('.ts-grid-view.animated, .ts-thumbnail-view.animated, .ts-big-posts.animated, .ts-list-view.animated, .ts-super-posts.animated').each(function(){
		jQuery(this).find('article').each(function(index){
			var thisElem = jQuery(this);
			if( !thisElem.hasClass('shown') && thisElem.isOnScreen() === true ){
				thisElem.addClass('shown');
				thisElem.stop().delay(100*index).animate({opacity: 1},1000);
			}
		});
	});

	jQuery('.content-block.animated').each(function(index){
		var thisElem = jQuery(this);
		var pixelsFromTransform = 0;
		if( thisElem.hasClass('slideup') ){
			pixelsFromTransform = 250;
		}
		if( thisElem.isOnScreen() === true ){
			thisElem.addClass('shown');
			thisElem.animate({opacity: 1},800);
		}
	});

	jQuery('.ts-counters').each(function(index){
		var thisElem = jQuery(this);
		if ( thisElem.isOnScreen() ) {
			startCounters();
		};
	});

	jQuery('.ts-horizontal-skills > li').each(function(index){
		var thisElem = jQuery(this);
		if ( thisElem.isOnScreen() ) {
			jQuery('.ts-horizontal-skills').countTo();
		};
	});

	jQuery('.ts-vertical-skills > li').each(function(index){
		var thisElem = jQuery(this);
		if ( thisElem.isOnScreen() ) {
			jQuery('.ts-vertical-skills').countTo();
		};
	});

}

function animateArticlesOnLoad(){
	var thisElem;
	// If adds fade effect to articles in grid view
	jQuery(window).on('scroll',function(){
		jQuery('.ts-grid-view.animated, .ts-thumbnail-view.animated, .ts-big-posts.animated, .ts-list-view.animated, .ts-super-posts.animated').each(function(){
			jQuery(this).find('article').each(function(index){
				thisElem = jQuery(this);
				if( !thisElem.hasClass('shown') && thisElem.isOnScreen() === true ){
					thisElem.addClass("shown");
					thisElem.stop().delay(100*index).animate({opacity: 1},1200);
				}
			});
		});
	});
}

jQuery.fn.isOnScreen = function(){

	var win = jQuery(window);

	var viewport = {
		top : win.scrollTop(),
		left : win.scrollLeft()
	};
	viewport.right = viewport.left + win.width();
	viewport.bottom = viewport.top + win.height();

	var bounds = this.offset();
	bounds.right = bounds.left + this.outerWidth();
	bounds.bottom = bounds.top + this.outerHeight();

	return (!(viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};

function animateBlocksOnScroll(){
	var thisElem;
	jQuery(window).on('scroll',function(){
		jQuery('.content-block.animated').each(function(index){
			var thisElem = jQuery(this);
			var pixelsFromTransform = 0;
			if( thisElem.hasClass('slideup') ){
				pixelsFromTransform = 150;
			}
			if( !thisElem.hasClass('shown') && thisElem.isOnScreen() === true ){
				thisElem.addClass('shown');
				thisElem.stop().delay(100*index).animate({opacity: 1},1000);
			}
		});

		jQuery('.ts-counters').each(function(index){
			var thisElem = jQuery(this);
			if( !thisElem.hasClass('shown') && thisElem.isOnScreen() === true ){
				thisElem.addClass('shown');
				startCounters();
			}
		});

		jQuery('.ts-horizontal-skills > li').each(function(index){
			var thisElem = jQuery(this);
			if( !thisElem.hasClass('animated') && thisElem.isOnScreen() === true ){
				thisElem.addClass('shown');
				jQuery('.ts-horizontal-skills').countTo();
			}
		});

		jQuery('.ts-vertical-skills > li').each(function(index){
			var thisElem = jQuery(this);
			if( !thisElem.hasClass('animated') && thisElem.isOnScreen() === true ){
				thisElem.addClass('shown');
				jQuery('.ts-vertical-skills').countTo();
			}
		});

	});

}

function activateStickyMenu(){
    var menu = jQuery('#header .ts-header-menu').not('.ts-sidebar-menu').last(),
        // sticky_height = 0,
        offset = 0;

    // there are no menu on the page
    if ( menu.length < 1 ) {
        offset = 100;
        // sticky_height = 80;
        menu = jQuery('#header');
    }
    // else
    //     sticky_height = jQuery('.ts-sticky-menu ul').height();

    if( jQuery(window).scrollTop() > offset && !jQuery('.ts-sticky-menu').hasClass('active') ){
        // jQuery('.ts-sticky-menu').outerHeight(sticky_height);
        jQuery('.ts-sticky-menu').addClass('active');
    }

    jQuery(window).on('scroll',function(){

        // check if the offset of the menu has changed
        if(menu.length > 0 && offset !== menu.offset().top )
            offset = menu.offset().top;

        if( jQuery(window).scrollTop() > offset && !jQuery('.ts-sticky-menu').hasClass('active') ){
            // jQuery('.ts-sticky-menu').outerHeight(sticky_height);
            jQuery('.ts-sticky-menu').addClass('active');
        }

        if( jQuery(window).scrollTop() <= offset && jQuery('.ts-sticky-menu').hasClass('active') ) {
            jQuery('.ts-sticky-menu').removeClass('active');
            // jQuery('.ts-sticky-menu').outerHeight(0);
        }
    });
}

function startOnePageNav(){
	jQuery('.main-menu a').click(function(e){
		e.preventDefault();
		var navItemUrl = jQuery(this).attr('href');
		jQuery(document).scrollTo(navItemUrl,500);
	});
}

// Count the share
jQuery('.entry-meta-share li').click(function(){
    var social = jQuery(this).attr('data-social');
    var postId = jQuery(this).attr('data-post-id');
    var socialCount = jQuery(this).find('a > span');

    var data = {
            action      : 'ts_set_share',
            ts_security : Videofly.ts_security,
            postId      : postId,
            social      : social
        };

    jQuery.post(Videofly.ajaxurl, data, function(response){

        if( response ){
            jQuery(socialCount).text(response);
            jQuery('.counted').each(function(){
            	jQuery(this).text(parseInt(jQuery(this).text()) + 1);
            });
        }
    });
});

// Animation for the sharing buttons
jQuery('.post-meta-share label').click(function(e){
	jQuery('.ts-popup-share').toggleClass('animated');
	e.preventDefault();
});

function filterButtonsRegister(){
	// Adds active class to "all" button
	jQuery('.ts-filters > li:first').addClass('active');

	// Code to change the .active class on click
	jQuery('.ts-filters > li a').click(function(e){
		e.preventDefault();

		var thisElem = jQuery(this);
		jQuery('.ts-filters > li.active').removeClass('active');
		thisElem.parent().addClass('active');
		return false;
	});
}

function twitterWidgetAnimated(){
	/*Tweets widget*/
	var delay = 4000; //millisecond delay between cycles

	function cycleThru(variable, j){
		var jmax = jQuery(variable + " li").length;
		jQuery(variable + " li:eq(" + j + ")")
			.css('display', 'block')
			.animate({opacity: 1}, 600)
			.animate({opacity: 1}, delay)
			.animate({opacity: 0}, 800, function(){
				if(j+1 === jmax){
					j=0;
				}else{
					j++;
				}
				jQuery(this).css('display', 'none').animate({opacity: 0}, 10);
				cycleThru(variable, j);
			});
	}

	jQuery('.tweets').each(function(index, val) {
		//iterate through array or object
		var parent_tweets = jQuery(val).attr('id');
		var actioner = '#' + parent_tweets + ' .ts-twitter-container.dynamic .slides_container .widget-items';
		cycleThru(actioner, 0);
	});
}

function activateFancyBox(){
	if( jQuery("a[rel^='fancybox']").length > 0 ){
		jQuery("a[rel^='fancybox']").fancybox({
			prevEffect  : 'none',
			nextEffect  : 'none',
			padding: 0,
			helpers : {
				title   : {
					type: 'outside'
				},
				thumbs  : {
					width   : 50,
					height  : 50
				}
			}
		});
	}
}

//Add logo to the center of all menu item list
function addLogoToMenu(logoContent){
	var menu_item_number = jQuery(".menu-with-logo > .main-menu > li").length;
	var middle = Math.round(menu_item_number / 2);
	jQuery(".menu-with-logo > .main-menu > li:nth-child(" + middle + ")").after(jQuery('<li class="menu-logo">'+logoContent+'</li>'));
	if (typeof logoContent !== 'undefined') {
		jQuery(".ts-sticky-menu .main-menu > li:nth-child(" + middle + ")").after(jQuery('<li class="menu-logo">'+logoContent+'</li>'));
	}
	if (jQuery("#nav").hasClass('menu-with-logo')){
		jQuery('#ts-mobile-menu').before('<div class="brand-logo">'+logoContent+'</div>');
	}
}

jQuery(document).on('click', '#ts-mobile-menu .trigger', function(event){
	event.preventDefault();
	jQuery(this).parent().next().slideToggle();
});

jQuery(document).on('click', '#ts-mobile-menu .menu-item-has-children > a', function(event){
	event.preventDefault();
	if (jQuery(this).next().attr('class').split(' ')[0] === 'ts_is_mega_div') {
		jQuery(this).next().children().slideToggle();
	}else{
		jQuery(this).next().slideToggle();
	}
});

jQuery(document).on('click', '.ts-vertical-menu .menu-item-has-children > a', function(event){
	event.preventDefault();
	jQuery(this).parent().toggleClass('collapsed');
	jQuery(this).next().slideToggle();
});

function ExpireCookie(minutes, content) {
	var date = new Date();
	var m = minutes;
	date.setTime(date.getTime() + (m * 60 * 1000));
	jQuery.cookie(content, m, { expires: date, path:'/' });
}

/* Time calculating in seconds! [example: fb_like_modal(30)] P.S. After 30 seconds, the function will be run */
function fb_likeus_modal(ShowTime){
	if( jQuery('#fbpageModal').length > 0 ){
		var modalContainer = jQuery('#fbpageModal');
		var timeExe = ShowTime * 1000;
		var closeBtn = modalContainer.find('button[data-dismiss="modal"]');
		var cookie = jQuery.cookie('ts_fb_modal_cookie'),
			setTime = 360;

		if( cookie != setTime ){
			modalContainer.delay(timeExe).queue(function() {
				jQuery(this).hide();
				jQuery(this).modal('show'); //calling modal() function
				jQuery(this).dequeue();
			});
		}else{
			modalContainer.modal('hide');
		}
		//If you clicked on the close button, the function sends a cookie for 30 minutes which helps to not display modal at every recharge page
		closeBtn.on('click', function(){
			ExpireCookie(setTime, 'ts_fb_modal_cookie');
		});
	}else{
		return;
	}
}

/* This function aligns the vertical center elements */
function alignElementVerticalyCenter(){
	var container = jQuery('.site-section');

	jQuery(container).each(function(){
		if( jQuery(this).hasClass('ts-fullscreen-row') ){
			if ( jQuery(this).hasClass('ts-has-bg-slider') ) {
				var windowHeight = jQuery(this).find('.flexslider .slides li:first-child img').height();
				if ( windowHeight == 0 ) {
					setTimeout(function(){
						alignElementVerticalyCenter();
					}, 400);
				}
			} else{
				var windowHeight = jQuery(window).height();
			}
			var containerHeight = windowHeight;
		}else{
			var windowHeight = '100%';
			var containerHeight = jQuery(this).outerHeight();
		}

		var innerContent = jQuery(this).find('.container').height();
		var insertPadding = Math.round((containerHeight-innerContent)/2);
		var Bottom = 0;

		if( jQuery(this).attr('data-alignment') == 'middle' && jQuery(this).hasClass('ts-fullscreen-row') ){
			jQuery(this).css({'padding-top':insertPadding,'padding-bottom':insertPadding,'min-height':windowHeight});
		}else if( jQuery(this).attr('data-alignment') == 'top' && jQuery(this).hasClass('ts-fullscreen-row') ){
			jQuery(this).css('min-height', windowHeight);
		}else if( jQuery(this).attr('data-alignment') == 'bottom' && jQuery(this).hasClass('ts-fullscreen-row') ){
			Bottom = jQuery(this).css('padding-bottom');
			jQuery(this).css({'width':'100%','height':containerHeight,'position':'relative','min-height':windowHeight});
			jQuery(this).find('.container').css({'width':'100%','height':'100%'});
			jQuery(this).find('.row-align-bottom').css({'position':'absolute','width':'100%','bottom':Bottom});
		}
	});

	// align the elements vertically in the middle for banner box
	if( jQuery('.ts-banner-box').length > 0 ){
		jQuery('.ts-banner-box').each(function(){
			var containerHeight = jQuery(this).outerHeight();
			var innerContent = jQuery(this).find('.container').height();
			var insertPadding = Math.round((containerHeight-innerContent)/2);

			jQuery(this).css({'padding-top':insertPadding,'padding-bottom':insertPadding});
		});
	}

}

function alignMegaMenu(){
	setTimeout(function(){
		if ( jQuery('.main-menu').length > 0 ) {
			jQuery('.main-menu').each(function(){
				if( !jQuery(this).parent().hasClass('mobile_menu') ){
					var thisElem = jQuery(this).find('.is_mega .ts_is_mega_div');
					if ( jQuery(thisElem).length > 0 ) {
						var windowWidth = jQuery(window).width();
						var thisElemWidth = jQuery(thisElem).outerWidth();
						jQuery(thisElem).removeAttr('style');
						var menuOffset = jQuery(thisElem).offset().left;
						var result = Math.round((windowWidth-thisElemWidth)/2);

						var result2 = result - menuOffset;
						jQuery(thisElem).css('left',result2);
					};
				}
			});
		};
	},100);
}

function fb_comments_width(){
	setTimeout(function(){
		jQuery('#comments .fb-comments').css('width','100%');
		jQuery('#comments .fb-comments > span').css('width','100%');
		jQuery('#comments .fb-comments > span > iframe').css('width','100%');
	},300);
}

function startCounters(){

	jQuery('.ts-counters').each(function(){

		var current = jQuery(this);
		var $chart = current.find('.chart');
		var $cnvSize = (jQuery(this).data('counter-type') == 'with-track-bar') ? 160 : 'auto';
		var bar_color = current.attr('data-bar-color');
		var track_color = '#fff';

		if( bar_color == 'transparent' ) track_color = false;

		$chart.easyPieChart({
			animate: 2000,
			scaleColor: false,
			barColor: bar_color,
			trackColor: track_color,
			size: $cnvSize,
			lineWidth: 5,
			lineCap: 'square',
			onStep: function(from, to, percent) {
				jQuery(this.el).find('.percent').text(Math.round(percent)).css({
					"line-height": $cnvSize+'px',
					width: $cnvSize
				})
			}
		});

	});

}

function mosaicViewScroller(){

	//Check if mosaic view have scroll
	if( jQuery('.mosaic-view').length > 0 ){
		jQuery('.mosaic-view').each(function(){
			if(jQuery(this).data('scroll') === 'true'){
				jQuery(this).mCustomScrollbar({
					horizontalScroll: true,
					theme: "dark",
					scrollInertia: 75,
					advanced:{
						autoExpandHorizontalScroll:true,
						updateOnImageLoad: true,
						updateOnContentResize: true,
					},
					callbacks:{
						onScroll: function(){
							showMosaic();
						}
					}

				});
			}
		});
	}else{
		return;
	}
}

function showMosaic(){
	if( jQuery('.mosaic-view').length > 0 ){
		jQuery('.mosaic-view').each(function(){
			if(jQuery(this).hasClass('fade-effect')){
				jQuery(this).find('.scroll-container > div').each(function(index){
					var thisElem = jQuery(this);
					var parentOffset = thisElem.parent().parent().parent().parent().offset().left;
					var parentWidth = thisElem.parent().parent().parent().parent().outerWidth();

					if( !thisElem.hasClass('shown') && thisElem.offset().left < parentOffset+parentWidth ){
						thisElem.delay(index*2).animate({opacity:1},1000).addClass('shown');
					}
				});
			}
		});
	}else{
		return;
	}
}

function autoPlayVideo(){

	var content = jQuery('#videoframe').find('iframe');

	if ( content.length != 0 && content.length > 0 ) {
		var option = getFrameSize(content);
	}

	if ( typeof(option) == 'undefined' ) {
		return;
	}

	if( option.iframe.attr('src').indexOf('?autoplay=1') > 0 ) return;

	if ( option.videourl.indexOf('youtube') >= 0 ) {
		var videoid = option.videourl.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)\/embed\/([^\s&]+)/);
		if ( videoid == null ) {
			alert('Video [id] not available!');
		}
	} else if ( option.videourl.indexOf('vimeo') >= 0 ) {
		var videoid = option.videourl.match(/(?:https?:\/{2})?(?:w{3}\.)?player\.vimeo\.com\/video\/([0-9]*)/);
		if ( videoid == null ) {
			alert('Video [id] not available!');
		}
	} else {
			alert('No valid video url!');
	}

	jQuery('.ts-over-img').css('display','none');
	jQuery('.video-container').css('display','block');
	option.iframe.css('display','block').attr('src', option.videourl + '?autoplay=1');
}

function getFrameSize(content){

  	var frame = jQuery(content),
      	new_iframe_url = frame.attr('src').split('?feature=oembed'),
      	videoLink = new_iframe_url[0],
      	videoWidth = frame.width(),
      	videoHeight = frame.height(),
      	container = jQuery(".video-container").width(),
      	calc = parseFloat(parseFloat(videoWidth/videoHeight).toPrecision(1)),
      	frameHeight = parseInt(container/calc),
      	frameOptions = {
      		iframe:frame,
      		videourl:videoLink,
      		iwidth:container,
      		iheight:frameHeight
  		};

  	return frameOptions
}

function ts_video_view(){

	jQuery("li.has-submenu[role='item']").on("click", function (e){
		e.preventDefault();
		jQuery(this).toggleClass('openned');
	});
	if( jQuery(".scroll-view").length > 0 ){
	    jQuery(".scroll-view").mCustomScrollbar({
	        horizontalScroll:true,
	        theme:"dark",
	        scrollInertia:75,
	        advanced:{
	            autoExpandHorizontalScroll:true
	        }
	    });
	}
	//Check if mosaic view have scroll
	if( jQuery('.mosaic-view').length > 0 ){
		jQuery('.mosaic-view').each(function(){
			if(jQuery(this).attr('data-scroll') === 'true'){
				jQuery(this).mCustomScrollbar({
					horizontalScroll:true,
					theme:"dark",
					scrollInertia:75,
					advanced:{
						autoExpandHorizontalScroll:true
					},
					callbacks:{
						onScroll: function(){
							showMosaic();
						}
					}

				});
			}
		});
	}
}

/* ***
* Count down element
*/
function ts_count_down_element() {
	// find all the countdown on the page

	var countdowns = jQuery('.ts-countdown');

	countdowns.each(function(index) {
		// save contect
		var ctx = jQuery(this);

		// get date and time
		var countdown_data = ctx.find('.time-remaining'),
			date = countdown_data.data('date'),
			time = countdown_data.data('time');

		// get dom elements of the countdown
		var $days = ctx.find('.ts-days'),
			$hours = ctx.find('.ts-hours'),
			$minutes = ctx.find('.ts-minutes'),
			$seconds = ctx.find('.ts-seconds');

		// start the countdown
		var days, hours, minutes, seconds, sec_remaining, date_diff;

		start_countdown();

		function start_countdown(){
			var curr_date = new Date(),
				event_date = new Date(date + ' ' + time);

			if ( curr_date > event_date ) {
				ctx.remove();
				return;
			}

			date_diff =  Math.abs(Math.floor( (event_date - curr_date) / 1000));

			days = Math.floor( date_diff / (24*60*60) );
			sec_remaining = date_diff - days * 24*60*60;

			hours = Math.floor( sec_remaining / (60*60) );
			sec_remaining = sec_remaining - hours * 60*60;

			minutes = Math.floor( sec_remaining / (60) );
			sec_remaining = sec_remaining - minutes * 60;

			$days.text( days );
			$hours.text( hours );
			$minutes.text( minutes );
			$seconds.text( sec_remaining );

			setTimeout(start_countdown, 1000);
		}
	});
}

function ts_fullscreen_scroll_btn(){
	var container = jQuery('.site-section'),
		scroll = jQuery('.site-section').attr('data-scroll-btn');

	if ( scroll === 'yes' ) {
		container.find('.ts-scroll-down-btn > a').on('click', function(e){
			e.preventDefault();

			jQuery('html, body').animate({

				scrollTop: jQuery(this).parents('.site-section').outerHeight()

			}, 1000)
		})
	};
}


/* ******************************* */
/*          Video Carousel         */
/* ******************************* */

(function($) {
	$.fn.ts_video_carousel = function(options) {
		var ts_slider_options = $.extend({
			transition: 700
		}, options);

		var $context = $(this),
			$slides = $(this).find('.slides'),
			$slide = $slides.children('li'),
			$nav_arrows = null;

		var viewport = $(window).width(),
			slide_width = $slide.eq(0).outerWidth(true),
			current = 0,
			ts_delay = null;

		// get the height of the slide thumb ( afte the iframe has been resized )
		$(window).load(function(){
			if ( $nav_arrows !== null){
				$nav_arrows.css({ 'height': $slide.find('.thumb').height() });
			}
		});

		$(window).resize(function(){
			// delay the calculation of the viewport on resize
			if ( ts_delay !== null ){
				clearTimeout(ts_delay);
			}

			ts_delay = setTimeout(function(){
				viewport = $(window).width();
				if ( $nav_arrows !== null){
					$nav_arrows.css({ 'height': $slide.find('.thumb').height() });
				}
				ts_setWidths();
			}, 400);
		});

		// create navigations
		(function ts_createElements(){
			var navigations =  '<div class="nav-arrow prev"><span class="nav-icon icon-left-arrow"></span></div>\
								<div class="nav-arrow next"><span class="nav-icon icon-right-arrow"></span></div>';
			$slides.after(navigations);
		})();

		// set initial states for slider elements
		(function ts_video_slider_init(){
			$slides.width( slide_width * $slide.size() );
			$slide.eq(0).addClass('current-active');
			$nav_arrows = $context.find('.nav-arrow');
			$nav_arrows.eq(0).addClass('fade-me');
			ts_setWidths();
		})();

		function ts_setWidths(){
			if ( viewport < slide_width ) {
				$slide.width( viewport );
				slide_width = viewport;

				$slide.css( {
					'left': slide_width * current * -1
				});
			} else {
				$slide.removeAttr('style');
				slide_width = $slide.width();

				$slide.css( {
					'left': slide_width * current * -1
				});
			}

			if ( viewport < $context.parent('.ts-video-slider-wrap').width() ) {
				$context.parent('.ts-video-slider-wrap').width(viewport);
			} else {
				$context.parent('.ts-video-slider-wrap').removeAttr('style');
			}
		};

		$slide.on( 'click', function(){
			if ( $(this).index() < current ){
				$slide.eq(current).removeClass('current-active');
				current--;

			} else if( $(this).index() > current) {
				$slide.eq(current).removeClass('current-active');
				current++;
			}
			ts_changeSlide()
		});

		$nav_arrows.on('click', function(){
			if ( $(this).hasClass('next') ) {
				if ( current !== $slide.size() - 1) {
					$slide.eq(current).removeClass('current-active');
					current++;
					$nav_arrows.eq(0).removeClass('fade-me');
					ts_changeSlide();
				}
				if ( $nav_arrows.eq(0).hasClass('fade-me') ){
					$nav_arrows.eq(0).removeClass('fade-me');
				}
			}
			else if( $(this).hasClass('prev') ){
				if ( parseFloat($slide.eq(0).css('left').replace( 'px', '')) < 0 && current > 0 ) {
					$slide.eq(current).removeClass('current-active');
					current--;
					ts_changeSlide();
				}

				if ( $nav_arrows.eq(1).hasClass('fade-me') ){
					$nav_arrows.eq(1).removeClass('fade-me');
				}
			}
		});

		function ts_changeSlide(){
			$slide.animate({
				'left': ( slide_width ) * current * -1
			}, {
				duration: ts_slider_options.transition,
				complete: function() {
					$slide.eq(current).addClass('current-active');
				}
			});

			if ( current === 0){
				$nav_arrows.eq(0).addClass('fade-me');
			}
			else if( current === $slide.size() - 1){
				$nav_arrows.eq(1).addClass('fade-me');
			}
		}
	}
})(jQuery);

function ts_scroll_top(){
	jQuery(window).scroll(function() {
		if(jQuery(this).scrollTop() > 200){
			jQuery('#ts-back-to-top').stop().animate({
				bottom: '30px'
			}, 500);
		}else{
			jQuery('#ts-back-to-top').stop().animate({
			   bottom: '-100px'
			}, 500);
		}
	});
	jQuery('#ts-back-to-top').on('click',function() {
		jQuery('html, body').stop().animate({
		   scrollTop: 0
		}, 500, function() {
			jQuery('#ts-back-to-top').stop().animate({
				bottom: '-100px'
			}, 500);
		});
	});
}

// Detect device
function isMobile() {
	try{ document.createEvent("TouchEvent"); return true; }
	catch(e){ return false; }
}

function setAdStatistics(obj){

	var such    = jQuery(obj).closest('.vdf-video-player'),
		adEvent = '';

	if ( jQuery(obj).hasClass('ts_playButtonPoster') ) {
		adEvent = 'views';
	} else {
		adEvent = 'clicks';
	}

	jQuery.post(Videofly.ajaxurl, {
		    'action'   : 'vdf_setDataAd',
		    'nonce'    : Videofly.ts_security,
		    'keys'     : such.data('keys'),
		    'postId'   : such.data('postid'),
		    'event'    : adEvent
		}, 	function(data){

			}
	);

}

jQuery('.ts-show-btn').click(function(){
	var elem = jQuery(this);
	elem.prev().toggleClass('hidden-excerpt');
	return false;
});

jQuery(document).ready(function($){
	if ( jQuery('body').hasClass('bp-user') ) {
		jQuery('body').addClass('user-profile-page');
	}
	
	if ( jQuery('.ts-instance-container').length > 0 ){
		resizeInstance();
	}

	jQuery(document).on('click', '.ts-item-tab', function (e) {
	    e.preventDefault();

	    var id = jQuery(this).find('a').attr('href'),
	        parent = jQuery(this).closest('.ts-tab-container');

	    parent.find('.active').removeClass('active');

	    jQuery(this).addClass('active');

	    jQuery(id).addClass('active');

	});

	if ( jQuery('.ts-notlogin-favorite').length > 0 ) {
		jQuery(document).on('click', '.ts-notlogin-favorite', function(e){
			e.preventDefault();
			alert( jQuery(this).data('alert') );
		});
	}

	if ( jQuery('.post-slides').length > 0 ) {

	    jQuery('.post-slides').slick({
	        arrows: true,
	        infinite: false,
	        draggable:true,
	        prevArrow: jQuery('.post-slides .ar-left'),
	        nextArrow: jQuery('.post-slides .ar-right'),
	        responsive: [
	                 {
	                     breakpoint: 992,
	                     settings: {
	                     draggable: true }
	                 },
	                 {
	                     breakpoint: 768,
	                     settings: {
	                    draggable: true }
	                 },
	                 {
	                     breakpoint:480,
	                     settings: {
	                     draggable: true }
	                 }
	             ]
	    });

	}

	if ( jQuery('.ts-post-nona').length > 0 ) {

	     jQuery('.ts-slide-type-six').slick({
	        slidesToShow: 1,
	        slidesToScroll: 1,
	        arrows: false,
	        speed: 500,
	        fade: true,
	        cssEase: 'linear',
	        draggable:false
	    });

	    jQuery('.ts-slide-nav').slick({
	        slidesToShow: 4,
	        slidesToScroll: 1,
	        dots: false,
	        infinite:false,
	        centerMode: false,
	        lazyLoad: 'ondemand',
	        arrows:false,
	        responsive: [
	             {
	                 breakpoint: 1200,
	                 settings: {
	                 arrows: false,
	                 slidesToShow: 3 }
	             },
	             {
	                 breakpoint: 992,
	                 settings: {
	                 arrows: false,
	                 slidesToShow: 2 }
	             },
	             {
	                 breakpoint: 768,
	                 settings: {
	                 arrows: false,
	                 slidesToShow: 2 }
	             },
	             {
	                 breakpoint:480,
	                 settings: {
	                 arrows: false,
	                 slidesToShow: 2 }
	             }
	         ]
	    });

	    jQuery('.ts-slide-nav .nona-nav').on('click', function () {
	        var index = jQuery(this).data('slick-index');
	           jQuery(this).parents('.ts-slide-nav').prev().slick('slickGoTo', index);
	    });
	}

	/*** Execute if exist video ***/

	jQuery(document).on('click', '.entry-embed-links li', function(e){
		e.preventDefault();

		jQuery('.' + jQuery(this).data('modal')).modal('show');

	});

	if ( jQuery('.vdf-video-player').length > 0 ) {

		jQuery('.vdf-video-player').each(function(){

			var playerOptions = jQuery.parseJSON(jQuery(this).find('span.vdf-data-json').text()),
				height = jQuery(this).width() / 1.77;

			jQuery(this).height(height);

			jQuery(this).find('span.vdf-data-json').remove();

			jQuery('.vdf-video-player').Video(playerOptions);

		});

		jQuery(document).on('click', '.ts_playButtonPoster', function(){
			setAdStatistics(jQuery(this));
		});

		jQuery(document).on('click', '.ts_itemUnselected', function(){
			window.location.assign(jQuery(this).attr('data-link'));
		});


		jQuery(document).on('click', '.ts-send-embed', function(e){
			e.preventDefault();

			jQuery(this).closest('.ts-send-tofriend').find('.invalid').removeClass('invalid');

			var name = jQuery(this).closest('.ts-send-tofriend').find('[name="ts-name"]').val(),
				email = jQuery(this).closest('.ts-send-tofriend').find('[name="ts-email"]').val(),
				emailRegEx   = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
				message = jQuery(this).closest('.ts-send-tofriend').find('[name="ts-message"]').val(),
				result = jQuery(this).closest('.ts-send-tofriend').find('.vdf-response'),
				data = {},
				such = jQuery(this);

			if ( name == '' ) {
				jQuery(this).closest('.ts-send-tofriend').find('[name="ts-name"]').addClass('invalid');
				return;
			}

			if ( message == '' ) {
				jQuery(this).closest('.ts-send-tofriend').find('[name="ts-message"]').addClass('invalid');
				return;
			}

			if ( email == '' || !emailRegEx.test(email) ) {
				jQuery(this).closest('.ts-send-tofriend').find('[name="ts-email"]').addClass('invalid');
				return;
			}

		    data = {
		    	'action'  : 'vdf_sendEmbed',
		    	'nonce'   : Videofly.ts_security,
		    	'email'   : email,
		    	'name'    : name,
		    	'message' : message
		    }

		    jQuery.ajax({
		      	type: 'POST',
		      	cache: false,
		      	url: Videofly.ajaxurl,
		      	data: data,
		      	success: function (response) {

		      		if ( response.success ) {
		      			such.closest('.ts-send-tofriend').find('[name="ts-email"], [name="ts-name"]').val('');
		      		}

			        result.addClass(response.success ? 'vdf-success' : 'vdf-error');
			        result.text(response.success ? response.success : response.error);
		      	}
		    });

		});
	}

	/*** Execute in add post user page ***/
	if( jQuery('.ts-file').length > 0 ){

		jQuery(document).on('click', '.ts-file', function(){
			jQuery(this).parent().find('.inputfile').trigger('click');
		});

		jQuery(document).on('change', '.inputfile', function(e){
			var fileName = e.target.value.split( '\\' ).pop();
			jQuery(this).parent().find('.ts-file span').text(fileName);

			if( jQuery(this).attr('name') !== 'ts-upload-img' ) return;

			if( this.files && this.files[0] ) {
		       var reader = new FileReader();

		       reader.onload = function (event) {
		           jQuery('.ts-preview-img').html('<img src="'+ event.target.result +'">');
		       }

		       reader.readAsDataURL(this.files[0]);
			}
		});

		jQuery('.ts-select-tab li').click(function(e){
			e.preventDefault();

			var such  = jQuery(this),
				index = such.index(),
				tab   = such.data('selected');

			jQuery('.entry-tabs ul.ts-select-tab').find('.ts-select-active').removeClass('ts-select-active');
			jQuery('.entry-tabs ul.ts-select-tab').find('li').eq(index).addClass('ts-select-active');
			jQuery('.entry-tabs ul.ts-tabs').find('.ts-tab-active').removeClass('ts-tab-active');
			jQuery('.entry-tabs ul.ts-tabs').find('li').eq(index).addClass('ts-tab-active');
			jQuery('[name="selected-tab"]').val(tab);

			such.closest('.entry-tabs').find('.ts-tabs').find('input, textarea').removeAttr('required');
			such.closest('.entry-tabs').find('.ts-tabs li.ts-tab-active').find('input, textarea').attr('required', 'required');

		});

		if( jQuery('[name="selected-tab"]').val() == '' ){
			jQuery('.ts-select-tab li:first-child').trigger('click');
		}else{
			jQuery('[data-selected="'+ jQuery.trim(jQuery('[name="selected-tab"]').val()) +'"]').trigger('click');
		}

		jQuery('#ts-category-video').selectpicker();

		jQuery('[data-toggle="tooltip"]').tooltip();
	}

	/*** Execute if exists user elemnent ***/
	if( jQuery('.ts-login').length > 0 ){

		jQuery(document).on('click', '.vdf-save-playlist', function(e){
			e.preventDefault();

			var such = jQuery(this),
				data = {},
				result = such.parent().find('.vdf-response');

			result.removeClass('vdf-error');

		    data = {
		    	'action': 'vdf_addPlaylist',
		    	'nonce' : Videofly.ts_security,
		    	'name'  : jQuery('[name="vdf-name-playlist"]').val()
		    }

		    jQuery.ajax({
		      	type: 'POST',
		      	cache: false,
		      	url: Videofly.ajaxurl,
		      	data: data,
		      	success: function (response) {

		      		if ( response.success ){
		      			jQuery('[name="vdf-name-playlist"]').val('');
		      			if ( jQuery('.vdf-add-playlist').length > 0 ) {
		      				jQuery('.vdf-add-playlist').append('<li data-name="'+ response.playlistId +'">'+  response.namePlaylist +'</li>');
		      			}
		      		}

			        result.addClass(response.success ? 'vdf-success' : 'vdf-error');
			        result.text(response.success ? response.success : response.error);
		      	}
		    });

		});
		// save playlist end.

		jQuery(document).on('click', '.vdf-remove-fromplaylist, .vdf-remove-playlist', function(e){
			e.preventDefault();

			var such = jQuery(this),
				data = {},
				result = such.parent().find('.vdf-response');

			result.removeClass('vdf-error');

		    data = {
		    	'action'     : 'vdf_actionsPlaylist',
		    	'nonce'      : Videofly.ts_security,
		    	'playlistId' : such.data('playlistid'),
		    	'todo'       : such.data('action')
		    }

		    if ( such.data('action') == 'video' ) {
		    	data['postId'] = such.data('postid');
		    }

		    jQuery.ajax({
		      	type: 'POST',
		      	cache: false,
		      	url: Videofly.ajaxurl,
		      	data: data,
		      	success: function (response) {
		      		if ( response.success ) {
		      			if ( such.data('action') == 'playlist' ) {
		      				such.closest('.vdf-playlist-item').remove();
		      			} else {
		      				such.closest('.vdf-video-item').parent().remove();

		      			}
		      		} else {
		      			result.addClass('vdf-error');
		      			result.text(response);
		      		}
		      	}
		    });

		});

		jQuery('body').append('<div class="ts-body-transparent"></div>');

		jQuery(document).on('click', 'a.ts-autentification', function(e){
			if(! jQuery('body.menu-open').length > 0){
				jQuery('.ts-slidein-block').toggleClass('ts-active');
				jQuery('.ts-slidein-block .ts-toggle-icon').show();
				jQuery('.ts-body-transparent').show();
				jQuery('.ts-slidein-block .ts-user-options').hide();
				jQuery('.ts-slidein-block .ts-register-form').show();
				jQuery('body').addClass('ts-hide-scroll');
			}
			e.preventDefault();
		});

		jQuery(document).on('click', 'a.ts-username', function(e){
			if(! jQuery('body.menu-open').length > 0){
				jQuery('.ts-slidein-block').toggleClass('ts-active');
				jQuery('.ts-slidein-block .ts-register-form').hide();
				jQuery('.ts-body-transparent').show();
				jQuery('.ts-slidein-block .ts-user-options').show();
				jQuery('.ts-slidein-block .ts-toggle-icon').show();
				jQuery('body').addClass('ts-hide-scroll');
			}
			e.preventDefault();
		});

		jQuery(document).on('click', '.ts-slidein-block .ts-toggle-icon', function(e){
			e.preventDefault();

			jQuery('.ts-slidein-block').toggleClass('ts-active');
			jQuery('.ts-body-transparent').hide();
			jQuery('.ts-slidein-block .ts-toggle-icon').hide();
			jQuery('body').removeClass('ts-hide-scroll');
		});

		jQuery(document).on('click', '.ts-send-log', function(event){
			event.preventDefault();

			var such      = jQuery(this),
				thisError = such.closest('.ts-login').find('.ts-login-error'),
				login     = such.closest('.ts-login').find('input[name="login"]').val(),
				password  = such.closest('.ts-login').find('input[name="password"]').val(),
				remember  = such.closest('.ts-login').find('input[name="rememberme"]').is(':checked') ? 'forever' : 'notforever',
				nonce     = such.closest('.ts-login').find('#user-nonce').val();

			thisError.text('');

			jQuery.post(Videofly.ajaxurl, {
			        action     : 'vdf_login',
			        nonce      : nonce,
			        login      : login,
			        remember   : remember,
			        password   : password
			    }, function(data){
			        if( data.error ){
			        	thisError.text(data.error);
			        }else{
		            	var login = such.closest('.ts-login');
		            	jQuery('.ts-autentification').trigger('click');
		        		setTimeout(function(){
		        			login.html(data);
		        			login.find('.ts-username').trigger('click');
		        		}, 700);
			        }
			    }
			);
		});
	}

	if( jQuery('.ts-add-favorite').length > 0 ){
		jQuery('.ts-add-favorite').click(function(e){
		    e.preventDefault();
		    var postId = jQuery('[name="ts-post-video-id"]').val();
		    var makeAction = jQuery(this).attr('data-action');

		    jQuery.post(Videofly.ajaxurl, {
		            action     : 'tsAddFavorite',
		            ts_security: Videofly.ts_security,
		            postId     : postId,
		            makeAction : makeAction
		        }, function(data){
		            if( data == 'succes' ){
		                if( makeAction == 'add' ){
		                    jQuery('.ts-display-favorite').addClass('hidden');
		                    jQuery('.ts-remove-favorite').removeClass('hidden');
		                }else{
		                    jQuery('.ts-remove-favorite').addClass('hidden');
		                    jQuery('.ts-display-favorite').removeClass('hidden');
		                }
		            }
		        }
		    );
		});
	}

	jQuery(document).on('click', '.vdf-add-playlist li', function(e){
		var such   = jQuery(this),
			data   = {},
			result = such.closest('.vdf-add-playlist').parent().find('.vdf-response');

		if ( such.find('.icon-tick').length > 0 ) return;

		result.removeClass('vdf-error').text('');

		data = {
			'action' : 'vdf_addToPlaylist',
			'nonce'  : Videofly.ts_security,
			'postId' : such.closest('.vdf-add-playlist').attr('data-postid'),
			'name'   : such.attr('data-name')
		};

	    jQuery.ajax({
	      	type: 'POST',
	      	cache: false,
	      	url: Videofly.ajaxurl,
	      	data: data,
	      	success: function (response) {

	      		if ( response.success ) {
	      			if ( such.find('.icon-tick').length == 0 ) {
	      				such.prepend('<i class="icon-tick"></i>');
	      				such.addClass('in-playlist');
	      			}
	      		}

	      		result.addClass(response.success ? 'vdf-success' : 'vdf-error');
	      		result.text(response.success ? response.success : response.error);
	      	}
	    });
	});

	jQuery('.ts-play-button a').on('click', function(event){
		event.preventDefault();

		var viewportHeight = jQuery('.ts-video').height(),
			tsVideoframe = jQuery('.ts-video iframe');

		tsVideoframe.css({'height': viewportHeight + 'px'});

		setTimeout(function(){
			autoPlayVideo();
		},500)
  	});

	jQuery('.ts-light').click(function(){

    	if ( jQuery(this).hasClass('activated') ) {
        	jQuery(this).removeClass('activated');
        	jQuery(this).find('a > span').text("Light off");
        	jQuery('.ts-smart-cover').remove();
    	} else {
        	jQuery(this).addClass('activated');
        	jQuery(this).find('a > span').text("Light on");
        	jQuery('body').append('<div class="ts-smart-cover"></div>');
    	}

    	jQuery('.ts-video').toggleClass('is-lights-off');

    	return false;
	});

	/*** Execute in user settings page if buddypress is not activated ***/
	if( jQuery('#ts-pass-confirme').length > 0 ){

        jQuery('#ts-pass-confirme').keyup(function(){

            if( jQuery('#ts-pass').val() !== jQuery(this).val() ){
                jQuery('#ts-notconfirm').removeClass('hidden');
                jQuery('#ts-confirm').addClass('hidden');
            }else{
                jQuery('#ts-confirm').removeClass('hidden');
                jQuery('#ts-notconfirm').addClass('hidden');
            }

            if( !jQuery('#ts-confirm').hasClass('hidden') ){
                setTimeout(function(){
                    jQuery('#ts-confirm').fadeOut('slow', function(){
                        jQuery('#ts-confirm').css('display', '').addClass('hidden');
                    });
                }, 4000);
            }

        });

        jQuery('#ts-pass-confirme').focusout(function() {
            if( jQuery('#ts-pass').val() !== jQuery(this).val() ){
                jQuery('#ts-notconfirm').removeClass('hidden');
                jQuery('#ts-confirm').addClass('hidden');
            }else{
                jQuery('#ts-notconfirm').addClass('hidden');
                if( jQuery('#ts-pass').val() !== jQuery('#ts-pass-confirme').val() ){
                    jQuery('#ts-confirm').removeClass('hidden');
                    setTimeout(function(){
                        jQuery('#ts-confirm').fadeOut('slow', function(){
                            jQuery('#ts-confirm').css('display', '').addClass('hidden');
                        });
                    }, 4000);
                }
            }
        });

        jQuery('[name="update-user"]').click(function(e){
        	jQuery('#ts-notconfirm').addClass('hidden');
            if( jQuery('#ts-pass').val() !== jQuery('#ts-pass-confirme').val() ){
                e.preventDefault();
                jQuery('#ts-notconfirm').removeClass('hidden');
            }
        });
    }

	if( Videofly.animsitionIn !== 'none' || Videofly.animsitionOut !== 'none' ){
		jQuery(".animsition").animsition({
			inClass     :   Videofly.animsitionIn,
			outClass    :   Videofly.animsitionOut,
			linkElement :   '.main-menu a, .ts-user-profile-dw a, .ts-big-posts a, .ts-grid-view a, .ts-list-view a, .ts-thumbnail-view a, .ts-big-posts a, .ts-super-posts a, .ts-timeline-view a, .mosaic-view a, .ts-small-news:not(.ts-featured-area-small) a, .ts-image-element a, .ts-icon-box a, .ts-listed-features a, .featured-area-tabs .entry-content a, .ts-banner-box a, .testimonials a, .ts-ribbon-banner a, .ts-video-carousel a, .ts-powerlink a, .ts-article-accordion .inner-content a, .ts-list-users a, .ts-animsition a, .ts-pricing-view a, .teams a, .block-title a, .featured-area-content a, .ts-breadcrumbs a, .ts-featured-article a, .logo, .video-single-section a, .post-video-content a'
		});
	}

	if( jQuery(".ts-map-create").length > 0 ){
		google.maps.event.addDomListener(window, "load", initialize);
	}

	if( Videofly.rightClick == 'y' ){
		jQuery(document).on('contextmenu', function(e){
			return false;
		});
	}

	// Preloader
	if ( jQuery('.ts-page-loading').length ) {
		NProgress.configure({
			showSpinner : false,
			ease: 'ease',
			speed: 500,
			parent : '.ts-page-loading',
		});
		NProgress.start();
	};

	ts_scroll_top();
	//Count To
	$.fn.countTo = function() {

		var element = this;

		function execute() {

			element.each(function(){

				var item = $(this).find('.countTo-item');

				item.each(function(){

					var current = $(this),
						percent = current.find('.skill-level').attr('data-percent');

					if ( !current.hasClass('animated') ) {
						current.find('.skill-title').css({'color' : 'inherit'});
						if( element.hasClass('ts-horizontal-skills') ){
							current.find('.skill-level').animate({'width' : percent+'%'}, 800);
						} else {
							current.find('.skill-level').animate({'height' : percent+'%'}, 800);
						}
						current.addClass('animated');
					}

					if ( current.hasClass('animated') && element.attr('data-percentage') == 'true' && current.find('.percent').length < 1 ) {
						current.append('<span class="percent">'+percent+'%'+'</span>');
						current.find('.percent').css({'left' : percent+'%'}).delay(1600).fadeIn();
					};

					if ( percent == 100 ) {
						item.addClass('full');
					};

				})

			})

		}

		execute();

		return this;
	};

	/*
	 *
	 *  Single gallery type
	 *
	*/
	// Masonry gallery
	if ( jQuery('.gallery-type').hasClass('single_gallery5') || jQuery('.ts-gallery-element').hasClass('ts-masonry-gallery') ) {
		var container = jQuery('.single_gallery5 .inner-gallery-container, .ts-masonry-gallery .inner-gallery-container');
		container.isotope({
			itemSelector : '.item'
		});
	};
	// Trigger caption at galleries
	jQuery('.single-ts-gallery .trigger-caption .button-trigger-cap, .ts-gallery-element .trigger-caption .button-trigger-cap').on('click', function(e){
		e.preventDefault();
		jQuery(this).closest('.trigger-caption').prev().toggleClass('shown');
	})

	jQuery('.toggle_title').click(function () {
		jQuery(this).next().slideToggle('fast');
		jQuery(this).find('.toggler').toggleClass('toggled');
	});

	jQuery('#searchbox .search-trigger').on('click', function(e){
		e.preventDefault();
		jQuery(this).next().addClass('active');
	})

	jQuery('#searchbox .search-close').on('click', function(e){
		e.preventDefault();
		jQuery(this).parent().removeClass('active');
	})

	jQuery("body").keydown(function (e) {

		if(e.which == 27){
			jQuery("#searchbox .search-close").parent().removeClass('active');
		}
	})

	jQuery('.single .post-rating .rating-items > li').each(function(){
		var bar_width = jQuery(this).find('.bar-progress').data('bar-size');

		jQuery(this).find('.bar-progress').css({width: bar_width+'%'});
	})

	// Gallery overlay sharing
	jQuery('.overlay-effect .entry-controls .share-box .share-link').on('click', function(e){
		e.preventDefault();
		jQuery(this).toggleClass('shown');
	})

	$('.ts-vertical-menu').find('.menu-item-has-children').each(function(){
		var url_link = $(this).children('a').attr('href');
		$(this).children('a').attr('href','#');
		$(this).append('<span class="menu-item-url-link"><a href="'+url_link+'" title="View page"><i class="icon-link"></i></a></span>')
	});

	$('.menu-item-type-taxonomy').each(function(){
		if($(this).find('.ts_is_mega_div').length !== 0){
			$(this).addClass('menu-item-has-children is_mega');
		}
	})

	function ts_ajax_load_more(){

		$('.ts-pagination-more').click(function(){

			var loop            = parseInt( $(this).attr('data-loop') );
			var args            = $(this).attr('data-args');
			var paginationNonce = $(this).find('input[type="hidden"]').val();
			var loadmoreButton  = $(this);
			var $container      = $(this).prev();

			// Show preloader
			$('#ts-loading-preload').addClass('shown');
			loadmoreButton.attr('data-loop', loop + 1);

			jQuery.post(Videofly.ajaxurl, {
					action         : 'ts_pagination',
					args           : args,
					paginationNonce: paginationNonce,
					loop           : loop
				},  function(data){
						if( data !== '0' ){
							if( $container.hasClass('ts-filters-container') ){
								var data_content = $(data).appendTo($container);
								$container.isotope('appended', $(data_content));
								setTimeout(function(){
									$container.isotope('layout');
								},1200);
							}else{
								$container.append($(data));
							}
						}else{
							loadmoreButton.remove();
						}
						// Hide the preloader
						setTimeout(function(){
							$('#ts-loading-preload').removeClass('shown');
						},800);

						if ( Videofly.ts_enable_imagesloaded == 'Y' ) {
							var layzr = new Layzr();
						}
					}
			);
		});
	}
	ts_ajax_load_more();

	if( jQuery('.ts-captcha').length > 0 ){
		jQuery(document).on('click', '.ts-regenerate-captcha', function(event) {
			event.preventDefault();
			var data,
				such = jQuery(this);

			data = {
				'action': 'vdf_regenerateCaptcha',
				'token' : Videofly.contact_form_token
			};

			jQuery.post(Videofly.ajaxurl, data, function(data, textStatus, xhr) {
				such.parent().find('.ts-img-captcha').fadeOut('400', function(){
					such.parent().find('.ts-img-captcha').replaceWith(data);
				});
			});
		});
	}

	function ts_send_date_ajax(id){

		$(document).on('click', '.contact-form-submit', function(event) {
			event.preventDefault();

			var form         = $(this).closest('form'),
				name         = form.find('.contact-form-name'),
				email        = form.find('.contact-form-email'),
				subject      = form.find('.contact-form-subject'),
				message      = form.find('.contact-form-text'),
				emailRegEx   = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
				errors       = 0,
				custom_field = form.find('.ts_contact_custom_field'),
				data         = {},
				this_element = jQuery(this),
				capcha       = form.find('.ts-captcha');

			String.prototype.trim = function() {
				return this.replace(/^\s+|\s+$/g,"");
			};

			if ( emailRegEx.test(email.val()) ) {
				email.removeClass('invalid');
			} else {
				email.addClass('invalid');
				errors = errors + 1;
			}

			jQuery(custom_field).each(function(i,val){
				if(jQuery(this).hasClass('contact-form-require')){
					if (jQuery(this).val().trim() !== '') {
						jQuery(this).removeClass('invalid');
					} else {
						jQuery(this).addClass('invalid');
						errors = errors + 1;
					}
				}
			});

			if (name.val().trim() !== '') {
				name.removeClass('invalid');
			} else {
				name.addClass('invalid');
				errors = errors + 1;
			}

			if( capcha.length > 0 ){
				if (capcha.val().trim() !== '') {
					capcha.removeClass('invalid');
				} else {
					capcha.addClass('invalid');
					errors = errors + 1;
				}
			}

			if ( subject.length !== 0 ) {
				if (subject.val().trim() !== '') {
					subject.removeClass('invalid');
				} else {
					subject.addClass('invalid');
					errors = errors + 1;
				}
			}

			if (message.val().trim() !== '') {
				message.removeClass('invalid');
			} else {
				message.addClass('invalid');
				errors = errors + 1;
			}

			if ( errors === 0 ) {

				data['action']  = 'vdf_contact_me';
				data['token']   = Videofly.contact_form_token;
				data['name']    = name.val().trim();
				data['from']    = email.val().trim();
				data['subject'] = (subject.length) ? subject.val().trim() : '';
				data['message'] = message.val().trim();
				data['custom_field'] = new Array();

				jQuery(custom_field).each(function(i,val){
					var title = jQuery(this).next().val();
					var value = jQuery(this).val();
					var require = jQuery(this).next().next().val();
					var new_item = {value : value, title: title, require: require};
					data['custom_field'].push(new_item);
				});

				if( capcha.length > 0 ){
					data['capcha'] = capcha.val();
					data['prefix'] = capcha.closest('form').find('.ts-img-captcha').data('prefix');
				}

				$.post(Videofly.ajaxurl, data, function(data, textStatus, xhr) {
					form.find('.contact-form-messages').html('');
					if ( data !== '-1' ) {
						if ( data.status === 'ok' ) {
							form.find('.contact-form-messages').removeClass("hidden").html(Videofly.contact_form_success).addClass('success');
							this_element.attr('disabled', 'disabled');
							form.find("input, textarea").not(".contact-form-submit").val('');
						} else {
							form.find('.contact-form-messages').removeClass("hidden").html('<div class="invalid">' + data.message + '</div>');
							this_element.removeAttr('disabled');
						}

						if ( typeof data.token !== "undefined" ) {
							Videofly.contact_form_error = data.token;
						}

					} else {
						form.addClass('hidden');
						form.find('.contact-form-messages').html(Videofly.contact_form_error);
						this_element.removeAttr('disabled');
					}
				});
			}
		});
	}
	ts_send_date_ajax();


	jQuery('.ts-select-by-category li').click(function(){

		var idCategory = jQuery(this).attr('data-category-li');

		jQuery('.ts-select-by-category li').each(function(){
			if( jQuery(this).hasClass('active') ){
				jQuery(this).removeClass('active');
			}
		});

		jQuery(this).addClass('active');

		jQuery(this).closest('section').find('.ts-tabbed-category').each(function(){
			jQuery(this).css('display', 'none').removeClass('shown');
		});

		jQuery(this).closest('section').find('.ts-tabbed-category').each(function(){
			var categories = jQuery(this).attr('data-category').split('\\');
			for(var category in categories){
				if( idCategory == categories[category] ){
					var thisHtml = jQuery(this).css('display', '').get(0).outerHTML;
					jQuery('[data-category-div="' + idCategory + '"]').find('.ts-cat-row').append(thisHtml);
					jQuery(this).remove();
				}
			}
		});
	});

	jQuery('[data-video="modal"]').on('click', function (e) {
		e.preventDefault();

	    var data = {};

	    data = {
	    	'action': 'vdf-get-video',
	    	'nonce' : Videofly.ts_security,
	    	'postId': jQuery(this).data('postid')
	    }

	    jQuery('#ts-loading-preload').addClass('shown');

	    jQuery.ajax({
	      	type: 'POST',
	      	cache: false,
	      	url: Videofly.ajaxurl,
	      	data: data,
	      	success: function (data) {
		        jQuery.fancybox(data, {
		          	fitToView: false,
		          	autoSize: true,
		          	closeClick: false,
		          	openEffect: 'none',
		          	closeEffect: 'none'
		        });
		        jQuery('#ts-loading-preload').removeClass('shown');
	      	}
	    });
	});

});

var map, mapAddress, latlng, mapLat, mapLng, mapType, mapStyle, mapZoom,
	mapTypeCtrl, mapZoomCtrl, mapScaleCtrl, mapScroll, mapDraggable, mapMarker;
var style = '';

	var infinite_loading = false;
	jQuery(window).on('scroll',function() {
		jQuery(".ts-infinite-scroll").each(function(){
			var thisElem = jQuery(this);
			if( thisElem.prev().offset().top + thisElem.parent().height() - 120 < jQuery(window).scrollTop() + jQuery(window).height() && infinite_loading == false ){

				infinite_loading = true;
				jQuery(thisElem).trigger("click");
				setTimeout(function(){
					infinite_loading = false;
				}, 1000)
			}
		});
	});

function ts_select_post_by_category(){
	jQuery('.ts-select-by-category li:first-child').each(function(){
		jQuery(this).trigger('click');
	});
}

function initialize(){
	jQuery('.ts-map-create').each(function(){
		var element = jQuery(this);
		mapAddress = jQuery(element).attr('data-address');
		mapLat = jQuery(element).attr('data-lat');
		mapLng = jQuery(element).attr('data-lng');
		mapStyle = jQuery(element).attr('data-style');
		mapZoom = jQuery(element).attr('data-zoom');
		mapTypeCtrl = (jQuery(element).attr('data-type-ctrl') === 'true') ? true : false;
		mapZoomCtrl = (jQuery(element).attr('data-zoom-ctrl') === 'true') ? true : false;
		mapScaleCtrl = (jQuery(element).attr('data-scale-ctrl') === 'true') ? true : false;
		mapScroll = (jQuery(element).attr('data-scroll') === 'true') ? true : false;
		mapDraggable = (jQuery(element).attr('data-draggable') === 'true') ? true : false;
		mapMarker = jQuery(element).attr('data-marker');

		if( jQuery(element).attr('data-type') === 'ROADMAP' )
			mapType = google.maps.MapTypeId.ROADMAP
		else if( jQuery(element).attr('data-type') === 'HYBRID' )
			mapType = google.maps.MapTypeId.HYBRID
		else if( jQuery(element).attr('data-type') === 'SATELLITE' )
			mapType = google.maps.MapTypeId.SATELLITE
		else if( jQuery(element).attr('data-type') === 'TERRAIN' )
			mapType = google.maps.MapTypeId.TERRAIN
		else
			mapType = google.maps.MapTypeId.ROADMAP

		// How you would like to style the map.
		// This is where you would paste any style found on Snazzy Maps.
		if ( mapStyle === 'map-style-essence' ){
			style = [{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill"},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#7dcdcd"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]}]

		} else if( mapStyle === 'map-style-subtle-grayscale' ){
			style = [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]

		} else if( mapStyle === 'map-style-shades-of-grey' ){
			style = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]}]

		} else if( mapStyle === 'map-style-purple' ){
			style = [{"featureType":"all","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#bc00ff"},{"saturation":"0"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e8b8f9"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#3e114e"},{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"},{"color":"#a02aca"}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#2e093b"}]},{"featureType":"landscape.natural","elementType":"labels.text","stylers":[{"color":"#9e1010"},{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"landscape.natural.landcover","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#58176e"}]},{"featureType":"landscape.natural.landcover","elementType":"labels.text.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#a02aca"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#d180ee"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#a02aca"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"off"},{"color":"#ff0000"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"color":"#a02aca"},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#cc81e7"},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"visibility":"simplified"},{"hue":"#bc00ff"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#6d2388"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#c46ce3"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#b7918f"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#280b33"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"simplified"},{"color":"#a02aca"}]}];

		} else if( mapStyle === 'map-style-best-ski-pros' ){
			style = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#2c3645"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#dcdcdc"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#476653"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#93d09e"}]},{"featureType":"landscape.natural.terrain","elementType":"labels","stylers":[{"visibility":"on"},{"color":"#0d6f32"}]},{"featureType":"landscape.natural.terrain","elementType":"labels.text.stroke","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#62bf85"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#95c4a7"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"color":"#334767"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#334767"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#b7b7b7"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"on"},{"color":"#364a6a"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.rail","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#535353"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#3fc672"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#4d6489"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]}]

		} else {
			style = '';
		}

		latlng = new google.maps.LatLng(mapLat, mapLng);

		var mapOptions = {
			zoom: parseInt(mapZoom),
			center: latlng,
			styles: style,
			zoomControl: mapZoomCtrl,
			scaleControl: mapScaleCtrl,
			mapTypeControl: mapTypeCtrl,
			scrollwheel: mapScroll,
			draggable: mapDraggable,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
			},
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL
			},
			mapTypeId: mapType
		}
		var idElement = jQuery(element).attr('id');

		map = new google.maps.Map(document.getElementById(idElement), mapOptions);

		var marker = new google.maps.Marker({
			map: map,
			icon: mapMarker,
			position: latlng,
			title: mapAddress
		});
	});
}

jQuery(window).on('resize orientationchange', function(){
	mosaicViewScroller();
});

 //Running functions on page load
jQuery(window).on('load resize orientationchange', function(){
	alignMegaMenu();
	setScrollContainerWidth();
});

jQuery(window).load(function(){
	// window load events 
	if ( jQuery('.ts-create-playlist').length > 0 ) {
		jQuery('.ts-create-playlist .ts-form-toggle').click(function(){
			jQuery(this).parent().find('.ts-new-playlist').show(500);
		});
	}
	jQuery('.joyslider .slides-container > li').css('display','inline-block');

	if (  jQuery('.ts-imagesloaded-enabled .mosaic-view[data-scroll="true"]').length > 0 ) { 
		jQuery('.ts-imagesloaded-enabled .mosaic-view[data-scroll="true"]').each(function(){
			jQuery(this).css({"max-height":"9999px"});			
			jQuery(this).mCustomScrollbar("update");
			// mscr
		});
	} 
	ts_filters();
	ts_set_like();
	fb_likeus_modal(5);
	initCarousel();
	animateArticlesOnLoad();
	animateBlocksOnScroll();
	visibleBeforeAnimation();
	activateStickyMenu();
	filterButtonsRegister();
	twitterWidgetAnimated();
	activateFancyBox();
	fb_comments_width();
	alignElementVerticalyCenter();
	showMosaic();
	mosaicViewScroller();
	ts_video_view();
	alignMegaMenu();
	setScrollContainerWidth();
	ts_count_down_element();
	ts_fullscreen_scroll_btn();
	ts_select_post_by_category();

	jQuery('.joyslider').addClass('active');
	jQuery('.corena-slider').addClass('active');

	// Hide preloader
	if ( jQuery('.ts-page-loading').length ) {
		NProgress.done(true);
		setTimeout(function() {
			jQuery('.ts-page-loading').addClass('shown');
		}, 900);
		setTimeout(function(){
			jQuery('.ts-page-loading').fadeOut(500);
		},1100);
	}

	// If onepage layout - run the onepage menu
	if ( ts_onepage_layout == 'yes' ) {
		startOnePageNav();
	}

	if( jQuery('.flexslider, #flexslider, .featured-sync').length > 0 ){

		jQuery('.ts-slides').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			touch: true,
			sync: ".slide-nav"
		});

		jQuery('.slide-nav').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 200,
			itemMargin: 10,
			maxItems: 4,
			minItems: 2,
			asNavFor: '.ts-slides',
			directionNav: false,
			start: function(slider) {

			  jQuery('.ts-flex-navigation li a.next').click(function(event){
			      event.preventDefault();
			      slider.flexAnimate(slider.getTarget("next"));
			  });

			  jQuery('.ts-flex-navigation li a.prev').click(function(event){
			      event.preventDefault();
			      slider.flexAnimate(slider.getTarget("prev"));
			  });

			}
		});


		jQuery('.flexslider').each(function(){
			var nav_control;
			if( jQuery(this).hasClass('with-thumbs') ){
				nav_control = 'thumbnails';
			} else{
				nav_control = 'none';
			}
			var nav_animation = jQuery(this).attr('data-animation');
			jQuery(this).flexslider({
				animation: nav_animation,
				controlNav: nav_control,
				prevText: "",
				nextText: "",
				smoothHeight: true

			});
		});
	}

	if( jQuery('.ts-bxslider').length > 0 ){
		jQuery('.ts-bxslider').each(function(){
			var current = '#'+jQuery(this).find(".bxslider").attr('id');
			var caption = jQuery(current).find('.slider-caption');

			jQuery(current).bxSlider({
				auto: true,
				autoHover: true,
				mode: 'fade',
				pause: 5000,
				nextSelector: '#slider-next',
				prevSelector: '#slider-prev',
				nextText: '<i class="icon-right"></i>',
				prevText: '<i class="icon-left"></i>',
				speed: 1000,
				onSliderLoad: function(){
					jQuery(current).children('li').eq(0).addClass('active-slide');
					caption.find('.title').addClass('animated');
					caption.find('.sub').addClass('animated');
				},
				onSlideBefore: function(){
					caption.find('.title').removeClass('animated');
					caption.find('.sub').removeClass('animated');
				},
				onSlideAfter: function(currentSlide, totalSlides, currentSlideHtmlObject){
					jQuery('.active-slide').removeClass('active-slide');
					jQuery(current).children('li').eq(currentSlideHtmlObject).addClass('active-slide');
					caption.find('.title').addClass('animated');
					caption.find('.sub').addClass('animated');
				}
			});
		});
	}

	jQuery('.panel-heading a[data-toggle="collapse"]').on('click', function(){

		var panelCollapse = jQuery(this).parent().next();
		if ( panelCollapse.hasClass('in')) {
			jQuery(this).find('i').css({
				'-webkit-transform': 'rotate(90deg)',
				'-o-transform': 'rotate(90deg)',
				'-mz-transform': 'rotate(90deg)',
				'transform': 'rotate(90deg)'
			})
		} else {
			jQuery(this).find('i').css({
				'-webkit-transform': 'rotate(270deg)',
				'-o-transform': 'rotate(270deg)',
				'-mz-transform': 'rotate(270deg)',
				'transform': 'rotate(270deg)'
			})
		}
	});

	jQuery('.megaWrapper').each(function(){
		var _this = jQuery(this);
		if( _this.hasClass('ts-behold-menu') ){
			jQuery(this).removeClass('ts-behold-menu').addClass('ts-mega-menu');
		}
		if( !_this.hasClass('ts-sidebar-menu') ){
			_this.find('.ts_is_mega_div .sub-menu').addClass('ts_is_mega');
			_this.find('.ts_is_mega_div').parent().addClass('is_mega');
		} else {
			_this.removeClass('ts-mega-menu');
		}
	})

	var all_anchor = jQuery('.ts-article-accordion > .panel-group').find('.panel-heading');

	all_anchor.on('click', function(){
		all_anchor.not(this).removeClass('hidden');
		jQuery(this).addClass('hidden');
	});

	if ( isMobile() == false ) {
		jQuery('body').addClass('desktop-version');
	};

}); //end load function

/*** MENU TYPE SIDEBAR ***/

var bodyElement = jQuery('body'),
	menu = jQuery('.menu');

jQuery('.ts-sidebar-menu .main-menu > .page_item_has_children > a,.ts-sidebar-menu .main-menu .menu-item-has-children > a').after('<i class="icon-right-arrow-thin"></i>');
jQuery('#nav.ts-sidebar-menu').append('<i class="trigger-menu close-menu icon-close"></i>');

jQuery('.trigger-menu').click(function(e){
	var that = jQuery(this),
		subMenu = jQuery('#nav.ts-sidebar-menu .page_item_has_children, #nav.ts-sidebar-menu .menu-item-has-children'),
		wrap = that.parents('#wrapper');

	that.toggleClass('open');
	menu.find('.sub-menu').removeClass('open');
	if (that.hasClass('open-menu')) {
		menu.addClass('open');
		bodyElement.addClass('menu-open');
		bodyElement.removeClass('menu-closed');
	} else if (that.hasClass('close-menu')) {
		menu.removeClass('open');
		bodyElement.removeClass('menu-open');
		bodyElement.addClass('menu-closed');
		subMenu.find('.ts-open, .ts-sub-level-open').removeClass('ts-open ts-sub-level-open');
	}
	e.preventDefault();
});

var sub_parent = jQuery(".ts-sidebar-menu .main-menu > .page_item_has_children, .ts-sidebar-menu .main-menu > .menu-item-has-children"),
	sub_menu = jQuery('.ts-sidebar-menu .sub-menu'),
	subMenu = jQuery('#nav.ts-sidebar-menu .page_item_has_children .children, #nav.ts-sidebar-menu .menu-item-has-children .sub-menu'),
	isMegaMenuColumn = subMenu.find('[class*="ts_is_mega_menu_columns_"] > ul');

subMenu.each(function() {
	var _this = jQuery(this);
	if ( _this.closest('div').hasClass( "ts_is_mega_div" ) ) {
		_this.unwrap();
	}
});

isMegaMenuColumn.each(function() {
	isMegaMenuColumn.removeClass('sub-menu');
});

jQuery('#nav.ts-sidebar-menu .children, #nav.ts-sidebar-menu .menu-item-has-children > .sub-menu').prepend('<div class="sub-menu--back"><i class="icon-left-arrow-thin"></i><span>BACK</span></div>');

jQuery('#nav.ts-sidebar-menu .page_item_has_children, #nav.ts-sidebar-menu .menu-item-has-children').on('click', 'i', function() {
	var that = jQuery(this); //cache when you can
	var parent_menu = that.next('.sub-menu');
	var menu_index = parent_menu.index();
	parent_menu.addClass('ts-sub-level-open ts-open');
	parent_menu.removeClass('ts-close');
	/*if (that.closest('.ts-open').length) {
		that.closest('.ts-open').removeClass('ts-open');
	}*/
});

var sub_back = jQuery('.sub-menu--back');

sub_back.click(function(){
	var that = jQuery(this),
		currentItem = that.parent('.children, .sub-menu'),
		prevParent = that.closest('.sub-menu').parent().closest('.sub-menu'),
		parent_menu = that.parents('.menu');

	currentItem.removeClass('ts-open');
	currentItem.addClass('ts-close');
	currentItem.removeClass('ts-sub-level-open');
	prevParent.addClass('ts-open');
});


jQuery(document).on('keydown', function(e) {
	var subMenuO = jQuery(' #nav.ts-sidebar-menu .sub-menu.open '),
		subMenuChild = subMenuO.find( '.sub-menu.open' ),
		tsOpen = jQuery('.ts-open');

	if (e.which == 27) {
		if (( jQuery('.sub-menu.ts-open').length == 0 ) && ( jQuery('.menu-open').length > 0 )) {
			menu.removeClass( 'open' );
			bodyElement.removeClass( 'menu-open' );
			bodyElement.addClass( 'menu-closed' );
		} else{
			jQuery('.sub-menu.ts-open > .sub-menu--back').trigger('click');
		}
	}
});

/*** END MENU TYPE SIDEBAR ***/

/*** Most popular tabs ***/

var targetContainer= jQuery('.widget.widget_popular .tab-content,  .widget_tabber .tab-content, .ts-tab-container'),
	tabClick = jQuery('.widget.ts_widget.widget_popular .nav-tabs li a, .widget.ts_widget.widget_tabber .nav-tabs li a, ul.nav-tabs li a');

tabClick.click(function(event) {
	event.preventDefault();

	var _this = jQuery(this),
		target =  _this.parent().index()+1;

		_this.closest('.ts-tab-container').find('li').removeClass('active');
		_this.closest('.ts-tab-container').find('.tab-content .tab-pane').removeClass('active');
		_this.parent('li').addClass('active');
		_this.parent().parent().next().find('div.tab-pane:nth-child('+target+')').addClass('active');

});

/*** End most popular tabs ***/

/*Facebook page plugin*/
if (jQuery(".facebook-page-plugin").length) {
	var facebookPagePlugin =jQuery(".facebook-page-plugin"),
		facebookPagePluginWidth = facebookPagePlugin.parent().width();
		facebookPagePlugin.css('width', facebookPagePluginWidth + 'px');
}


;(function($, window) {

    var $win = $(window);
    var defaults = {
        gap: 0,
        horizontal: false,
        isFixed: $.noop
    };

    var supportSticky = function(elem) {
        var prefixes = ['', '-webkit-', '-moz-', '-ms-', '-o-'], prefix;
        return false;
    };

    $.fn.fixer = function(options) {
        options = $.extend({}, defaults, options);
        var hori = options.horizontal,
            cssPos = hori ? 'left' : 'top';

        return this.each(function() {
            var style = this.style,
                $this = $(this),
                $parent = $this.parent().parent();
                if ( jQuery(this).hasClass('ts-sidebar-element') ) {
                	$parent = jQuery(this).parents('.container').find('>.row');
                	console.log($parent);
                };

            if (supportSticky(this)) {
                style[cssPos] = options.gap + 'px';
                return;
            }

            $win.on('scroll', function() {
                var scrollPos = $win[hori ? 'scrollLeft' : 'scrollTop'](),
                    elemSize = $this[hori ? 'outerWidth' : 'outerHeight'](),
                    parentPos = $parent.offset()[cssPos],
                    parentSize = $parent[hori ? 'outerWidth' : 'outerHeight']();

                if (scrollPos >= parentPos - 1 && (parentSize + parentPos - 60) >= (scrollPos + elemSize)) {
                    style.position = 'relative';
                    style[cssPos] = (scrollPos - parentPos) + options.gap + 'px';
                    options.isFixed();
                } else if (scrollPos < parentPos) {
                    style.position = 'relative';
                    style[cssPos] = 0;
                } else {
                    style.position = 'relative';
                    style[cssPos] = parentSize - elemSize - 60 + 'px';
                }
            }).resize();
        });
    };
}(jQuery, this));
if ( jQuery(window).width() > 768 ) {
	jQuery('#main.sticky-sidebars-enabled #secondary').fixer();
	jQuery('.ts-sidebar-element.sidebar-is-sticky').fixer();
	jQuery('.single_style2 .header.container').fixer();
};

(function ($) {
    $.fn.TsProgressScroll = function (options) {
        // This is the easiest way to have default options.
        var settings = $.extend({
            backgroundColor: "#000",
            height: '10px',
            position: 'fixed'
        }, options);
        var mySelector = this.selector;
        this.each(function () {
            $(window).scroll(function () {
                var offsettop = parseInt($(this).scrollTop());
                var parentHeight = parseInt($('.ts-single-post').height() - $(window).height());
                var vscrollwidth = offsettop / parentHeight * 100;
                $(mySelector).css({width: vscrollwidth + '%'});
            });
            $(mySelector).css({
                backgroundColor: settings.backgroundColor,
                height: settings.height,
                position: settings.position
            });
        });
        return this;
    };
}(jQuery));

jQuery("#main.article-progress-enabled #article-progress-bar").TsProgressScroll({backgroundColor: main_color, height: '3px', position: 'fixed'});

   jQuery('.ts-post-sharing li.show-more a.closed').click(function() {
    	var lis = jQuery('.ts-post-sharing li.secondary');
    	jQuery(lis).each(function(i){
		    var t = jQuery(this);
		    setTimeout(function(){ t.toggleClass('ts-collapsed'); }, (i+1) * 50);
		});
   });


// Disable page scrolling if sidebar is open
jQuery('.ts-login .ts-btn').click(function(){
	if(jQuery('.ts-slidein-block.ts-active').length === 0 && jQuery('body.menu-open').length === 0 ){
		jQuery('body').css({'overflow': 'hidden'});
	}
});

jQuery('.ts-toggle-icon').click(function(){
	jQuery('body').css({'overflow': 'auto'});
});
// ESC button for user sidebar
jQuery(document).on('keydown', function(e) {
	if (e.which == 27) {
		if ( jQuery('.ts-slidein-block.ts-active').length > 0 ) {
			e.preventDefault();
			jQuery('.ts-slidein-block').toggleClass('ts-active');
			jQuery('.ts-body-transparent').hide();
			jQuery('body').css({'overflow':'auto'});
		} else{
			// jQuery('.sub-menu.ts-open > .sub-menu--back').trigger('click');
		}
	}
});

// Creating micro player
jQuery(window).scroll(function(){
	if ( jQuery('.ts-video-small').length > 0 ) {
		smallPlayer();
	}
	mosaicViewScroller();

	if ( jQuery('.ts-kenburns').length > 0 ) {
		kenburnsEffect();
	}

});

jQuery(window).resize(function(){
	if ( jQuery('.ts-instance-container').length > 0 ){
		resizeInstance();
	}
});
function smallPlayer() {

	if( jQuery(window).width() > 768 ) {
		var hasSticky,
			hasAdmin,
			topPos,
			normalize,
			adsPosition

		hasSticky  = ( jQuery('.ts-sticky-menu').length > 0 );
		hasAdmin  = ( jQuery('.admin-bar').length > 0 );

		topPos  = ( hasSticky ) ? jQuery('.ts-sticky-menu').height() : 0;
			topPos += ( hasAdmin ) ? 32 : 0;

		normalize = {
			'height': '100%',
			'width' : '100%',
		}

		adsPosition = {
			'left': '50%',
			'-webkit-transform': 'translatex(-50%)',
			'	 -o-transform' : 'translatex(-50%)',
			'		transform' : 'translatex(-50%)',
		}


		if( jQuery('.ts-video-small').length > 0 && jQuery('#ts_playlist').length === 0 ){
				if( jQuery('.ts-video').isOnScreen() ) {
					if(jQuery('#videoframe .ts_mainContainer').hasClass('micro-player')){
						jQuery('#videoframe .ts_mainContainer').removeClass('micro-player').css({'top' : 0});
						jQuery('.ts_videoPlayer').css(normalize);
						jQuery('.ts_videoPlayerAD').css(normalize);
						// jQuery('.ts_ads').css(adsPosition);
						// jQuery('.ts_textAdsWindow').css(adsPosition);
					}

				} else {
					jQuery('#videoframe .ts_mainContainer').addClass('micro-player');
					if( jQuery(window).width() < 1400 ){
						jQuery('#videoframe .ts_mainContainer').css({'top': topPos + 'px'});
					} else {

						jQuery('#videoframe .ts_mainContainer').css({'top': topPos - jQuery('.ts-sticky-menu').height() + 'px'});
					}
						// jQuery('.ts_ads').css(adsPosition);
						// jQuery('.ts_textAdsWindow').css(adsPosition);
				}//show small player when large player is out-of-viewport

		}//small player will not appear on playlist page
	}//small player will show up only on larger-than 768p devices
};//func
jQuery('.ts-slide-nav .nona-nav').click(function(){
	var _this = jQuery(this),
	index = _this.index();
	jQuery('.ts-slide-nav').find('.ts-nav-active').removeClass('ts-nav-active');
	jQuery('.ts-slide-nav').find('.nona-nav').eq(index).addClass('ts-nav-active');
});
function kenburnsEffect(){
		if ( jQuery('.ts-banner-box.ts-kenburns').length > 0 ){
		if( jQuery('.ts-banner-box.ts-kenburns').isOnScreen() ){
			jQuery('.ts-banner-box.ts-kenburns .ts-kenburns-inner').addClass('active');
		}
	}
}
function resizeInstance(){
	jQuery('.ts-instance-container').each(function(){
		var tsInstanceImgHeight	  = jQuery(this).find('header img').height();
		if( jQuery(this).height() <= tsInstanceImgHeight ) {
			jQuery(this).height(tsInstanceImgHeight);
			jQuery(this).find('section').height(tsInstanceImgHeight);
		}
	});
}
