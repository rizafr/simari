var popupStatus=0;jQuery(document).ready(function(){jQuery("#button").click(function(){centerPopup();loadPopup()});jQuery("#popupContactClose").click(function(){disablePopup()});jQuery("#backgroundPopup").click(function(){disablePopup()});jQuery(document).keypress(function(e){if(e.keyCode==27&&popupStatus==1){disablePopup()}})});function loadPopup(){var popupStatus=0;if(popupStatus==0){jQuery("#backgroundPopup").css({"opacity":"0.4"});jQuery("#backgroundPopup").fadeIn("slow");jQuery("#popupContact").fadeIn("slow");popupStatus=1}}function disablePopup(){if(popupStatus==1){jQuery("#backgroundPopup").fadeOut("slow");jQuery("#popupContact").fadeOut("slow");popupStatus=0}}function centerPopup(){var windowWidth=document.documentElement.clientWidth;var windowHeight=document.documentElement.clientHeight;var popupHeight=jQuery("#popupContact").height();var popupWidth=jQuery("#popupContact").width();var w=200;var h=500;w=screen.availWidth;h=screen.availHeight;jQuery("#popupContact").css({"position":"absolute","top":30,"bottom":30,"left":30,"right":30});jQuery("#backgroundPopup").css({"height":windowHeight})}function closeDetil(){jQuery("#popupContact").fadeOut("slow");jQuery("#backgroundPopup").fadeOut("slow")}