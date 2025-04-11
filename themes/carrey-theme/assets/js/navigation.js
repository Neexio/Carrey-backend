jQuery(document).ready(function($) {
    // Mobile menu toggle
    $('.mobile-menu-toggle').click(function() {
        $(this).toggleClass('active');
        $('.main-navigation').toggleClass('active');
        $('body').toggleClass('menu-open');
    });

    // Close menu when clicking outside
    $(document).click(function(event) {
        if (!$(event.target).closest('.main-navigation, .mobile-menu-toggle').length) {
            $('.mobile-menu-toggle').removeClass('active');
            $('.main-navigation').removeClass('active');
            $('body').removeClass('menu-open');
        }
    });

    // Add dropdown functionality for submenus
    $('.menu-item-has-children > a').after('<span class="submenu-toggle"></span>');
    
    $('.submenu-toggle').click(function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).siblings('.sub-menu').slideToggle();
    });

    // FAQ functionality
    $('.faq-question').click(function() {
        const $faqItem = $(this).parent();
        const $answer = $faqItem.find('.faq-answer');
        
        if ($faqItem.hasClass('active')) {
            $answer.slideUp();
            $faqItem.removeClass('active');
        } else {
            $('.faq-item.active .faq-answer').slideUp();
            $('.faq-item.active').removeClass('active');
            $answer.slideDown();
            $faqItem.addClass('active');
        }
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
}); 