var popupStatus=0;
jQuery(document).ready(function(){
	jQuery("#button").click(function(){
		centerPopup();
		loadPopup()});
	jQuery("#popupContactClose2").click(function(){
		disablePopup2()});
	jQuery("#backgroundPopup2").click(function(){
		disablePopup2()});
	jQuery(document).keypress(function(e){
		if(e.keyCode==27&&popupStatus==1){
			disablePopup2()}
	})
});

function loadPopup2(){
	var popupStatus=0;
	if(popupStatus==0){
		jQuery("#backgroundPopup2").css({"opacity":"1"});
		jQuery("#backgroundPopup2").fadeIn("slow");
		jQuery("#popupContact2").fadeIn("slow");
		popupStatus=1
	}
}

function disablePopup2(){
	if(popupStatus==1){
		jQuery("#backgroundPopup2").fadeOut("slow");
		jQuery("#popupContact2").fadeOut("slow");
		popupStatus=0
	}
}

function centerPopup2(){
	var windowWidth=document.documentElement.clientWidth;
	var windowHeight=document.documentElement.clientHeight;
	var popupHeight=jQuery("#popupContact2").height();
	var popupWidth=jQuery("#popupContact2").width();
	var w=200;
	var h=500;
	w=screen.availWidth;
	h=screen.availHeight;
	jQuery("#popupContact2").css({"position":"absolute","top":(h-700)/2,"left":(w-700)/2});
	jQuery("#backgroundPopup2").css({"height":windowHeight})
}

function closeDetil2(){
	jQuery("#popupContact2").fadeOut("slow");
	jQuery("#backgroundPopup2").fadeOut("slow")
}