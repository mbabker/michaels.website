window.$ = window.jQuery = require('jquery');

// Import Bootstrap components individually
require('bootstrap-sass/assets/javascripts/bootstrap/transition');
require('bootstrap-sass/assets/javascripts/bootstrap/collapse');

// Navigation Scripts to Show Header on Scroll-Up
window.jQuery(document).ready(function ($) {
    const MQL = 1170;

    //primary navigation slide-in effect
    if ($(window).width() > MQL) {
        let navbar = $('.navbar-custom'),
            headerHeight = navbar.height();

        $(window).on(
            'scroll',
            {previousTop: 0},
            function () {
                let currentTop = $(window).scrollTop();

                // Check if user is scrolling up
                if (currentTop < this.previousTop) {
                    // If scrolling up...
                    if (currentTop > 0 && navbar.hasClass('is-fixed')) {
                        navbar.addClass('is-visible');
                    } else {
                        navbar.removeClass('is-visible is-fixed');
                    }
                } else if (currentTop > this.previousTop) {
                    // If scrolling down...
                    navbar.removeClass('is-visible');

                    if (currentTop > headerHeight && !navbar.hasClass('is-fixed')) {
                        navbar.addClass('is-fixed');
                    }
                }
                this.previousTop = currentTop;
            }
        );
    }
});
