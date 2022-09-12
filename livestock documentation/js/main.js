(function ($) {
"use strict";

	/*----------------------------
		Smoothscroll Active
		------------------------------*/

	$('.main-menu nav ul').onePageNav({
		currentClass: 'active',
		changeHash: true,
		scrollSpeed: 750,
		scrollThreshold: 0.5,
		filter: '',
		easing: 'swing',
	});
	$('html').smoothScroll();

})(jQuery);	