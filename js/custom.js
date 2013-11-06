// Jquery with no conflict
jQuery(document).ready(function($) {

	// ##########################################
	// Tool tips
	// ##########################################

	$('.poshytip').poshytip({
		className : 'tip-yellow',
		showTimeout : 1,
		alignTo : 'target',
		alignX : 'center',
		offsetY : 5,
		allowTipHover : false
	});

	$('.form-poshytip').poshytip({
		className : 'tip-yellow',
		showOn : 'focus',
		alignTo : 'target',
		alignX : 'right',
		alignY : 'center',
		offsetX : 5
	});

	// ##########################################
	// Toggle box
	// ##########################################

	$('.toggle-trigger').click(function() {
		$(this).next().toggle('slow');
		$(this).toggleClass("active");
		return false;
	}).next().hide();

	// ##########################################
	// Tabs
	// ##########################################

	$(".tabs").tabs("div.panes > div", {
		effect : 'fade'
	});

	// ##########################################
	// Create Combo Navi
	// ##########################################

	// Create the dropdown base
	$("<select id='comboNav' />").appendTo("#combo-holder");

	// Create default option "Go to..."
	$("<option />", {
		"selected" : "selected",
		"value" : "",
		"text" : "Navigation"
	}).appendTo("#combo-holder select");

	// Populate dropdown with menu items
	$("#nav a").each(function() {
		var el = $(this);
		var label = $(this).parent().parent().attr('id');
		var sub = (label == 'nav') ? '' : '- ';

		$("<option />", {
			"value" : el.attr("href"),
			"text" : sub + el.text()
		}).appendTo("#combo-holder select");
	});

	// ##########################################
	// Combo Navigation action
	// ##########################################

	$("#comboNav").change(function() {
		location = this.options[this.selectedIndex].value;
	});

	/*
	 * Commented due to relayout issue
	 *  // modified Isotope methods for gutters in masonry
	 * $.Isotope.prototype._getMasonryGutterColumns = function() { var gutter =
	 * this.options.masonry && this.options.masonry.gutterWidth || 0;
	 * containerWidth = this.element.width();
	 * 
	 * this.masonry.columnWidth = this.options.masonry &&
	 * this.options.masonry.columnWidth || // or use the size of the first item
	 * this.$filteredAtoms.outerWidth(true) || // if there's no items, use size
	 * of container containerWidth;
	 * 
	 * this.masonry.columnWidth += gutter;
	 * 
	 * this.masonry.cols = Math.floor( ( containerWidth + gutter ) /
	 * this.masonry.columnWidth ); this.masonry.cols = Math.max(
	 * this.masonry.cols, 1 ); };
	 * 
	 * $.Isotope.prototype._masonryReset = function() { // layout-specific props
	 * this.masonry = {}; // FIXME shouldn't have to call this again
	 * this._getMasonryGutterColumns(); var i = this.masonry.cols;
	 * this.masonry.colYs = []; while (i--) { this.masonry.colYs.push( 0 ); } };
	 * 
	 * $.Isotope.prototype._masonryResizeChanged = function() { var prevSegments =
	 * this.masonry.cols; // update cols/rows this._getMasonryGutterColumns(); //
	 * return if updated cols/rows is not equal to previous return (
	 * this.masonry.cols !== prevSegments ); };
	 */

});