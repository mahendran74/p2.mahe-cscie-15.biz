// Show the first blind
jQuery(document).ready(function($) {
	// ##########################################
	// Accordion box
	// ##########################################

	$('.accordion-container').hide();
	$('.accordion-trigger:first').addClass('active').next().show();
	$('.accordion-trigger').click(function() {
		if ($(this).next().is(':hidden')) {
			$('.accordion-trigger').removeClass('active').next().slideUp();
			$(this).toggleClass('active').next().slideDown();
		}
		return false;
	});
});