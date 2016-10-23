(function() {

    tinymce.PluginManager.add('ts_pushortcodes', function( editor )
    {

        editor.addButton('ts_pushortcodes', {
            type: 'menubutton',
            icon: 'icon ts_shortcode_icon',
            menu: [
                {
                    text: 'Icon',
                    value: 'icon',
                    onclick: function() {
                       ts_get_modal('icon'); 
                    }
                },
                {
                    text: 'Button',
                    value: 'button',
                    onclick: function() {
                       ts_get_modal('button'); 
                    }
                },
                {
                    text: 'Image Carousel',
                    value: 'image_carousel',
                    onclick: function() {
                       ts_get_modal('image_carousel'); 
                    }
                },
                {
                    text: 'Gallery',
                    value: 'image_gallery',
                    onclick: function() {
                       ts_get_modal('image_gallery'); 
                    }
                },
                {
                    text: 'Toggle',
                    value: 'toggle',
                    onclick: function() {
                       ts_get_modal('toggle'); 
                    }
                },
                {
                    text: 'Tabs',
                    value: 'tab',
                    onclick: function() {
                       ts_get_modal('tab'); 
                    }
                },
                {
                    text: 'Alert',
                    value: 'tab',
                    onclick: function() {
                       ts_get_modal('alert'); 
                    }
                },
                {
                    text: 'Chart',
                    value: 'chart',
                    onclick: function() {
                       ts_get_modal('chart'); 
                    }
                },
                {
                    text: 'Columns',
                    value: '',
                    onclick: function() {
                        
                    },
                
                    menu: [
                        {
                            text: '1/2 + 1/2',
                            value: 'ts_one_half',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[ts_row]\
                                    [ts_one_half]\
                                        Add Content here\
                                    [/ts_one_half]\
                                    [ts_one_half]\
                                        Add Content here\
                                    [/ts_one_half]\
                                [/ts_row]'); 
                            }       
                        },
                        {
                            text: '1/3 + 1/3 + 1/3',
                            value: 'ts_one_third',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[ts_row]\
                                    [ts_one_third]\
                                        Add Content here\
                                    [/ts_one_third]\
                                    [ts_one_third]\
                                        Add Content here\
                                    [/ts_one_third]\
                                    [ts_one_third]\
                                        Add Content here\
                                    [/ts_one_third]\
                                [/ts_row]' );
                            }       
                        },
                        {
                            text: '2/3 + 1/3',
                            value: 'ts_two_third',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[ts_row]\
                                    [ts_two_third]\
                                        Add 2/3 Content here\
                                    [/ts_two_third]\
                                    [ts_one_third]\
                                        Add 1/3 Content here\
                                    [/ts_one_third]\
                                [/ts_row]');
                            }       
                        },
                        {
                            text: '1/4 + 1/4 + 1/4 + 1/4',
                            value: 'ts_one_fourth',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[ts_row]\
                                    [ts_one_fourth]\
                                        Add Content here\
                                    [/ts_one_fourth]\
                                    [ts_one_fourth]\
                                        Add Content here\
                                    [/ts_one_fourth]\
                                    [ts_one_fourth]\
                                        Add Content here\
                                    [/ts_one_fourth]\
                                    [ts_one_fourth]\
                                        Add Content here\
                                    [/ts_one_fourth]\
                                [/ts_row]' );  
                            }       
                        },
                    ]
                },
                {
                    text: 'List',
                    value: '',
                    onclick: function() {
                        
                    },
                
                    menu: [
                        {
                            text: 'Star',
                            value: 'star',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[star]\
                                    <ul><li>Add Content here</li><li>Add Content here</li></ul>\
                                [/star]'); 
                            }       
                        },
                        {
                            text: 'Arrow',
                            value: 'arrow',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[arrow]\
                                    <ul><li>Add Content here</li><li>Add Content here</li></ul>\
                                [/arrow]' );
                            }       
                        },
                        {
                            text: 'Thumb',
                            value: 'thumb',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[thumb]\
                                    <ul><li>Add Content here</li><li>Add Content here</li></ul>\
                                [/thumb]');
                            }       
                        },
                        {
                            text: 'Question',
                            value: 'question',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[question]\
                                    <ul><li>Add Content here</li><li>Add Content here</li></ul>\
                                [/question]' );  
                            }       
                        },
                        {
                            text: 'Direction',
                            value: 'direction',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[direction]\
                                   <ul><li>Add Content here</li><li>Add Content here</li></ul>\
                                [/direction]' );  
                            }       
                        },
                        {
                            text: 'Tick',
                            value: 'tick',
                            onclick: function(e) {
                               tinyMCE.activeEditor.selection.setContent(
                                '[tick]\
                                    <ul><li>Add Content here</li><li>Add Content here</li></ul>\
                                [/tick]' );  
                            }       
                        }
                    ]
                }
            ]   
        });

        var array_elements = {
                "icon"           : ["size", "icon", "display"],
                "button"         : ["icon","text", "url", "target", "size", "text_color", "bg_color", "button_align", "mode_display", "border_color"],
                "image_carousel" : ["images"],
                "image_gallery"  : ["images"],
                "toggle"         : ["title", "state"],
                "tab"            : [],
                "alert"          : ['icon', 'title', 'background_color', 'text_color'],
                "chart"          : ['pie_content', 'line_content', 'mode', 'title', 'label', 'scaleShowGridLines', 'scaleGridLineColor', 'scaleGridLineWidth', 'scaleShowHorizontalLines', 'scaleShowVerticalLines', 'bezierCurve', 'bezierCurveTension', 'pointDot', 'pointDotRadius', 'pointDotStrokeWidth', 'pointHitDetectionRadius', 'datasetStroke', 'datasetStrokeWidth', 'datasetFill', 'segmentShowStroke', 'segmentStrokeColor', 'segmentStrokeWidth', 'percentageInnerCutout', 'animationSteps', 'animateRotate', 'animateScale']
        };

        function ts_insert_shortcode(){
            jQuery('input#shortcode-save').click(function(event) {
                event.preventDefault();
                if( name_element = jQuery('div[data-name-element]').attr('data-name-element') ){
                    for(name in array_elements){
                        if( name === name_element ){
                           ts_get_options_shortcode(name_element, array_elements[name]);
                        }
                    }
                }
                jQuery('#ts-shortcode-elements-modal button.close').trigger('click');
            });
        }

        function ts_get_options_shortcode(name_element, array_options){

            var option_value = '';
            var insert_into_editor = '';
            tinyMCE.execCommand("mceRepaint");
            var element_html = jQuery('.shortcode-' + name_element);

            if( name_element == 'image_carousel'){

                option_value = jQuery('#carousel_image_gallery').val();
                 
                if( typeof(option_value) !== 'undefined' ){

                    insert_into_editor = insert_into_editor + ' ' + 'images=' + '"' + option_value + '" '; 
                    window.tinymce.execCommand('mceInsertContent', true,  '[' + name_element + insert_into_editor + '][/' + name_element + ']' );
                }
            }else if( name_element == 'image_gallery'){

                option_value = jQuery('#gallery_image_gallery').val();
                 
                if( typeof(option_value) !== 'undefined' ){

                    insert_into_editor = insert_into_editor + ' ' + 'images=' + '"' + option_value + '" '; 
                    window.tinymce.execCommand('mceInsertContent', true,  '[' + name_element + insert_into_editor + '][/' + name_element + ']' );
                }
            }else if( name_element == 'tab' ){
                var comma = '';

                var i = 0;
                jQuery(element_html).find('input[data-builder-name="item_id"]').each(function(){

                    var id_element = jQuery(this).val();
                    if ( i + 1 < jQuery('input[data-builder-name="item_id"]').length ) { var comma = ','}else{var comma = ''};

                    option_title = jQuery(element_html).find('#shortcode-tab-' + id_element + '-title').val();
                    option_text = jQuery(element_html).find('#shortcode-tab-' + id_element + '-text').val();
                    insert_into_editor = insert_into_editor + '[ts_tab id="' + i + '" title="' + option_title.replace(/"/g, "'") + '"]' + option_text + '[/ts_tab]';
                    i++; 
                });

                window.tinymce.execCommand('mceInsertContent', true,  '[ts_tabs]' + insert_into_editor + '[/ts_tabs]' );

            }else if( name_element == 'chart' ){
                var optionNotJson = '',
                    modeChart = '';

                jQuery(array_options).each(function(id, value){

                    option_value = jQuery('#shortcode-' + name_element + '-' + value).val();

                    if( typeof(option_value) !== 'undefined' ){
                        optionNotJson = optionNotJson + ' ' + value + '=' + '"' + option_value + '" '; 
                    }
                    if( value == 'mode' ) modeChart = option_value;

                });
                
                var i = 0;

                jQuery(element_html).find('input[data-option-name="item_id"]').each(function(){

                    var id_element = jQuery(this).val();
                    
                    option_title = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-title').val();
                    option_fillColor = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-fillColor').val();
                    option_strokeColor = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-strokeColor').val();
                    option_pointColor = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-pointColor').val();
                    option_pointStrokeColor = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-pointStrokeColor').val();
                    option_pointHighlightFill = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-pointHighlightFill').val();
                    option_pointHighlightStroke = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-pointHighlightStroke').val();
                    option_data = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-data').val();
                    option_value = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-value').val();
                    option_color = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-color').val();
                    option_highlight = jQuery(element_html).find('#shortcode-chart_' + modeChart + '-' + id_element + '-highlight').val();
                    
                    
                    insert_into_editor = insert_into_editor + '[ts_chart id="' + i + '" title="' + option_title + '" fillColor="' + option_fillColor + '" strokeColor="' + option_strokeColor + '" pointColor="' + option_pointColor + '" pointStrokeColor="' + option_pointStrokeColor + '" pointHighlightFill="' + option_pointHighlightFill + '" pointHighlightStroke="' + option_pointHighlightStroke + '" data="' + option_data + '" value="' + option_value + '" color="' + option_color + '" highlight="' + option_highlight + '"][/ts_chart]';
                    i++; 
                });

                window.tinymce.execCommand('mceInsertContent', true,  '[ts_charts ' + optionNotJson + ']' + insert_into_editor + '[/ts_charts]' );

            }else{

                jQuery(array_options).each(function(id, value){
                    
                    if( value == 'icon' ){
                        option_value = jQuery('#builder-element-icon').val();
                        
                        if( typeof(option_value) == 'undefined' || option_value == null ){
                            option_value = 'noicon';
                        }
                        
                    }else{
                        option_value = jQuery('#shortcode-' + name_element + '-' + value).val();
                    }

                    if( typeof(option_value) !== 'undefined' ){
                        insert_into_editor = insert_into_editor + ' ' + value + '=' + '"' + option_value + '" '; 
                    }
                    
                });

                if( name_element == 'toggle' || name_element == 'alert' ){
                    window.tinymce.execCommand('mceInsertContent', true,  '[' + name_element + insert_into_editor + ']Add your description here[/' + name_element + ']' );
                }else{
                    window.tinymce.execCommand('mceInsertContent', true,  '[' + name_element + insert_into_editor + '][/' + name_element + ']' );
                }
            }
        }

        function ts_get_modal(name_element){
            jQuery("#ts-shortcode-elements-modal-preloader").show();
            jQuery("#ts-shortcode-elements-modal .modal-body").load(ajaxurl + "?action=" + name_element + "&height=800&width=835&modal=true",function(result){

                if( result == 0 ){
                    return;
                }else{
                    jQuery("#ts-shortcode-elements-modal").modal({show:true});
                    jQuery("#ts-shortcode-elements-modal-label").html("Insert shortcode");
                    jQuery("#ts-shortcode-elements-modal-preloader").hide();
                    ts_insert_shortcode();
                    ts_restart_color_picker();
                }
            });
        }
    });

    function ts_restart_color_picker(){

        jQuery('.colors-section-picker-div').each(function(){
            jQuery(this).farbtastic(jQuery(this).prev());
        });

        jQuery('.colors-section-picker-div').hide();

        jQuery(".colors-section-picker").click(function(e){
            e.stopPropagation();
            jQuery(jQuery(this).next()).show();
        });
        
        jQuery('body').click(function() {
            jQuery('.colors-section-picker-div').hide();
        });
    }
})();