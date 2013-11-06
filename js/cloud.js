$(document).ready(function() {
	$("#wordcloud1").awesomeCloud({
		"size" : {
			"grid" : 9,
			"factor" : 1
		},
		"options" : {
			"color" : "random-dark",
			"rotationRatio" : 0.35
		},
		"font" : "Helvetica, Arial, sans-serif",
		"shape" : "circle"
	});
});