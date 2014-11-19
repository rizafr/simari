jQuery(document).ready(function($) { 
	
	$('#nav ul, #menu ul, #header ul.nav').superfish({ 
		delay:       100,								// .5 second delay on mouseout 
		animation:   {opacity:'show',height:'show'},	// fade-in and slide-down animation 
		dropShadows: false								// disable drop shadows 
	});
	
});