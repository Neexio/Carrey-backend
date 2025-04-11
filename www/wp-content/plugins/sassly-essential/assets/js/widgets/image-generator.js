( function( $ ) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    const Image_Generator = function ($scope, $) {
        const form = $('.search-form', $scope)
        const popup = $('.wcf-image-generator-popup');

        form.submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: SASSLY_ADDONS_JS.ajaxUrl,
                data: {
                    action: 'sassly_get_render_images',
                    data_terms: form.serialize(),
                    post_type: form.attr('post_types'),
                    nonce: SASSLY_ADDONS_JS._wpnonce,
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function () {

                },
                success: function (data) {
                    popup.find('.image-generator-post-wrapper').html(data.html);
                    //need to work for smoother
                },
                complete: function (response) {
                    popup.addClass('search-visible');
                },

                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        });

        popup.on('click', function (e) {
            popup.removeClass('search-visible');
        });

        $("input", $scope).focus(function () {
            form.addClass('wcf-search-form--focus');
        });

        $("input", $scope).focusout(function () {
            form.removeClass('wcf-search-form--focus');
        });

    };

    // Make sure you run this code under Elementor.
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wcf--image-generator.default', Image_Generator );
    } );
} )( jQuery );
