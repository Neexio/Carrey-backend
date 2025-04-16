// Accordion widget Extend
elementor.hooks.addAction('panel/open_editor/widget/accordion', function (panel, model, view) {
    var paccordion_elements = panel.$el.find('.elementor-control-type-repeater').find('.elementor-control-wcf_title_icon,.elementor-control-wcf_content_icon');
    panel.$el.find('[data-setting="_skin"]').find(':selected').text() == 'WCF' ? paccordion_elements.show() : paccordion_elements.hide();

    var accordion_title_desc = panel.$el.find('.elementor-control-type-repeater').find('.elementor-control-tab_title .elementor-control-field-description');
    accordion_title_desc.hide();

    var accordion_number = panel.$el.find('.elementor-control-type-repeater').find('.elementor-control-tab_number');
    accordion_number.hide();


    elementor.channels.editor.on('change', (e) => {
        if (e.$el.find('[data-setting="_skin"]').length) {
            var accordion_elements = e.$el.siblings('.elementor-control-type-repeater').find('.elementor-control-wcf_title_icon,.elementor-control-wcf_content_icon');

            e.$el.find('[data-setting="_skin"]').find(':selected').text() == 'WCF' ? accordion_elements.show() : accordion_elements.hide();
            if (e.$el.find('[data-setting="_skin"]').find(':selected').text() == 'WCF Style Two') {
                accordion_elements.hide();
                accordion_number.show();
                accordion_title_desc.show();
            } else {
                accordion_number.hide();
                accordion_title_desc.hide();
            }

        }
    });

});

elementor.hooks.addAction('panel/open_editor/widget/sassly--testimonial', function (panel, model, view) {
    var testimonial_repeater_elements = panel.$el.find('.elementor-control-type-repeater').find('.elementor-control-testimonial_logo,.elementor-control-testimonial_rating');
    panel.$el.find('[data-setting="element_list"]').find(':selected').text() == 'Three' ? testimonial_repeater_elements.show() : testimonial_repeater_elements.hide();

    elementor.channels.editor.on('change', (e) => {
        if (e.$el.find('[data-setting="element_list"]').length) {
            var testi_elements = e.$el.siblings('.elementor-control-type-repeater').find('.elementor-control-testimonial_logo,.elementor-control-testimonial_rating');
            e.$el.find('[data-setting="element_list"]').find(':selected').text() == 'Three' ? testi_elements.show() : testi_elements.hide();
        }
    });
});




