// Confirms delete
jQuery(document).ready(function($) {
	$('.confirm').click(function() {
		return confirm("Are you sure you want to delete this post ?");
	});
});