;(function($) {

    var $formEditor = $('ul#user-form-editor');

    var Editor = {
        init: function() {

            // make it sortable
            this.makeSortable();

            this.tooltip();
            this.tabber();
            this.showHideHelp();

            // on save validation
            $('form#post').submit(function(e) {
                var errors = false;
                $('li.custom-field input[data-type="label"]').each( function(index) {
                    if ($(this).val().length === 0) {
                        errors = true;
                        $(this).css('border', '1px solid #993333');
                    }
                });

                $('li.custom-field.custom_repeater input[type="text"]').each( function(index) {
                    if ( $(this).attr( 'name' ).indexOf("name") >= 0 && $(this).val().length === 0) {
                        errors = true;
                        $(this).css('border', '1px solid #993333');
                    }
                });

                if (errors) {
                    e.preventDefault();
                    alert( 'Please fix the errors to save the form.' );
                }

            });

            // collapse all
            $('button.user-collapse').on('click', this.collapseEditFields);

            // add field click
            $('.user-form-buttons').on('click', 'button', this.addNewField);

            // remove form field
            $('ul#user-form-editor').on('click', '.user-remove', this.removeFormField);

            // on change event: meta key
            $('ul#user-form-editor').on('blur', 'li.custom-field input[data-type="label"]', this.setMetaKey);

            // on change event: checkbox|radio fields
            $('ul#user-form-editor').on('change', '.user-form-sub-fields input[type=text]', function() {
                $(this).prev('input[type=checkbox], input[type=radio]').val($(this).val());
            });

            // on change event: checkbox|radio fields
            $('ul#user-form-editor').on('click', 'input[type=checkbox].multicolumn', function() {
                // $(this).prev('input[type=checkbox], input[type=radio]').val($(this).val());
                var $self = $(this),
                    $parent = $self.closest('.user-form-rows');

                if ($self.is(':checked')) {
                    $parent.next().hide().next().hide();
                    $parent.siblings('.column-names').show();
                } else {
                    $parent.next().show().next().show();
                    $parent.siblings('.column-names').hide();
                }
            });

            // on change event: checkbox|radio fields
            $('ul#user-form-editor').on('click', 'input[type=checkbox].retype-pass', function() {
                // $(this).prev('input[type=checkbox], input[type=radio]').val($(this).val());
                var $self = $(this),
                    $parent = $self.closest('.user-form-rows');

                if ($self.is(':checked')) {
                    $parent.next().show().next().show();
                } else {
                    $parent.next().hide().next().hide();
                }
            });

            // toggle form field
            $('ul#user-form-editor').on('click', '.user-toggle', this.toggleFormField);

            // clone and remove repeated field
            $('ul#user-form-editor').on('click', 'img.user-clone-field', this.cloneField);
            $('ul#user-form-editor').on('click', 'img.user-remove-field', this.removeField);
            
        },

        showHideHelp: function() {
            var childs = $('ul#user-form-editor').children('li');

            if ( !childs.length) {
                $('.user-updated').show();
            } else {
                $('.user-updated').hide();
            }
        },

        makeSortable: function() {
            $formEditor = $('ul#user-form-editor');

            if ($formEditor) {
                $formEditor.sortable({
                    placeholder: "ui-state-highlight",
                    handle: '> .user-legend',
                    distance: 5
                });
            }
        },

        addNewField: function(e) {
            e.preventDefault();
            var $self = $(this),
                $formEditor = $('ul#user-form-editor'),
                name = $self.data('name'),
                type = $self.data('type'),
                data = {
                    name: name,
                    type: type,
                    order: $formEditor.find('li').length,
                    action: 'user-form_add_el'
                };

            // console.log($self, data);

            // check if these are already inserted
            var oneInstance = ['user_login', 'first_name', 'last_name', 'nickname', 'display_name', 'user_email', 'user_url',
                'user_bio', 'password', 'user_avatar', 'post_title', 'post_content', 'featured_image', 'download_category',
				'download_tag','multiple_pricing','post_excerpt','frontendc_user_paypal', 'frontend_ap'];

            if ($.inArray(name, oneInstance) >= 0) {
                if( $formEditor.find('li.' + name).length ) {
                    alert('You already have this field in the form');
                    return false;
                }
            }

            var buttonText = $self.text();
            $self.html('<div class="user-loading"></div>');
            $self.attr('disabled', 'disabled');
            $('.user-form-buttons .user-button').attr('disabled', 'disabled');
            $.ajax({
                   type:"POST", 
                   url: ajaxurl, 
                   data:data, 
                   success:function(res) {
                    $formEditor.append(res);

                    // re-call sortable
                    Editor.makeSortable();

                    // enable tooltip
                    Editor.tooltip();

                    $self.removeAttr('disabled');
                    $('.user-form-buttons .user-button').removeAttr('disabled');
                    $self.text(buttonText);
                    Editor.showHideHelp();
                }
            });
        },

        removeFormField: function(e) {
            e.preventDefault();

            if (confirm('Are you sure?')) {

                $(this).closest('li').fadeOut(function() {
                    var vl = $(this).find('input[name="field_id_input"]').val();
                    $('.user-form-editor li .all_fields option[value="'+vl+'"]').remove();
                    $(this).remove();
                    Editor.showHideHelp();
                });
            }
        },

        toggleFormField: function(e) {
            e.preventDefault();

            $(this).closest('li').find('.user-form-holder').slideToggle('fast');
        },

        cloneField: function(e) {
            e.preventDefault();

            var $div = $(this).closest('div');
            var index = $(".user-form-rows.user-hide > div").length;
            var $clone = $div.clone();
            if($clone.hasClass('append_div')) {
                $clone.removeClass().addClass('append_div two_'+index);
            }
                      
            //clear the inputs
            $clone.find('input').val('');
            $clone.find(':checked').attr('checked', '');
            $div.after($clone);
        },

        removeField: function() {
            //check if it's the only item
            var $parent = $(this).closest('div');
            var items = $parent.siblings().andSelf().length;

            if( items > 1 ) {
                var vl = $parent.find('input[type="text"]').val();
                $('.user-form-editor li .all_fields2 option[value="'+vl+'"]').remove();
                $parent.remove();
            }
        },

        setMetaKey: function() {
            var $self = $(this),
                val = $self.val().toLowerCase().split(' ').join('_').split('\'').join(''),
                $metaKey = $(this).closest('.user-form-rows').next().find('input[type=text]');

            if ($metaKey.length) {
                $metaKey.val(val);
            }
        },

        tooltip: function() {
            $('.smallipopInput').smallipop({
                preferredPosition: 'right',
                theme: 'black',
                popupOffset: 0,
                triggerOnClick: true
            });
        },

        collapseEditFields: function(e) {
            e.preventDefault();

            $('ul#user-form-editor').children('li').find('.user-form-holder').slideToggle();
        },

        tabber: function() {
            // Switches option sections
            $('.group').hide();
            $('.group:first').fadeIn();

            $('.group .collapsed').each(function(){
                $(this).find('input:checked').parent().parent().parent().nextAll().each(
                function(){
                    if ($(this).hasClass('last')) {
                        $(this).removeClass('hidden');
                        return false;
                    }
                    $(this).filter('.hidden').removeClass('hidden');
                });
            });

            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');

            $('.nav-tab-wrapper a').click(function(evt) {
                var clicked_group = $(this).attr('href');
                if ( clicked_group.indexOf( '#' ) >= 0 ) {
                    evt.preventDefault();
                    $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active').blur();
                    $('.group').hide();
                    $(clicked_group).fadeIn();
                }
            });
        }
    };

    // on DOM ready
    $(function() {
        Editor.init();
    });
    $(document).on('click','.all_fields2', function(){
      $(this).find('option').hide();
      var vl  = $(this).closest('.append_div').find('.all_fields').val();
      var vl1 = vl.split('-');
      $(this).find('.ddc_'+vl1[1]).show();
    });
    $(document).on('focus','.all_fields', function(){
      $(this).find('option:not(.initial_opt_val)').hide();
      var n  = $(this).closest('li').index();
      for (var i = 1; i <= n; i++) {
          var vl = $('#user-form-editor li:nth-child('+i+') input[name="field_id_input"]').val();
          $(this).find('option[value="'+vl+'"]').show();
      };
    });
    $(document).on('click','#user-form-editor li .user-form-holder input.conditional_logic', function(){
        var vl = $(this).val();
        var n  = $('#user-form-editor li').length;
        var count = 0;
        for (var i = 0; i < n; i++) {
            var j  = i+1;
            var ck = $('#user-form-editor li:nth-child('+j+') input[name="user_input['+i+'][enable_login]"]').is(':checked');
            if( ck ) {
                count++; 
            } 
        };
        if(count == n) {
            alert('altleast one Enable Conditional Logic unchecked');
            $(this).prop('checked',false);
            $(this).closest('.enable_conditional_input_check').next().hide();
        }
    });
    $(document).on('change', 'input[name="make_field_fullwidth"]', function(){
        if($(this).attr("checked")) {
            $(this).closest('li').addClass('full_width_r')
            $(this).closest('li').find('input.r_full_width').val('full_width_r');
        }
        else {
            $(this).closest('li').removeClass('full_width_r')
            $(this).closest('li').find('input.r_full_width').val('0');   
        }
    });
})(jQuery);