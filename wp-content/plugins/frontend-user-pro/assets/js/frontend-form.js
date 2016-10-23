;(function ($) {
    var product_featured_frame;
    var avatar_frame;
    var USER_Form = {
        init: function () {
            // clone and remove repeated field
            $('.user-form').on('click', 'img.user-clone-field', this.cloneField);
            $('.user-form').on('click', 'img.user-remove-field', this.removeField);

            // form submissions
            $('.user-submission-form').on('submit', this.formSubmit);
            $('.user-form-login-form').on('submit', this.formSubmit);
            $('.user-profile-form').on('submit', this.formSubmit);
            $('.user-form-registration-form').on('submit', this.formSubmit);
            $('.user-form-user-contact-form').on('submit', this.formSubmit);

            // featured image
            $('.user-fields').on('click', 'a.user-feat-image-btn', this.featuredImage.addImage);
            $('.user-fields').on('click', 'a.user-remove-feat-image', this.featuredImage.removeImage);

            // featured image
            $('.user-fields').on('click', 'a.user-avatar-image-btn', this.avatarImage.addImage);
            $('.user-fields').on('click', 'a.user-remove-avatar-image', this.avatarImage.removeImage);

            // download links
            $('.user-fields').on('click', 'a.upload_file_button', this.fileDownloadable);

            // Repeatable file inputs
            $('.user-fields').on('click', 'a.insert-file-row', function (e) {
                e.preventDefault();
                var clickedID = $(this).attr('id');
                var max = $('#user-upload-max-files-'+clickedID ).val();
                var optionContainer = $('.user-variations-list-'+clickedID);
                var option = optionContainer.find('.user-single-variation:last');
                var newOption = option.clone();
                delete newOption[1];
                newOption.length = 1;
                var count = optionContainer.find('.user-single-variation').length;

                // too many files 
                if ( count + 1 > max && max != 0 ){
                    return alert(user_form.too_many_files_pt_1 + max + user_form.too_many_files_pt_2);
                }

                newOption.find('input, select, textarea').val('');
                newOption.find('input, select, textarea').each(function () {
                    var name = $(this).attr('name');
                    name = name.replace(/\[(\d+)\]/, '[' + parseInt(count) + ']');
                    $(this)
                        .attr('name', name)
                        .attr('id', name);

                    newOption.insertBefore("#"+clickedID);
                });
                return false;
            });


            $('.user-fields').on('click', 'a.delete', function (e) {
                e.preventDefault();
                var option = $(this).parents('.user-single-variation');
                var optionContainer = $(this).parents('[class^=user-variations-list-]');
                var count = optionContainer.find('.user-single-variation').length;

                if (count == 1) {
                    return alert(user_form.one_option);
                } else {
                    option.remove();
                    return false;
                }
            });
        },

        avatarImage: {

            addImage: function (e) {
                e.preventDefault();

                var self = $(this);

                if (avatar_frame) {
                    avatar_frame.open();
                    return;
                }

                avatar_frame = wp.media({
                    title: user_form.avatar_title,
                    button: {
                        text: user_form.avatar_button,
                    },
                    library: {
                        type: 'image',
                    }
                });

                avatar_frame.on('select', function () {
                    var selection = avatar_frame.state().get('selection');

                    selection.map(function (attachment) {
                        attachment = attachment.toJSON();

                        // set the image hidden id
                        self.siblings('input.user-avatar-image-id').val(attachment.id);

                        // set the image
                        var instruction = self.closest('.instruction-inside');
                        var wrap = instruction.siblings('.image-wrap');

                        // wrap.find('img').attr('src', attachment.sizes.thumbnail.url);
                        wrap.find('img').attr('src', attachment.url);

                        instruction.addClass('user-hide');
                        wrap.removeClass('user-hide');
                    });
                });

                avatar_frame.open();
            },

            removeImage: function (e) {
                e.preventDefault();

                var self = $(this);
                var wrap = self.closest('.image-wrap');
                var instruction = wrap.siblings('.instruction-inside');

                instruction.find('input.user-avatar-image-id').val('0');
                wrap.addClass('user-hide');
                instruction.removeClass('user-hide');
            }
        },

        fileDownloadable: function (e) {
            e.preventDefault();

            var self = $(this),
                downloadable_frame;

            if (downloadable_frame) {
                downloadable_frame.open();
                return;
            }

            downloadable_frame = wp.media({
                title: user_form.file_title,
                button: {
                    text: user_form.file_button,
                },
                multiple: false
            });

            downloadable_frame.on('select', function () {
                var selection = downloadable_frame.state().get('selection');

                selection.map(function (attachment) {
                    attachment = attachment.toJSON();

                    self.closest('tr').find('input.user-file-value').val(attachment.url);
                });
            });

            downloadable_frame.open();
        },


        featuredImage: {

            addImage: function (e) {
                e.preventDefault();

                var self = $(this);

                if (product_featured_frame) {
                    product_featured_frame.open();
                    return;
                }

                product_featured_frame = wp.media({
                    title: user_form.feat_title,
                    button: {
                        text: user_form.feat_button,
                    },
                    library: {
                        type: 'image',
                    }
                });

                product_featured_frame.on('select', function () {
                    var selection = product_featured_frame.state().get('selection');

                    selection.map(function (attachment) {
                        attachment = attachment.toJSON();

                        //console.log(attachment, self);
                        // set the image hidden id
                        self.siblings('input.user-feat-image-id').val(attachment.id);

                        // set the image
                        var instruction = self.closest('.instruction-inside');
                        var wrap = instruction.siblings('.image-wrap');

                        // wrap.find('img').attr('src', attachment.sizes.thumbnail.url);
                        wrap.find('img').attr('src', attachment.url);

                        instruction.addClass('user-hide');
                        wrap.removeClass('user-hide');
                    });
                });

                product_featured_frame.open();
            },

            removeImage: function (e) {
                e.preventDefault();

                var self = $(this);
                var wrap = self.closest('.image-wrap');
                var instruction = wrap.siblings('.instruction-inside');

                instruction.find('input.user-feat-image-id').val('0');
                wrap.addClass('user-hide');
                instruction.removeClass('user-hide');
            }
        },

        cloneField: function (e) {
            e.preventDefault();

            var $div = $(this).closest('tr');
            var $clone = $div.clone();

            //clear the inputs
            $clone.find('input').val('');
            $clone.find(':checked').attr('checked', '');
            $div.after($clone);
        },

        removeField: function () {
            //check if it's the only item
            var $parent = $(this).closest('tr');
            var items = $parent.siblings().andSelf().length;

            if (items > 1) {
                $parent.remove();
            }
        },

        formSubmit: function (e) {
            e.preventDefault();

            var form = $(this),
                form_error_field = form.find('.user-form-error')
                submitButton = form.find('input[type=submit]')
                form_data = USER_Form.validateForm(form);

                alert(form_error_field+"*****"+form_data);
            if (form_error_field.length) {
                form_error_field.hide();
            }

            if (form_data) {
                // send the request
                form.find('fieldset.user-submit').append('<span class="user-loading"></span>');
                submitButton.attr('disabled', 'disabled').addClass('button-primary-disabled');
                $.post(user_form.ajaxurl, form_data, function (res) {
                    //var res = $.parseJSON(res);
                    if ( window.console && window.console.log ) {
                        console.log( res );
                    }
                    if (res.success) {
                        form.before('<div class="user-success">' + res.message + '</div>');
                        if (res.is_post) {
                            form.slideUp('fast', function () {
                                form.remove();
                            });
                        }

                        //focus
                        $('html, body').animate({
                            scrollTop: $('.user-success').offset().top - 100
                        }, 'fast');

                        setTimeout(
                            function () {
                                window.location = res.redirect_to;
                            }, 1000);
                    } else {
                        if ( form_error_field.length ) {
                            form_error_field.text(res.error);
                            form_error_field.show();
                        } else {
                            alert(res.error);
                        }
                        submitButton.removeAttr('disabled');
                    }

                    submitButton.removeClass('button-primary-disabled');
                    form.find('span.user-loading').remove();
                });
            }
        },

        validateForm: function (self) {

            var temp,
                temp_val = '',
                error = false,
                error_items = [];

            USER_Form.removeErrors(self);
            USER_Form.removeErrorNotice(self);

            var required = self.find('[data-required="yes"]');

            required.each(function (i, item) {
                var data_type = $(item).data('type')
                val = '';

                switch (data_type) {
                case 'rich':
                    var name = $(item).data('id')
                    val = $.trim(tinyMCE.get(name).getContent());

                    if (val === '') {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'textarea':
                case 'text':
                    val = $.trim($(item).val());

                    if (val === '') {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'select':
                    val = $(item).val();
                    if (!val || val === '-1') {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'multiselect':
                    val = $(item).val();

                    if (val === null || val.length === 0) {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'tax-checkbox':
                    var length = $(item).children().find('input:checked').length;

                    if (!length) {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'radio':
                    var length = $(item).parent().find('input:checked').length;

                    if (!length) {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'image':
                    var length = $(item).next().val();
                    if (length === null || length === 0 || length === '' || length === "0" ) {
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;

                case 'file':
                    var length = $(item).next('input.user-file-value').val();
                    if (length === null || length === 0 || length === '' ) {
                        error = true;
                        USER_Form.markError(item);
                    }
                    else{
                        if (!USER_Form.isValidURL(length)) {
                            error = true;
                            USER_Form.markError(item);
                        }

                    }
                    break;

                case 'email':
                    var val = $(item).val();

                    if (val !== '') {
                        if (!USER_Form.isValidEmail(val)) {
                            error = true;
                            USER_Form.markError(item);
                        }
                    }
                    break;


                case 'url':
                    var val = $(item).val();

                    if (val !== '') {
                        if (!USER_Form.isValidURL(val)) {
                            error = true;
                            USER_Form.markError(item);
                        }
                    }
                    break;

                 case 'checkbox':
                        var length = $(item).parent().find('input:checked').length;

                        if ( ! length ) {
                            error = true;
                            USER_Form.markError(item);
                        }
                        break;     

                case 'multiple':
                    var file = $(item).closest('.user-single-variation').find('input.user-file-value').val();
                    var price = $(item).closest('.user-single-variation').find('input.user-price-value').val();
                    var name = $(item).closest('.user-single-variation').find('input.user-name-value').val();
                    
                    var file_exists = false;
                    if($('#user-file-row-js').length){
                        file_exists = true;
                    }


                    var price_exists = false;
                    if($('#user-price-row-js').length){
                        price_exists = true;
                    }


                    var name_exists = false;
                    if($('#user-name-row-js').length){
                        name_exists = true;
                    }

                    // file
                    if ( file_exists && ( file === '' || !USER_Form.isValidURL(file) ) ) {
                        error = true;
                        USER_Form.markError(item);
                    }

                    // price
                    if ( price_exists && price === '' ){
                        error = true;
                        USER_Form.markError(item);
                    }
                   
                    // name
                    if ( name_exists && name === ''  ){
                        error = true;
                        USER_Form.markError(item);
                    }
                    break;
                };

            });

            // if error found, bail out
            if (error) {
                // add error notice
                USER_Form.addErrorNotice(self);
                return false;
            }

            var form_data = self.serialize(),
                rich_texts = [];

            // grab rich texts from tinyMCE
            $('.user-rich-validation').each(function (index, item) {
                temp = $(item).data('id');
                val = $.trim(tinyMCE.get(temp).getContent());

                rich_texts.push(temp + '=' + encodeURIComponent(val));
            });

            // append them to the form var
            form_data = form_data + '&' + rich_texts.join('&');
            return form_data;
        },

        addErrorNotice: function (form) {
            $(form).find('fieldset.user-submit').append('<div id="user-error-div" class="user-error frontend_errors">' + user_form.error_message + '</div>');
        },

        removeErrorNotice: function (form) {
            $(form).find('#user-error-div').remove();
        },

        markError: function (item) {
            $(item).closest('fieldset').addClass('has-error');
            $(item).focus();
        },

        removeErrors: function (item) {
            $(item).find('.has-error').removeClass('has-error');
        },

        isValidEmail: function (email) {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(email);
        },

        isValidURL: function (url) {
            var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|files.|ftp:\/\/www.|www.|http:\/\/|https:\/\/){1}([0-9A-Za-z]+\.)|.+\.(?:jpg|gif|png|tiff)");
            return urlregex.test(url);
        },
    };

    $(function () {
        USER_Form.init();
    });

})(jQuery);
