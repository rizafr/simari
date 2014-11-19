/*
+--------------------------------------------------------------------------
|   modalWindow Plugin
|   =============================================
|   by Oliver Trebes
|   (c) 2006 - 2008 amplified webservices
|   =============================================
|   http://www.angelmedia.de
+--------------------------------------------------------------------------
|   > $Date$
|   > $Revision$
|   > $Author$
+--------------------------------------------------------------------------
|   > Filename:     modalWindow.js
|   > Date started: Tue Dec 18 11:49:23 CET 2007
+--------------------------------------------------------------------------
|	Title: Modal Window Plugin
|
|	Please see <copyright.inc.php> for a detailed description, copyright
|	and license information.
+--------------------------------------------------------------------------
|	script inspired by lightbox
+--------------------------------------------------------------------------
*/

	try {
		if (undefined == xajax.ext)
			xajax.ext = {};
	} catch (e) {
		alert("Could not create xajax.ext namespace");
	}

	try {
		if (undefined == xajax.ext.modalWindow)
			xajax.ext.modalWindow = {};
	} catch (e) {
		alert("Could not create xajax.ext.modalWindow namespace");
	}

	xjxmW = xajax.ext.modalWindow;
	
	xjxmW.tools = {
		
		getWindowWidth 		: function( objDoc, objWin ) {
								return (objDoc.layers||(objDoc.getElementById&&!objDoc.all)) ? objWin.innerWidth : (objDoc.all ? objDoc.body.clientWidth : 0);
							  },
		getWindowHeight		: function( objDoc, objWin ) {
								return objWin.innerHeight ? objWin.innerHeight :(objDoc.getBoxObjectFor ? Math.min(objDoc.documentElement.clientHeight, objDoc.body.clientHeight) : ((objDoc.documentElement.clientHeight != 0) ? objDoc.documentElement.clientHeight : (objDoc.body ? objDoc.body.clientHeight : 0)));
							  },
		getScrollWidth		: function( objDoc, objWin ) {
								return objDoc.all ? Math.max(Math.max(objDoc.documentElement.offsetWidth, objDoc.documentElement.scrollWidth), objDoc.body.scrollWidth) : (objDoc.body ? objDoc.body.scrollWidth : ((objDoc.documentElement.scrollWidth != 0) ? objDoc.documentElement.scrollWidth : 0));
							  },
		getScrollHeight		: function( objDoc, objWin ) {
								return Math.max( Math.max( objDoc.body.scrollHeight, objDoc.documentElement.scrollHeight ), Math.max( objDoc.body.offsetHeight, objDoc.documentElement.offsetHeight ) );
							  },
		getScrollLeft		: function( objDoc, objWin ) {
								return objDoc.all ? (!objDoc.documentElement.scrollLeft ? objDoc.body.scrollLeft : objDoc.documentElement.scrollLeft) : ((objWin.pageXOffset != 0) ? objWin.pageXOffset : 0);
							  },
		getScrollTop		: function( objDoc, objWin ) {
								return objDoc.all ? (!objDoc.documentElement.scrollTop ? objDoc.body.scrollTop : objDoc.documentElement.scrollTop) : ((objWin.pageYOffset != 0) ? objWin.pageYOffset : 0);
							  },
		getClientLeft		: function( objDoc, objWin ) {
								return (!objDoc.documentElement.clientLeft ? objDoc.body.clientLeft : objDoc.documentElement.clientLeft);
							  },
		getClientTop		: function( objDoc, objWin ) {
							  	return (!objDoc.documentElement.clientTop ? objDoc.body.clientTop : objDoc.documentElement.clientTop);
							  },
		getSize				: function( objDoc, objWin ) {
								WindowWidth   = parseInt(xjxmW.tools.getWindowWidth ( objDoc, objWin ));
								WindowHeight  = parseInt(xjxmW.tools.getWindowHeight( objDoc, objWin ));
								ScrollWidth   = parseInt(xjxmW.tools.getScrollWidth ( objDoc, objWin ));
								ScrollHeight  = parseInt(xjxmW.tools.getScrollHeight( objDoc, objWin ));
								ScrollLeft    = parseInt(xjxmW.tools.getScrollLeft  ( objDoc, objWin ));
								ScrollTop     = parseInt(xjxmW.tools.getScrollTop   ( objDoc, objWin ));
								PageWidth	  = Math.max( ScrollWidth, WindowWidth);
								PageHeight	  = Math.max( ScrollHeight, WindowHeight);
								
								/*alert(ScrollHeight);
								alert(WindowHeight);
								alert(PageHeight);*/
								
								return { pW: PageWidth, pH: PageHeight, wW: WindowWidth, wH: WindowHeight, sW: ScrollWidth, sh: ScrollHeight, sL: ScrollLeft, sT: ScrollTop };
							  },
		getElementSize		: function( e ) {
								if ( e.display != 'none' && e.display != null )
								{
									return {width: e.offsetWidth, height: e.offsetHeight};
								}
								else
								{
								    var originalVisibility	= e.style.visibility;
								    var originalPosition 	= e.style.position;
								    var originalDisplay 	= e.style.display;
								    e.style.visibility 		= 'hidden';
								    e.style.position 		= 'absolute';
								    e.style.display 		= 'block';
								    var sizes 				= {width: e.clientWidth, height: e.clientHeight};
								    var originalHeight 		= e.clientHeight;
								    e.style.display 		= originalDisplay;
								    e.style.position 		= originalPosition;
								    e.style.visibility 		= originalVisibility;
								    return sizes;
							 	}  			
							  },
		hideSelects			: function( objDoc, visibility ) {
								if (navigator.appVersion.indexOf("MSIE")!=-1)
								{
									var selects = objDoc.getElementsByTagName('select');
			
									for(i = 0; i < selects.length; i++)
									{
										if ( !selects[i].rel )
										{
											selects[i].rel = 'ddl_' + objDoc.openmW;
										}
										
										if ( selects[i].rel == 'ddl_' + objDoc.openmW )
										{
											selects[i].style.visibility = visibility;
										}
										
										if ( visibility != 'hidden' )
										{
											selects[i].rel = null;
										}
									}
							  	}
							  },
		byClassName			: function (className, tag, elm) {
								tag = tag || "*";
								elm = elm || document;
								var classes = className.split(" "),
									classesToCheck = [],
									elements = (tag === "*" && elm.all)? elm.all : elm.getElementsByTagName(tag),
									current,
									returnElements = [],
									match;
								for(var k=0, kl=classes.length; k<kl; k+=1){
									classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
								}
								for(var l=0, ll=elements.length; l<ll; l+=1){
									current = elements[l];
									match = false;
									for(var m=0, ml=classesToCheck.length; m<ml; m+=1){
										match = classesToCheck[m].test(current.className);
										if (!match) {
											break;
										}
									}
									if (match) {
										returnElements.push(current);
									}
								}
								return returnElements;
							  },
		getEvent			: function ( eventObj, objWin ) {
								  eventObj = eventObj || window.event || objWin.event;
							  	return eventObj;
							  },
		mouseCoord			: function( eventObj, objDoc ) {
								if ( eventObj.pageX || eventObj.pageY )
								{
									return {x: eventObj.pageX, y: eventObj.pageY};
								}
								return { x: eventObj.clientX + xjxmW.tools.getScrollLeft(objDoc) - xjxmW.tools.getClientLeft(objDoc), y:eventObj.clientY + xjxmW.tools.getScrollTop(objDoc) - xjxmW.tools.getClientTop(objDoc)}
							  },
		addEvent			: function ( command )
							  {
								command.fullName = 'addEvent';
								var element = command.id;
								var sEvent = command.prop;
								var code = command.data;
								code = code.length == 0 ? 'null' : code;
								if ('string' == typeof element)
									element = xajax.$(element);
								sEvent = xajax.tools.addOnPrefix(sEvent);
								code = xajax.tools.doubleQuotes(code);
								eval('element.' + sEvent + ' = ' + code + ';');
								return true;							  	
							  },
		addOverlay			: function( command )
							  {
								if ( true == command.bOverlay )
								{
									var objOverlay 		= command.objDoc.createElement("div");
									objOverlay.setAttribute('id','xjxmW_' + command.objDoc.openmW );
									objOverlay.style.display 	= 'none';
									objOverlay.style.position	= 'absolute';
									objOverlay.style.top		= '0';
									objOverlay.style.left		= '0';
									objOverlay.style.zIndex		= command.zIndex;
								 	objOverlay.style.width		= '100%';
								 	objOverlay.style.height		= command.PageSize.pH + 'px';
								 	objOverlay.style.minHeight	= '100%';
								 	
								 	if ( command.color != null )
								 	{
								 		objOverlay.style.backgroundColor = command.color;
								 	}
								 	
								 	if ( command.opacity != null )
								 	{
								 		objOverlay.style.display = '';
								 	
										if (navigator.appVersion.indexOf("MSIE")!=-1)
										{
											objOverlay.style.filter = "alpha(opacity=" + command.opacity + ")";
										}
										else
										{
								 			objOverlay.style.opacity = ( command.opacity / 100 );
										}
								 	}	 	
								 	
								 	if ( command.className != null )
								 	{
										objOverlay.className = command.className;
								 	}	 	
								 	
								 	if ( command.iPageOverFlow != null )
								 	{
										pageOverFlow = command.PageOverFlow;
								 	}	 	
								 	
								 	command.objBody.appendChild(objOverlay);	
							  	}
							  }
	}
	
	/**
	 *  xjxmW Drag Class
	 */
	
	xjxmW.drag = {
		obj		:	null,
		init	:	function( objWin, o, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper)
					{
						xjxmW.tools.addEvent( {id: o, prop:'mousedown', data: 'xjxmW.drag.start' } );
						o.objWin 		= objWin;
						o.hmode			= bSwapHorzRef ? false : true ;
						o.vmode			= bSwapVertRef ? false : true ;
						o.root = o;
						while ( o.root.id.indexOf('xjxmWc_') == -1 )
						{
							o.root = o.root.parentNode;
						}
				
						if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
						if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
						if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
						if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";
				
						o.minX	= typeof minX != 'undefined' ? minX : null;
						o.minY	= typeof minY != 'undefined' ? minY : null;
						o.maxX	= typeof maxX != 'undefined' ? maxX : null;
						o.maxY	= typeof maxY != 'undefined' ? maxY : null;
				
						o.xMapper = fXMapper ? fXMapper : null;
						o.yMapper = fYMapper ? fYMapper : null;
				
						o.root.onDragStart	= new Function();
						o.root.onDragEnd	= new Function();
						o.root.onDrag		= new Function();
					},
		start 	: 	function(e)
					{
						//alert(e.type);
						var o = xjxmW.drag.obj = this;
						e = xjxmW.tools.getEvent(e, o.objWin);
						var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
						var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
						
						o.root.onDragStart(x, y);
						
						objDoc = o.ownerDocument ? o.ownerDocument : document;
						
						var mouse = xjxmW.tools.mouseCoord(e, objDoc);
				
						o.lastMouseX	= mouse.x;
						o.lastMouseY	= mouse.y;
				
						if (o.hmode) {
							if (o.minX != null)	o.minMouseX	= mouse.x - x + o.minX;
							if (o.maxX != null)	o.maxMouseX	= o.minMouseX + o.maxX - o.minX;
						} else {
							if (o.minX != null) o.maxMouseX = -o.minX + mouse.x + x;
							if (o.maxX != null) o.minMouseX = -o.maxX + mouse.x + x;
						}
				
						if (o.vmode) {
							if (o.minY != null)	o.minMouseY	= mouse.y - y + o.minY;
							if (o.maxY != null)	o.maxMouseY	= o.minMouseY + o.maxY - o.minY;
						} else {
							if (o.minY != null) o.maxMouseY = -o.minY + mouse.y + y;
							if (o.maxY != null) o.minMouseY = -o.maxY + mouse.y + y;
						}
						objDoc = o.ownerDocument ? o.ownerDocument : document;
						xjxmW.tools.addEvent( {id: objDoc, prop:'mousemove', data: 'xjxmW.drag.drag' } );
						xjxmW.tools.addEvent( {id: objDoc, prop:'mouseup', data: 'xjxmW.drag.end' } );
						return false;
					},
		drag 	: 	function(e)
					{
						var o = xjxmW.drag.obj;
						e = xjxmW.tools.getEvent(e, o.objWin);
						objDoc = o.ownerDocument ? o.ownerDocument : document;
						var mouse = xjxmW.tools.mouseCoord(e, objDoc);
						var ey	= mouse.y;
						var ex	= mouse.x;
						var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
						var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
						var nx, ny;
						if (o.minX != null) ex = o.hmode ? Math.max(ex, o.minMouseX) : Math.min(ex, o.maxMouseX);
						if (o.maxX != null) ex = o.hmode ? Math.min(ex, o.maxMouseX) : Math.max(ex, o.minMouseX);
						if (o.minY != null) ey = o.vmode ? Math.max(ey, o.minMouseY) : Math.min(ey, o.maxMouseY);
						if (o.maxY != null) ey = o.vmode ? Math.min(ey, o.maxMouseY) : Math.max(ey, o.minMouseY);
						nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
						ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));
						if (o.xMapper)		nx = o.xMapper(y)
						else if (o.yMapper)	ny = o.yMapper(x)
						xjxmW.drag.obj.root.style[o.hmode ? "left" : "right"] = nx + "px";
						xjxmW.drag.obj.root.style[o.vmode ? "top" : "bottom"] = ny + "px";
						xjxmW.drag.obj.lastMouseX	= ex;
						xjxmW.drag.obj.lastMouseY	= ey;
						xjxmW.drag.obj.root.onDrag(nx, ny);
						return false;
					},
		end 	: 	function()
					{
						var o = xjxmW.drag.obj;

						objDoc = o.ownerDocument ? o.ownerDocument : document;
						xjxmW.tools.addEvent( {id: objDoc, prop:'mousemove', data: '' } );
						xjxmW.tools.addEvent( {id: objDoc, prop:'mouseup', data: '' } );
						xjxmW.drag.obj.root.onDragEnd(	parseInt(xjxmW.drag.obj.root.style[xjxmW.drag.obj.hmode ? "left" : "right"]), 
														parseInt(xjxmW.drag.obj.root.style[xjxmW.drag.obj.vmode ? "top" : "bottom"]));
						xjxmW.drag.obj = null;
					}
	};		
	
	/**
	 * Function mw:aw add a new modal window
	 */
	
	xjxmW.addWindow = function( command )
	{
		var pageOverFlow	= 0;
		
		command.objDoc			= document;
		command.objWin			= window;
		 
		if( parent.frames.length && command.frame.length > 0 )
		{
			command.objDoc = command.frame == 'parent' ? parent.document : parent.frames[command.frame].document;
			command.objWin = command.frame == 'parent' ? parent : parent.frames[command.frame];
		}
		
		if (undefined == command.objDoc.openmW) { command.objDoc.openmW = 0; }
		
		xjxmW.tools.hideSelects( command.objDoc, 'hidden' );

		command.objDoc.openmW++;

		command.objBody		= command.objDoc.getElementsByTagName("body").item(0) ? command.objDoc.getElementsByTagName("body").item(0) : command.objDoc.getElementsByTagName("html").item(0);
		command.zIndex		= command.objDoc.openmW ? command.objDoc.openmW * 1000 : 1000;
		command.PageSize	= xjxmW.tools.getSize( command.objDoc, command.objWin );
		
		var xmW = command.objDoc.createElement("div");
		
		xmW.setAttribute('id',	'xjxmWc_' + command.objDoc.openmW );
		xmW.style.zIndex 		= command.zIndex + 1;
		xmW.style.visibility	= 'hidden';
		xmW.style.position		= 'absolute';
		xmW.innerHTML 			= command.data;
		
		command.objBody.appendChild(xmW);
		
		xmW.sizes = xjxmW.tools.getElementSize( xmW.firstChild ); 
	
		if ( xmW.sizes.height > command.PageSize.pH )
		{
			command.PageSize.wH = xmW.sizes.height + pageOverFlow;
			command.PageSize.pH = xmW.sizes.height + pageOverFlow;
		}
		
		xmW.style.top  = Math.max( (command.PageSize.sT + ( (command.PageSize.wH -  xmW.sizes.height ) / 2 ) ), 0 )  + 'px';
		xmW.style.left = Math.max( ( ( (command.PageSize.pW -  xmW.sizes.width ) / 2 ) ), 0 ) + 'px';
		
		var aTemp = xjxmW.tools.byClassName('xmWmoveable', '*', xmW );
		
		if ( aTemp.length > 0 )
		{
			for (var i = 0, j = aTemp.length; i < j; i++)
			{
				xjxmW.drag.init( command.objWin, aTemp[i], 0, (command.PageSize.pW - xmW.sizes.width - 2), 0,  command.PageSize.pH - xmW.sizes.height - 2);
			}			
		}
		
		//command.overLayHeight = command.PageSize.pH;
		
		xjxmW.tools.addOverlay( command );

		xmW.style.visibility	= '';
		return true;
	}
	
	/**
	 * Function mw:cw remove the highest modal window
	 */
	
	xjxmW.closeWindow = function( args )
	{
		var objDoc			= document;
		
		if( parent.frames.length )
		{
			if ( args == undefined )
			{
				args = 'parent';
			}
			objDoc = args == 'parent' ? parent.document : parent.frames[args].document;	
		}
		
		var activewidget = objDoc.openmW;
		
		
		lId = 'xjxmW_' + activewidget;
		cId = 'xjxmWc_' + activewidget;
	
		objElement = objDoc.getElementById(cId);
		
		if (objElement && objElement.parentNode && objElement.parentNode.removeChild)
		{
			objElement.parentNode.removeChild(objElement);
		}	
		
		objElement = objDoc.getElementById(lId);
		
		if (objElement && objElement.parentNode && objElement.parentNode.removeChild)
		{
			objElement.parentNode.removeChild(objElement);
			xjxmW.tools.hideSelects( objDoc ); 
		}
		
		objDoc.openmW--;
		
		return true;
	}
	
	xajax.command.handler.register('mw:aw', xajax.ext.modalWindow.addWindow );	
	xajax.command.handler.register('mw:cw', xajax.ext.modalWindow.closeWindow );	