
(function($) {
    "use strict";
	
	/* Back To Top Button */
    // create the back to top button
    $('body').prepend('<a href="#top" class="back-to-top scrolling">Back to Top</a>');
    var amountScrolled = 700;
    $(window).scroll(function() {
        if ($(window).scrollTop() > amountScrolled) {
            $('a.back-to-top').fadeIn('500');
        } else {
            $('a.back-to-top').fadeOut('500');
        }
    });
    
	
	/* jQuery Page Scrolling Feature */
	// requires jQuery easing plugin
		$('a.scrolling').on('click', function(event) {
			var $anchor = $(this);
			$('html, body').stop().animate({
				scrollTop: ($($anchor.attr('href')).offset().top - 50)
			}, 1250, 'easeInOutExpo');
			event.preventDefault();
		});
	// highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 51
    });
    // closes the responsive menu on menu item click
    $('.navbar-collapse ul li a').on('click', function(){ 
            $('.navbar-toggle:visible').click();
	});
    // offset for main navigation
    $('#mainNav').affix({
        offset: {
            top: 100
        }
    })
		
})(jQuery);