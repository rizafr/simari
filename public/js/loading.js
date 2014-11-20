$(function(){
		$(document.body).append('<div id="page-loader"></div>');
	$(window).on("beforeunload", function() {
		$('#page-loader').fadeIn(500).delay(3000).fadeOut(5000);
	});
});

 jQuery(document).ready(function () {
	var loaderParm = document.getElementById('loading');
	if (loaderParm == null) {
	  var loader = jQuery('<div id="page-loader"></div>')
		 // .css({position: "relative", top: "1em", left: "1em"})
		  .hide()
		  .appendTo("body");

		$().ajaxStart(function() {
			loader.show();
		}).ajaxStop(function() {
			loader.hide();
		});		  
	}
 });