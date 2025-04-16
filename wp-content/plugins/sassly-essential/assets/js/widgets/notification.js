/* global WCF_ADDONS_JS */
(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    const SasslyNotification = function ($scope, $) {
        const close = $('.sassly-notification .close-icon', $scope);
        const notify = $('.sassly-notification', $scope);

        close.on('click', function () {
            notify.hide();
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/sassly--notification.default', SasslyNotification);
    });
})(jQuery);
