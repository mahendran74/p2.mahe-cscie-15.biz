jQuery(document).ready(function() {
	$(".infobox-success").click(function() {
		$(".infobox-success").fadeOut("slow");
	});

	setTimeout(function() {
		$(".infobox-success").fadeOut("slow");
	}, 5000)

});