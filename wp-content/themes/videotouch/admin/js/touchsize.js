jQuery(document).ready(function($) {

	if ($('#ts-theme-bg-picker').length) {
		$('#ts-theme-bg-picker').hide();
		$('#ts-theme-bg-picker').farbtastic("#theme-bg-color");

		$("#theme-bg-color").click(function(e){
			e.stopPropagation();
			$('#ts-theme-bg-picker').show();
		});

		$('body').click(function() {
			$('#ts-theme-bg-picker').hide();
		});
	}

	if ($('#ts-primary-picker').length) {
		$('#ts-primary-picker').hide();
		$('#ts-primary-picker').farbtastic("#ts-primary-color");

		$("#ts-primary-color").click(function(e){
			e.stopPropagation();
			$('#ts-primary-picker').show();
		});

		$('body').click(function() {
			$('#ts-primary-picker').hide();
		});
	}
	if ($('.colors-section-picker-div').length) {
		$('.colors-section-picker-div').hide();

		// $('.colors-section-picker-div').farbtastic(".colors-section-picker");

		$(".colors-section-picker").click(function(e){
			e.stopPropagation();
			$(jQuery(this).next()).show();
		});

		// $('body').click(function() {
		// 	$('.colors-section-picker-div').hide();
		// });
		var pickers = $('.colors-section-picker-div');
		$.each(pickers, function( index, value ) {
			$(this).farbtastic(jQuery(this).prev());
		});
		$('body').click(function() {
			$(pickers).hide();
		});
	}

	if ($('#ts-secondary-picker').length) {
		$('#ts-secondary-picker').hide();
		$('#ts-secondary-picker').farbtastic("#ts-secondary-color");

		$("#ts-secondary-color").click(function(e){
			e.stopPropagation();
			$('#ts-secondary-picker').show();
		});

		$('body').click(function() {
			$('#ts-secondary-picker').hide();
		});
	}

	/************************************
	 * Theme options
	 ************************************/

	/**
	 * Theme background customization
	 */

	$('.ts-custom-bg').css({'display':'none'});
	var style = $('#custom-bg-options option:selected').val();

	// set defaul option for background customization
	background_options(style);

	function background_options(style) {

		if (style == 'pattern') {
			$('#ts-patterns-option').show();
		} else if (style == 'color') {
			$('#ts-bg-color').show();
		} else if (style == 'image') {
			$('#ts-bg-image').show();
		}
	}

	$('#custom-bg-options').change(function(event) {
		$('.ts-custom-bg').css({'display':'none'});
		var style = $(this).find("option:selected").val();
		background_options(style);
	});

	/**
	 * Overlay stripes/dotts effect for images
	 */

	var overlay_effect = $('#overlay-effect-for-images option:selected').val();

	if (overlay_effect == 'Y') {
		$('#overlay-effects').show();
	} else {
		$('#overlay-effects').css({'display':'none'});
	}

	$('#overlay-effect-for-images').change(function(event) {
		var overlay_effect = $(this).find('option:selected').val();
		if (overlay_effect == 'Y') {
			$('#overlay-effects').show();
		} else {
			$('#overlay-effects').css({'display':'none'});
		}
	});

	/**
	 * Theme logo
	 */
	var logo_style = $('.ts-logo-type option:selected').val();
	set_logo_style(logo_style);

	$('.ts-logo-type').change(function(event) {
		var selected_style = $(this).find('option:selected').val();
		set_logo_style(selected_style);
	});

	function set_logo_style (style) {
		if (style === 'image') {
			$('#ts-logo-fonts').css({'display':'none'});
			$('#ts-logo-image').show();
		} else {
			$('#ts-logo-fonts').show();
			$('#ts-logo-image').css({'display':'none'});
		}
	}

	/**
	 * Typography - Headings styles
	 */
	var headings_style = $('.ts-typo-headings option:selected').val();
	set_headings_style(headings_style);

	$('.ts-typo-headings').change(function(event) {
		var selected_style = $(this).find('option:selected').val();
		set_headings_style(selected_style);
	});

	function set_headings_style (style) {
		if (style === 'std') {

			$('#ts-typo-headings-gfonts').css({'display':'none'});

			$('#custom-font').css({'display':'none'});

		}else if (style === 'custom_font') {
			$('#ts-typo-headings-gfonts').css({'display':'block'});
			$('#fontchanger-headings').css({'display':'none'});
			$('.headings-subset-types').css({'display':'none'});
			$('#custom-font').css({'display':''});
			$('#headings-demo').css({'display':'none'});
			$('#headings-preview').css({'display':'none'});
			$('.logo-text-preview').css({'display':'none'});


		} else {
			$('#ts-typo-headings-gfonts').show();
			$('#custom-font').css({'display':'none'});
			$('#fontchanger-headings').css({'display':'block'});
			$('.headings-subset-types').css({'display':'block'});
			$('#headings-demo').css({'display':'block'});
			$('#headings-preview').css({'display':'block'});
			$('.logo-text-preview').css({'display':'block'});


		}
	}

	/**
	 * Typography - Primary text styles
	 */
	var primary_text_style = $('.ts-typo-primary_text option:selected').val();
	set_primary_text_style(primary_text_style);

	$('.ts-typo-primary_text').change(function(event) {
		var selected_style = $(this).find('option:selected').val();
		set_primary_text_style(selected_style);
	});

	function set_primary_text_style (style) {
		if (style === 'std') {
			$('#ts-typo-primary_text-gfonts').css({'display':'none'});
			$('#custom-primary-font').css({'display':'none'});

		} else if(style === 'custom_font') {
			$('#ts-typo-primary_text-gfonts').css({'display':'block'});
			$('#fontchanger-primary_text').css({'display':'none'});
			$('#custom-primary-font').css({'display':'block'});
			$('.primary_text-subset-types').css({'display':'none'});
			$('.primary-preview').css({'display':'none'});
			$('#primary_text-demo').css({'display':'none'});
			$('#primary_text-preview').css({'display':'none'});
		} else{
			$('#ts-typo-primary_text-gfonts').show();
			$('#fontchanger-primary_text').css({'display':'block'});
			$('.primary_text-subset-types').css({'display':'block'});
			$('#custom-primary-font').css({'display':'none'});
			$('.primary-preview').css({'display':'block'});
			$('#primary_text-demo').css({'display':'block'});
			$('#primary_text-preview').css({'display':'block'});
		}
	}

	/**
	 * Typography - Secondary text styles
	 */
	var secondary_text_style = $('.ts-typo-secondary_text option:selected').val();
	set_secondary_text_style(secondary_text_style);

	$('.ts-typo-secondary_text').change(function(event) {
		var selected_style = $(this).find('option:selected').val();
		set_secondary_text_style(selected_style);
	});

	function set_secondary_text_style (style) {
		if (style === 'std') {
			$('#ts-typo-secondary_text-gfonts').css({'display':'none'});
			$('#custom-secondary-font').css({'display':'none'});
		} else if(style === 'custom_font'){
			$('#ts-typo-secondary_text-gfonts').show();
			$('#custom-secondary-font').css({'display':'block'});
			$('#fontchanger-secondary_text').css({'display':'none'});
			$('.logo-secundary-preview').css({'display':'none'});
			$('#secondary_text-demo').css({'display':'none'});
		} else {
			$('#ts-typo-secondary_text-gfonts').show();
			$('#custom-secondary-font').css({'display':'none'});
			$('#fontchanger-secondary_text').css({'display':'block'});
			$('.logo-secundary-preview').css({'display':'block'});
			$('#secondary_text-demo').css({'display':'block'});
		}
	}

	/**
	 * Single post - Enable related posts
	 */
	var related_posts = $('.ts-related-posts option:selected').val();
	set_related_posts(related_posts);

	$('.ts-related-posts').change(function(event) {
		var related_posts = $(this).find('option:selected').val();
		set_related_posts(related_posts);
	});

	function set_related_posts (style) {
		if (style === 'N') {
			$('#ts-related-posts-options').css({'display':'none'});
		} else {
			$('#ts-related-posts-options').show();
		}
	}


	$('#enable_facebook_box').change(function(event) {
		if ( $(this).val() === 'Y' ) {
			$('#facebook_page').removeClass('hidden');
		} else {
			$('#facebook_page').addClass('hidden');
		}
	});

	/**
	 * Sticky menu
	 */
	$('#enable_sticky_menu').change(function(event) {
		if ( $(this).val() === 'Y' ) {
			$('#sticky_menu_options').removeClass('hidden');
		} else {
			$('#sticky_menu_options').addClass('hidden');
		}
	});

	if ($('#sticky_menu_bg_color_picker').length) {
		$('#sticky_menu_bg_color_picker').hide();
		$('#sticky_menu_bg_color_picker').farbtastic("#sticky_menu_bg_color");

		$("#sticky_menu_bg_color").click(function(e){
			e.stopPropagation();
			$('#sticky_menu_bg_color_picker').show();
		});

		$('body').click(function() {
			$('#sticky_menu_bg_color_picker').hide();
		});
	}

	if ($('#sticky_menu_text_color_picker').length) {
		$('#sticky_menu_text_color_picker').hide();
		$('#sticky_menu_text_color_picker').farbtastic("#sticky_menu_text_color");

		$("#sticky_menu_text_color").click(function(e){
			e.stopPropagation();
			$('#sticky_menu_text_color_picker').show();
		});

		$('body').click(function() {
			$('#sticky_menu_text_color_picker').hide();
		});
	}

	/* Display button play */
	$('select[name="videotouch_single_post[button_play]"]').change(function(event) {
		if ( $(this).val() === 'y' ) {
			$('select[name="videotouch_single_post[video_image]"]').closest('tr').removeClass('hidden');
		} else {
			$('select[name="videotouch_single_post[video_image]"]').closest('tr').addClass('hidden');
		}
	});
	if ( $('select[name="videotouch_single_post[button_play]"]').val() === 'y' ) {
		$('select[name="videotouch_single_post[video_image]"]').closest('tr').removeClass('hidden');
	} else {
		$('select[name="videotouch_single_post[video_image]"]').closest('tr').addClass('hidden');
	}

	/* Set JW Player as default video player */
	$('select[name="videotouch_single_post[default_videoplayer]"]').change(function(event) {
		if ( $(this).val() === 'y' ) {
			$('select[name="videotouch_single_post[button_play]"]').closest('tr').addClass('hidden');
		} else {
			$('select[name="videotouch_single_post[button_play]"]').closest('tr').removeClass('hidden');
		}
	});
	if ( $('select[name="videotouch_single_post[default_videoplayer]"]').val() === 'y' ) {
		$('select[name="videotouch_single_post[button_play]"]').closest('tr').addClass('hidden');
	} else {
		$('select[name="videotouch_single_post[button_play]"]').closest('tr').removeClass('hidden');
	}

	/**
	 * Create new sidebar
	 */
	$('#ts_add_sidebar').on('click', function(event) {
		event.preventDefault();
		var sidebar_name = $('#ts_sidebar_name').val();

		var data = {
			action: 'ts_add_sidebar',
			ts_sidebar_name: sidebar_name
		};

		$.post(ajaxurl, data, function(data, textStatus, xhr) {
			if (data.success == 1) {
				var html = '<tr><td class="dynamic-sidebar">'+data.sidebar.name+'</td><td><a href="#" class="ts-remove-sidebar" id="'+data.sidebar.id+'">Delete</a></td></tr>';
				$('#ts_sidebar_name').val('');
				$('#ts-sidebars').append($(html));
			} else {
				alert(data.message);
			}
		}, 'json');
	});

	/**
	 * Removing sidebar
	 */
	$(document).on('click', '.ts-remove-sidebar', function(event) {
		event.preventDefault();
		var sidebar = $(this);
		var sidebar_id = sidebar.attr('id');

		var data = {
			action: 'ts_remove_sidebar',
			ts_sidebar_id: sidebar_id
		};

		$.post(ajaxurl, data, function(data, textStatus, xhr) {
			if (data.result == 1){
				sidebar.parent().parent().remove();
			}
		}, 'json');
	});

	// Show/Hide options for sidebar

	jQuery('#page-sidebar-position').change(function(){
		var position_val = jQuery(this).val();
		if ( position_val != 'none' ) {
			jQuery('#ts_sidebar_size').show();
			jQuery('#ts_sidebar_sidebars').show();
		} else{
			jQuery('#ts_sidebar_size').hide();
			jQuery('#ts_sidebar_sidebars').hide();
		}
		//jQuery('#page-sidebar-position').trigger('change');
	});
	if ( jQuery('#page-sidebar-position').val() != 'none' ) {
		jQuery('#ts_sidebar_size').show();
		jQuery('#ts_sidebar_sidebars').show();
	} else{
		jQuery('#ts_sidebar_size').hide();
		jQuery('#ts_sidebar_sidebars').hide();
	}

    // Options > Layouts > Blog page
    var blogDisplayMode = $('.blog-last-posts-display-mode-options>div'),
    blogDisplayModeFirstElement = $(".blog-last-posts-display-mode").find('option:first').val(),

    // Options > Layouts > Category
    categoryDisplayMode = $('.category-last-posts-display-mode-options>div'),
    categoryDisplayModeFirstElement = $(".category-last-posts-display-mode").find('option:first').val(),

    // Options > Layouts > Author
    authorDisplayMode = $('.author-last-posts-display-mode-options>div'),
    authorDisplayModeFirstElement = $(".author-last-posts-display-mode").find('option:first').val(),

    // Options > Layouts > Search
    searchDisplayMode = $('.search-last-posts-display-mode-options>div'),
    searchDisplayModeFirstElement = $("#search-last-posts-display-mode").find('option:first').val(),

    // Options > Layouts > Archive
    archiveDisplayMode = $('.archive-last-posts-display-mode-options>div'),
    archiveDisplayModeFirstElement = $(".archive-last-posts-display-mode").find('option:first').val(),

    // Options > Layouts > Tags
    tagsDisplayMode = $('.tags-last-posts-display-mode-options>div'),
    tagsDisplayModeFirstElement = $(".tags-last-posts-display-mode").find('option:first').val();

	// Show selected element from builderElements and hide the rest
	function makeSelected (builderElements, selected) {
		$.each(builderElements, function(index, el) {
			var element = $(el);
			if (element.hasClass(selected)) {
				element.removeClass('hidden');
			} else {
				if( ! element.hasClass('hidden')) {
					element.addClass('hidden');
				}
			}
		});
	}

    $(document).on("change", ".blog-last-posts-display-mode", function(event) {
        var selected = 'last-posts-' + $(this).val();
        makeSelected(blogDisplayMode, selected);
    });

    $(document).on("change", ".category-last-posts-display-mode", function(event) {
        var selected = 'last-posts-' + $(this).val();
        makeSelected(categoryDisplayMode, selected);
    });

    $(document).on("change", ".author-last-posts-display-mode", function(event) {
        var selected = 'last-posts-' + $(this).val();
        console.log(selected);
        makeSelected(authorDisplayMode, selected);
    });

    $(document).on("change", ".search-last-posts-display-mode", function(event) {
        var selected = 'last-posts-' + $(this).val();
        makeSelected(searchDisplayMode, selected);
    });

    $(document).on("change", ".archive-last-posts-display-mode", function(event) {
        var selected = 'last-posts-' + $(this).val();
        makeSelected(archiveDisplayMode, selected);
    });

    $(document).on("change", ".tags-last-posts-display-mode", function(event) {
        var selected = 'last-posts-' + $(this).val();
        makeSelected(tagsDisplayMode, selected);
    });

	jQuery('.display-layout-options').click(function(){
		jQuery(this).toggleClass('opened');
		jQuery(this).next().toggleClass('hidden');
	});

    var videotouch_custom_uploader = {};

    $(document).on("click", ".videotouch_select_image", function(e) {
        e.preventDefault();

        if (typeof wp.media.frames.file_frame == 'undefined') {
            wp.media.frames.file_frame = {};
        }

        var _this     = $(this),
            target_id = _this.attr('id'),
            media_id  = _this.closest('div').find('.image_media_id').val();

        //If the uploader object has already been created, reopen the dialog
        if (videotouch_custom_uploader[target_id]) {
            videotouch_custom_uploader[target_id].open();
            return;
        }

        //Extend the wp.media object
        videotouch_custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            library: {
                type: 'image'
            },
            multiple: false,
            selection: [media_id]
        });

        //When a file is selected, grab the URL and set it as the text field's value
        videotouch_custom_uploader[target_id].on('select', function() {

            var attachment = videotouch_custom_uploader[target_id].state().get('selection').first().toJSON();
            var options = _this.closest('div');

            options.find('.image_url').val(attachment.url);
            options.find('.image_media_id').val(attachment.id);

            if (target_id === 'upload-logo') {
				var logo_url = $("#logo-url").val();
				var newImg = new Image();
				newImg.src = logo_url;

				$(newImg).load(function(){
					$('#videotouch_logo_retina_width').val(newImg.width);
					$('#videotouch_logo_retina_height').val(newImg.height);
				});
			}

            return;
        });

        //Open the uploader dialog
        videotouch_custom_uploader[target_id].open();
    });


	$(document).on('click', '.ts-remove-alert', function(event) {
		event.preventDefault();

		var alert = $(this).closest('.updated'),
			token = $(this).attr('data-token'),
			alertID = $(this).attr('data-alets-id'),
			data = {};

		data['action'] = 'videotouch_hide_touchsize_alert';
		data['token'] = token;
		data['alertID'] = alertID;

		alert.fadeOut('slow');

		$.post( ajaxurl, data, function(data, textStatus, xhr) {
			if (data.status === 'ok') {
				alert.remove();
			}
		});
	});
	$(document).on('click', '.uploader-meta-field', function(event) {
        event.preventDefault();
        var this_element_ID = '#' + jQuery(this).attr('data-element-id');
        if (typeof wp.media.frames.file_frame == 'undefined') {
            wp.media.frames.file_frame = {};
        }

        var _this     = $(this),
            target_id = _this.attr('id'),
            media_id  = _this.closest('div').find(this_element_ID + '-media-id').val();

        //If the uploader object has already been created, reopen the dialog
        if (videotouch_custom_uploader[target_id]) {
            videotouch_custom_uploader[target_id].open();
            return;
        }

        //Extend the wp.media object
        videotouch_custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            library: {
                type: 'image'
            },
            multiple: false,
            selection: [media_id]
        });

        //When a file is selected, grab the URL and set it as the text field's value
        videotouch_custom_uploader[target_id].on('select', function() {

            var attachment = videotouch_custom_uploader[target_id].state().get('selection').first().toJSON();
            var options = _this.closest('div');

            options.find(this_element_ID + '-input-field').val(attachment.url);
            options.find(this_element_ID + '-media-id').val(attachment.id);

            return;
        });

        //Open the uploader dialog
        videotouch_custom_uploader[target_id].open();
	});
	jQuery('.image-radio-input').click(function(){
		jQuery(this).parent().parent().find('.selected').removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery(this).parent().parent().find('input[checked="checked"]').removeAttr('checked');
		jQuery(this).parent().parent().find('input[data-value="'+jQuery(this).attr('data-value')+'"]').attr('checked','checked');
	});

	function restartColorPickers(){
		var pickers = jQuery('.colors-section-picker-div');
		jQuery.each(pickers, function( index, value ) {
			jQuery(this).farbtastic(jQuery(this).prev());
		});
	}
    setTimeout(function(){
        custom_selectors_run();
    },200);

    var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");

    //Function to convert rgb format to hex color
    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    function hex(x) {
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }

	// Custom selectors

	function custom_selectors(selector, targetselect){
	    selector_option = jQuery(selector).attr('data-option');
	    jQuery(selector).parent().parent().find('.selected').removeClass('selected');
	    jQuery(targetselect).find('option[selected="selected"]').removeAttr('selected');
	    jQuery(targetselect).find('option[value="' + selector_option + '"]').attr('selected','selected');
	    jQuery(selector).parent().addClass('selected');
	}
	function custom_selectors_run(){
	    jQuery('.ts-custom-selector').each(function(){
	        the_selector = jQuery(this).attr('data-selector');
	        the_selector_value = jQuery(the_selector + ' option[selected="selected"]').attr('value');
	        selected_class = jQuery(this).find('[data-option="' + the_selector_value + '"]').attr('data-option');
	        jQuery(this).find('[data-option="' + selected_class + '"]').parent().addClass('selected');

	        var firstchild = jQuery(this).children('li:first'),
	        	bool = false;

	        if(jQuery(this).children().hasClass('selected') == false){
		    	firstchild.addClass('selected');
		    	bool = true;

		    	if(bool == true){
		    		jQuery('#page-sidebar-position-selector li:first img').trigger('click');
		    	}

		    }else{
		    	return true;
		    }
	    });
	}

	targetselect = jQuery("#page-sidebar-position");
    jQuery("#page-sidebar-position-selector li img").click(function(){
        custom_selectors(jQuery(this), targetselect);
        jQuery('#page-sidebar-position').trigger('change');
    });
    jQuery(document).on('click','.builder-element-icon-trigger-btn', function(event){
    	event.preventDefault();
        jQuery(this).parent().next().slideToggle();
    });
    jQuery(document).on('click','.builder-icon-list li i', function(event){
    	event.preventDefault();
    	var triggerBtn = jQuery(this).parent().parent().prev().find('a');
    	var selectedIcon = jQuery(this).attr('data-option');
        jQuery(triggerBtn).trigger('click');
        jQuery(triggerBtn).find('i').remove();
        jQuery(triggerBtn).append("<i class='" + selectedIcon + "'></i>");
    });

    jQuery(window).load(function(){
		setTimeout(function(){
	        custom_selectors_run();
	    },100);
	});

	jQuery(document).on('click', '.sortable-meta-element', function(event) {
	    event.preventDefault();
	    var arrow = jQuery(this).find('.tab-arrow');
	    if (arrow.hasClass('icon-down')) {
	        arrow.removeClass('icon-down').addClass('icon-up');
	    } else if (arrow.hasClass('icon-up')) {
	        arrow.removeClass('icon-up').addClass('icon-down');
	    }
	    jQuery(this).next().slideToggle();

	    if( jQuery(this).next().hasClass('hidden') ){
	    	jQuery(this).next().removeClass('hidden').addClass('active');
	    }else{
	    	jQuery(this).next().removeClass('active').addClass('hidden');
	    }

		var thisElem = jQuery(this).parent().attr('id');
		var thisContainer = jQuery(this).parent().parent().attr('id');

		jQuery('#'+thisContainer+' > li').not('#'+thisElem).each(function(){
			var allItems = jQuery(this).children('div:not(.sortable-meta-element)');
			jQuery(allItems).slideUp();
		});
	});

	// Remove item
	jQuery(document).on('click', '.remove-item', function(event) {
	    event.preventDefault();
	    jQuery(this).closest('li').remove();
	});

	// Duplicate item
	jQuery(document).on('click', '.ts-multiple-item-duplicate', function(event){
        event.preventDefault();
        var parentContainer = jQuery(this).parent().parent();
        var target_item = jQuery(this).attr('data-item');
        var incerement_item = jQuery(this).attr('data-increment');
        var element_name = jQuery(this).attr('data-element-name');
        incerement_item++;

        dublicate_item_id = jQuery(this).prev().prev().val();
        var new_element_id =  new Date().getTime();
        element_id = new RegExp(dublicate_item_id, 'g');

        if( target_item === 'listed-features-item' ){
        	ts_listed_features_style_color();
        }

        if ( jQuery(parentContainer).find('.colors-section-picker').length > 0 ){
        	jQuery(parentContainer).find('.colors-section-picker').each(function(){
	            jQuery(this).attr('value',rgb2hex(jQuery(this).css('background-color')));
	        });
        }
        if ( jQuery(parentContainer).find('textarea[data-builder-name="text"]').length > 0 ){
	        jQuery(parentContainer).find('textarea[data-builder-name="text"]').each(function(){
	            jQuery(this).text(jQuery(this).val());
	        });
	    }
	    if ( jQuery(parentContainer).find('input[data-builder-name="title"]').length > 0 ){
	        jQuery(parentContainer).find('input[data-builder-name="title"]').each(function(){
	            jQuery(this).attr('value',jQuery(this).val());
	        });
	    }
	    if ( jQuery(parentContainer).find('input[data-role="media-url"]').length > 0 ){
			jQuery(parentContainer).find('input[data-role="media-url"]').each(function(){
			   jQuery(this).attr('value',jQuery(this).val());
			});
		}
		if ( jQuery(parentContainer).find('input[data-builder-name="name"]').length > 0 ){
			jQuery(parentContainer).find('input[data-builder-name="name"]').each(function(){
			   jQuery(this).attr('value',jQuery(this).val());
			});
		}
		if ( jQuery(parentContainer).find('input[data-builder-name="company"]').length > 0 ){
			jQuery(parentContainer).find('input[data-builder-name="company"]').each(function(){
			   jQuery(this).attr('value',jQuery(this).val());
			});
		}
		if ( jQuery(parentContainer).find('input[data-builder-name="url"]').length > 0 ){
			jQuery(parentContainer).find('input[data-builder-name="url"]').each(function(){
			   jQuery(this).attr('value',jQuery(this).val());
			});
		}
		if ( jQuery(parentContainer).find('input[data-builder-name="embed"]').length > 0 ){
			jQuery(parentContainer).find('input[data-builder-name="embed"]').each(function(){
			   jQuery(this).attr('value',jQuery(this).val());
			});
		}

        var element_content = jQuery(parentContainer).html();
        var list_id = Math.round(new Date().getTime() + (Math.random() * 100));

        element_content = element_content.replace(element_id,new_element_id);
        jQuery(parentContainer).parent().append('<li id="list-item-id-'+list_id+'" class="'+target_item+' ts-multiple-add-list-element">' + element_content + '</li>');

        ts_upload_files('#uploader_' + new_element_id, '#slide_media_id-' + new_element_id, '#' + element_name + '-' + new_element_id + '-image', 'Insert image', '#image-preview-' + new_element_id, 'image');

        restartColorPickers();

        jQuery('div.builder-element .builder-icon-list').each(function(){
	        jQuery('div.builder-element .builder-icon-list li i').click(function(){
	            targetselect = jQuery(this).parent().parent().attr('data-selector');
	            custom_selectors(jQuery(this), targetselect);
	        });
	    });
    });

	jQuery('#ts-toggle-layout-builder').click(function(){
		tinymce.execCommand('mceAddEditor', false, 'content');
    });

    jQuery('#ts_editor_default').remove();

	jQuery('#generate-likes').click(function(){

		jQuery('.ts-wait').css('display', '');

		jQuery.post(ajaxurl, "action=ts_generate_like&nonce=" + VideoTouchAdmin.LikeGenerate, function(response){
			if( response === '1' ){
				jQuery('.ts-wait').css('display', 'none');
				jQuery('.ts-succes-like').css('display', '');
			}else{
				jQuery('.ts-wait').css('display', 'none');
				jQuery('.ts-error-like').css('display', '');
			}
		});
	});
});

// Mega menu scripts
(function($)
{
	var ts_is_mega_menu = {
		recalcTimeout: false,
		// bind the click event to all elements with the class ts_uploader
		bind_click: function()
		{
			var megmenuActivator = '.menu-item-ts-megamenu,#menu-to-edit';

				$(document).on('click', megmenuActivator, function()
				{
					var checkbox = $(this),
						container = checkbox.parents('.menu-item:eq(0)');

					if(checkbox.is(':checked'))
					{
						container.addClass('ts_is_mega_active');
					}
					else
					{
						container.removeClass('ts_is_mega_active');
					}

					//check if anything in the dom needs to be changed to reflect the (de)activation of the mega menu
					ts_is_mega_menu.recalc();

				});
		},
		recalcInit: function()
		{
            $(document).on('mouseup', '.menu-item-bar', function(event, ui)
			{
				if(!$(event.target).is('a'))
				{
					clearTimeout(ts_is_mega_menu.recalcTimeout);
					ts_is_mega_menu.recalcTimeout = setTimeout(ts_is_mega_menu.recalc, 500);
				}
			});
		},
		recalc : function()
		{
			var menuItems = $('.menu-item','#menu-to-edit');

			menuItems.each(function(i)
			{
				var item = $(this),
					megaMenuCheckbox = $('.menu-item-ts-megamenu', this);

				if(!item.is('.menu-item-depth-0'))
				{
					var checkItem = menuItems.filter(':eq('+(i-1)+')');
					if(checkItem.is('.ts_is_mega_active'))
					{
						item.addClass('ts_is_mega_active');
						megaMenuCheckbox.attr('checked','checked');
					}
					else
					{
						item.removeClass('ts_is_mega_active');
						megaMenuCheckbox.attr('checked','');
					}
				}
			});
		},

		// Clone the menu-item for creating our megamenu
		addItemToMenu : function(menuItem, processMethod, callback) {
			var menu = $('#menu').val(),
				nonce = $('#menu-settings-column-nonce').val();

			processMethod = processMethod || function(){};
			callback = callback || function(){};

			params = {
				'action': 'ts_ajax_switch_menu_walker',
				'menu': menu,
				'menu-settings-column-nonce': nonce,
				'menu-item': menuItem
			};

			$.post( ajaxurl, params, function(menuMarkup) {
				var ins = $('#menu-instructions');
				processMethod(menuMarkup, params);
				if( ! ins.hasClass('menu-instructions-inactive') && ins.siblings().length )
					ins.addClass('menu-instructions-inactive');
				callback();
			});
		}

};

	$(function()
	{
		ts_is_mega_menu.bind_click();
		ts_is_mega_menu.recalcInit();
		ts_is_mega_menu.recalc();
		if(typeof wpNavMenu != 'undefined'){ wpNavMenu.addItemToMenu = ts_is_mega_menu.addItemToMenu; }
 	});

})(jQuery);

function custom_selectors(selector, targetselect){
    selector_option = jQuery(selector).attr('data-option');
    jQuery(selector).parent().parent().find('.selected').removeClass('selected');
    jQuery(targetselect).find('option[selected="selected"]').removeAttr('selected');
    jQuery(targetselect).find('option[value="' + selector_option + '"]').attr('selected','selected');
    jQuery(selector).parent().addClass('selected');
}

jQuery(document).on('click', '.clickable-element', function(){
    data_selector = jQuery(this).parent().parent().attr('data-selector');
    custom_selectors(jQuery(this), data_selector);
    jQuery(data_selector).trigger('change');
});

jQuery(document).on('click', 'li#icon-text', function(event) {
    event.preventDefault();

	jQuery('#ts-builder-elements-modal').modal('hide');

    jQuery('#ts-builder-elements-editor-preloader').show();

    jQuery('#ts-builder-elements-modal-editor').modal({show:true});
    jQuery('#ts-builder-elements-modal-editor-label').html('Text elements');
    jQuery('#ts-builder-elements-editor-preloader').hide();
});

jQuery('#ts-builder-elements-modal-editor').find('#ts-toggle-layout-builder').remove();

jQuery(document).on('click', '.ts-save-editor', function(event) {
    event.preventDefault();
    jQuery('input#builder-save').trigger('click');
    jQuery('#ts-builder-elements-modal-editor button.close').trigger('click');
});

jQuery('#ts-builder-elements-modal-editor').on('hidden.bs.modal', function () {
	jQuery('#ts-builder-elements-modal').modal('hide')
	if( typeof(tinymce) !== 'undefined' ){
  		tinymce.get('ts_editor_id').setContent('');
  	}
});

jQuery(document).on('focusin', function(e) {
    e.stopImmediatePropagation();
});

jQuery(document).ready( function() {

	//save the configoration in page posts
	jQuery(".featured").click(function() {

		var nonce_featured = VideoTouchAdmin.Nonce;
		var value_checkbox = jQuery(this).val();
		var this_feature = jQuery(this);

		if(jQuery(this).is(":checked")){
			var checked = "yes";
		}else{
			var checked = "no";
		}
		jQuery.ajax({
            url: ajaxurl,
            type : "POST",
            data : "action=ts_updateFeatures&value_checkbox=" + value_checkbox + "&checked=" + checked + '&nonce_featured=' + nonce_featured,
            beforeSend:function(xhr){
            			jQuery('.saved_ajax').remove();
						this_feature.after('<p class="save_ajax">Save...</p>');
			},
			success:function(results){
				jQuery('.save_ajax').remove();
				this_feature.after('<p class="saved_ajax">Saved !</p>');
				object = {
				   func: function() {
				   		jQuery('.saved_ajax').hide(1000);
				   }
				}
				setTimeout( function() { object.func() } , 2000);

			}
		});
	});
});

function ts_upload_image_social(){
	custom_uploader = {};
    if (typeof wp.media.frames.file_frame == 'undefined') {
                wp.media.frames.file_frame = {};
    }

    jQuery('.ts-upload-social-image').click(function(e) {
        e.preventDefault();
        var _this     = jQuery(this),
        target_id = _this.attr('id'),
        media_id  = _this.parent().find('[data-role="media-id"]').val();

        if (custom_uploader[target_id]) {
            custom_uploader[target_id].open();
            return;
        }

        custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
            title: 'Choose Image',
            button: {
              text: 'Choose Image'
            },
            library: {
              type: 'image'
            },
            multiple: false,
            selection: [media_id]
        });

        custom_uploader[target_id].on('select', function() {
            var attachment = custom_uploader[target_id].state().get('selection').first().toJSON();
            var slide = _this.parent().parent();
            slide.find('[data-role="media-url"]').val(attachment.url);
            slide.find('[data-role="media-id"]').val(attachment.id);
            return;
        });

        custom_uploader[target_id].open();
    });
}
ts_upload_image_social();

    // upload files
    function ts_upload_files(id_button, id_input_hidden, input_value_attachment, text_button, id_div_preview, library){

    	var videotouch_custom_uploader = {};

        jQuery(id_button).click(function(e) {
            e.preventDefault();

            if (typeof wp.media.frames.file_frame == 'undefined') {
                wp.media.frames.file_frame = {};
            }

            var _this     = jQuery(this),
                target_id = _this.attr('id'),
                media_id  = _this.closest('td').find(id_input_hidden).val();

            if (videotouch_custom_uploader[target_id]) {
                videotouch_custom_uploader[target_id].open();
                return;
            }

            videotouch_custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
                title: text_button,
                button: {
                    text: text_button
                },
                library: {
                    type: library
                },
                multiple: false,
                selection: [media_id]
            });

            videotouch_custom_uploader[target_id].on('select', function() {

                var attachment = videotouch_custom_uploader[target_id].state().get('selection').first().toJSON();
                var options = _this.closest('div');

                jQuery(input_value_attachment).val(attachment.url);
                jQuery(id_input_hidden).val(attachment.id);

                if ( typeof(id_div_preview) !== 'undefined' ) {

	                if( typeof(jQuery(id_div_preview)) !== 'undefined' ){
						var img = jQuery("<img>").attr('src', attachment.url).attr('style', 'max-width:400px');
                        jQuery(id_div_preview).html(img);

					}
				}

                return;
            });

            videotouch_custom_uploader[target_id].open();

        });
	}

	//headings upload font
	ts_upload_files('#upload_svg','#file_svg','#atachment-svg','Choose font','image');
	ts_upload_files('#upload_eot','#file_eot','#atachment-eot','Choose font','image');
	ts_upload_files('#upload_woff','#file_woff','#atachment-woff','Choose font','image');
	ts_upload_files('#upload_ttf','#file_ttf','#atachment-ttf','Choose font','image');

	//primary upload font
	ts_upload_files('#upload_primary_svg','#file_primary_svg','#atachment-primary-svg','Choose font','image');
	ts_upload_files('#upload_primary_eot','#file_primary_eot','#atachment-primary-eot','Choose font','image');
	ts_upload_files('#upload_primary_woff','#file_primary_woff','#atachment-primary-woff','Choose font','image');
	ts_upload_files('#upload_primary_ttf','#file_primary_ttf','#atachment-primary-ttf','Choose font','image');

	//primary upload font
	ts_upload_files('#upload_secondary_svg','#file_secondary_svg','#atachment-secondary-svg','Choose font','image');
	ts_upload_files('#upload_secondary_eot','#file_secondary_eot','#atachment-secondary-eot','Choose font','image');
	ts_upload_files('#upload_secondary_woff','#file_secondary_woff','#atachment-secondary-woff','Choose font','image');
	ts_upload_files('#upload_secondary_ttf','#file_secondary_ttf','#atachment-secondary-ttf','Choose font','image');

	//upload images in element banner
	ts_upload_files('#select_banner_image','#banner_image_media_id','#image-banner-url','Choose image','#banner_image_preview','image');
	ts_upload_files('#select-custom-type-video','#select-custom_media_id','#custom-type-upload-videos','Upload video','image');

// Add new items in builder-element
jQuery(document).on('click', '.ts-multiple-add-button', function(event) {
    event.preventDefault();
    var element_name = jQuery(this).attr('data-element-name');
    name_block_items++;
    var sufix = new Date().getTime();
    window.tab_sufix = sufix;
    var item_id = new RegExp('{{item-id}}', 'g');
    var item_number = new RegExp('{{slide-number}}', 'g');

    var items = jQuery('#' + element_name + '_items');
    var name_block_items = jQuery('#' + element_name + '_items > li').length;
    var name_blocks_template = '';
    name_blocks_template = jQuery('#' + element_name + '_items_template').html();

    var template = name_blocks_template.replace(item_id, sufix).replace(item_number, name_block_items);
    items.append(template);

    ts_upload_files('#uploader_' + sufix, '#slide_media_id-' + sufix, '#' + element_name + '-' + sufix + '-image', 'Insert image', '#image-preview-' + sufix, 'image');

    jQuery('div.' + element_name + '.builder-element .builder-icon-list').each(function(){
        this_id = '#' + jQuery(this).attr('id') + ' li i';
        jQuery('div.' + element_name + '.builder-element .builder-icon-list li i').click(function(){
            targetselect = jQuery(this).parent().parent().attr('data-selector');
            custom_selectors(jQuery(this), targetselect);
        });
    });

    if( element_name == 'preroll' ){
    	ts_upload_files('#ts_upload-' + sufix, '#ts-hidden-' + sufix, '#ts-video-' + sufix, 'Upload video', '', 'webm');
    }

    if(element_name == 'listed-features'){
        ts_listed_features_style_color();
    }

    restartColorPickers();

   	ts_contact_form_type_options();

    jQuery(this).parent().find('.ui-sortable > li:last div.hidden').removeClass('hidden');

});

//show/hide the input "options" in element contact form depending of option "type"(option "select" show this input and "other option" hide this input)
function ts_contact_form_type_options(){

    jQuery('.contact-form-type').change(function(){

        if( jQuery(this).val() == 'select' ){
            jQuery(this).parent().parent().parent().find('.contact-form-options').css('display','');
        }else{
            jQuery(this).parent().parent().parent().find('.contact-form-options').css('display','none');
        }
    });
    jQuery('.contact-form-type').each(function(){
        if( jQuery(this).val() == 'select' ){
            jQuery(this).parent().parent().parent().find('.contact-form-options').css('display','');
        }else{
            jQuery(this).parent().parent().parent().find('.contact-form-options').css('display','none');
        }
    });
}
ts_contact_form_type_options();

function restartColorPickers(){
  var pickers = jQuery('.colors-section-picker-div');
  jQuery.each(pickers, function( index, value ) {
    jQuery(this).farbtastic(jQuery(this).prev());
  });
}
setTimeout(function(){
    custom_selectors_run();
},200);

function custom_selectors_run(){
    jQuery('.ts-custom-selector').each(function(){
        the_selector = jQuery(this).attr('data-selector');
        the_selector_value = jQuery(the_selector + ' option[selected="selected"]').attr('value');
        selected_class = jQuery(this).find('[data-option="' + the_selector_value + '"]').attr('data-option');
        jQuery(this).find('[data-option="' + selected_class + '"]').parent().addClass('selected');

        var firstchild = jQuery(this).children('li:first'),
            bool = false;

          if(jQuery(this).children().hasClass('selected') == false){
            firstchild.addClass('selected');
            bool = true;

            if(bool == true){
                jQuery(the_selector + ' li .clickable-element:first-child').trigger('click');
            }

        }else{
          return true;
        }
    });
}

//show/hide the button 'Generate likes' in 'GENERAL OPTIONS' depending of option 'Enable likes'
function ts_general_options_button_likes(){
    var option_display_mode = jQuery('.enable-likes');
    jQuery(option_display_mode).change(function(){
        if( jQuery(this).val() === 'n' ){
            jQuery(this).parent().parent().parent().find('.icons-likes').closest('tr').css('display', 'none');
            jQuery(this).parent().parent().parent().find('.generate-likes').closest('tr').css('display', 'none');
        }else{
        	jQuery(this).parent().parent().parent().find('.icons-likes').closest('tr').css('display', '');
            jQuery(this).parent().parent().parent().find('.generate-likes').closest('tr').css('display', '');
        }
    });

    if( jQuery(option_display_mode).val() === 'n' ){
        jQuery(option_display_mode).parent().parent().parent().find('.icons-likes').closest('tr').css('display', 'none');
        jQuery(option_display_mode).parent().parent().parent().find('.generate-likes').closest('tr').css('display', 'none');
    }else{
        jQuery(option_display_mode).parent().parent().parent().find('.icons-likes').closest('tr').css('display', '');
        jQuery(option_display_mode).parent().parent().parent().find('.generate-likes').closest('tr').css('display', '');
    }
}
ts_general_options_button_likes();

function ts_megamenu_category_enable(){

	jQuery('.ts-menu-category-posts').change(function(){

		if( jQuery(this).val() === 'y' ){
			jQuery(this).parent().next().next('.ts_is_mega_menu_options').css('display', 'none');
			jQuery(this).parent().next().next('.ts_is_mega_menu_options').find('.ts-megamenu-category-posts').removeAttr('checked');
			jQuery(this).parent().next('.field-nr-of-columns').css('display', '');
		}else{
			jQuery(this).parent().next().next('.ts_is_mega_menu_options').css('display', '');
			jQuery(this).parent().next('.field-nr-of-columns').css('display', 'none');
		}
	});

	jQuery('.ts-menu-category-posts').each(function(){
		console.log(jQuery(this).val());
		if( jQuery(this).val() === 'y' ){
			jQuery(this).parent().next().next('.ts_is_mega_menu_options').css('display', 'none');
			jQuery(this).parent().next().next('.ts_is_mega_menu_options').find('.ts-megamenu-category-posts').removeAttr('checked');
			jQuery(this).parent().next('.field-nr-of-columns').css('display', '');

		}else{
			jQuery(this).parent().next().next('.ts_is_mega_menu_options').css('display', '');
			jQuery(this).parent().next('.field-nr-of-columns').hide();
		}
	});
}
jQuery(document).ready(function($){
	ts_megamenu_category_enable();
});

jQuery('#save-header-footer').on('click', function() {
    window.builderDataChanged = false;
});

function ts_slider_pips(min, max, step, value, idSlider, idInput){
    jQuery(function() {
        jQuery('#' + idSlider).slider({
            range: "max",
            min: min,
            max: max,
            value: value,
            step: step,
              slide: function(event, ui) {
                jQuery('#' + idInput).val(ui.value);
              }
        });
        jQuery('#' + idInput).val(jQuery('#' + idSlider).slider( "value" ));
    });
}
