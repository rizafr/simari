var popupStatus=0;
jQuery(document).ready(function(){
	jQuery("#button").click(function(){
		centerPopup();
		loadPopup();
	});
	jQuery("#popupPasswordClose").click(function(){
		disablePopup();
	});
	jQuery("#backgroundPopup").click(function(){
		disablePopup();
	});
	jQuery(document).keypress(function(e){
	if(e.keyCode==27&&popupStatus==1){
		disablePopup();
	}
})});
function loadPopup(){
	var popupStatus=0;
	if(popupStatus==0){
		jQuery("#backgroundPopup").css({"opacity":"0.9"});
		jQuery("#backgroundPopup").fadeIn("slow");
		jQuery("#popupPassword").fadeIn("slow");
		popupStatus=1
	}
}

function disablePopup(){
	if(popupStatus==1){
		jQuery("#backgroundPopup").fadeOut("slow");
		jQuery("#popupPassword").fadeOut("slow");
		popupStatus=0;
	}
}

function centerPopup(){
	var windowWidth=document.documentElement.clientWidth;
	var windowHeight=document.documentElement.clientHeight;
	var popupHeight=jQuery("#popupPassword").height();
	var popupWidth=jQuery("#popupPassword").width();
	var w=200;var h=500;
	w=screen.availWidth;
	h=screen.availHeight;
	//jQuery("#popupPassword").css({"position":"absolute","top":(h-700)/2,"left":(w-700)/2});
	jQuery("#popupPassword").css({"position":"absolute","top":(h-700)/2,"right":20});
	jQuery("#backgroundPopup").css({"height":windowHeight});
}

function closeDetil(){
	jQuery("#popupPassword").fadeOut("slow");
	jQuery("#backgroundPopup").fadeOut("slow");
}