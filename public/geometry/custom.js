(function ($) {

    // Handle navbar close
    function handleNavbarCollapse() {
        if ($(window).innerWidth() <= 991) {
            $('.header-top-search').prependTo('.navbar-collapse');
        }

        if ($(window).innerWidth() < 630) {
            $('.header-top-subscribe').appendTo('.navbar-collapse');
        }
    }

    function responsiveMenuToggle() {
        $('.hamburger-icon').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('close-icon');
            $('body').toggleClass('body-fix');
            $('.header-bottom').toggleClass('nav-open');
            $('.header-search').removeClass('search-open');
        })
    }

    function searchBoxToggle() {
        $('.search-btn').on('click', function(e) {
            e.preventDefault();
            $(this).find('span').hide();
            $('#searchBox').addClass('show-search');
        });
    }


    function initMatchHeights() {
        $('.banner-equal').matchHeight();
        $('.form-equal').matchHeight();
        $('.news-title').matchHeight();
    }

    function eventSliderLeft() {
        $('.events-slider-left').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            autoplay: true,
            asNavFor: '.events-slider-right-content'
        });
    }

    function eventSliderRight() {
        $('.events-slider-right-content').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            asNavFor: '.events-slider-left',
            dots: true,
            autoplay: true,
            focusOnSelect: true,
            arrows: false
        });
    }

    /**
     * Smooth scroll animation to anchor links.
     */
    function handleSmoothScrollAnchorLinks() {
        $(document).on('click', 'a[href^="#"]', function(event) {
            event.preventDefault();
            var elm = $($.attr(this, 'href'));
            if (elm.length) {
                $('html, body').animate({
                    scrollTop: elm.offset().top - 100
                }, 500);
            }
        });
    }

    // Fix
    function fixMeAtTheTop() {
        var elm = $('.page-content-filter');
        if (elm.length) {
            var fixmeTop = elm.offset().top;
            $(window).scroll(function() { // assign scroll event listener
                var currentScroll = $(window).scrollTop(); // get current position
                if (currentScroll >= fixmeTop) { // apply position: fixed if you
                    $('.page-content-filter').css({ // scroll to that element or below it
                        position: 'fixed',
                        top: '0',
                        left: '0',
                        width: '100%'
                    });
                } else { // apply position: static
                    $('.page-content-filter').css({ // if you scroll above it
                        position: 'static'
                    });
                }
            });
        }
    }

    function hideEmptyParagraph() {
        $('p').each(function() {
            var $this = $(this);
            if ($this.html().replace(/\s|&nbsp;/g, '').length == 0)
                $this.remove();
        });
    }

    // Run!
    responsiveMenuToggle();
    searchBoxToggle();
    handleNavbarCollapse();
    initMatchHeights();
    handleSmoothScrollAnchorLinks();
    fixMeAtTheTop();
    eventSliderLeft();
    eventSliderRight();
    hideEmptyParagraph();

    jQuery(window).resize(function() {
        handleNavbarCollapse();
    });
})(jQuery);
