/* global WCF_ADDONS_JS */
(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    const SasslyDraggableItem = function ($scope, $) {
        $( ".drag--item", $scope ).draggable({
            containment: "parent"
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/sassly--draggable-item.default', SasslyDraggableItem);
    });
})(jQuery);
