//------------------------------------
//
//	ENGAGE.TBLANK.JS
//
//	Automatically shows a popup on
//	external link clicks then sets
//	cookie to remember the choice.
//
//	Author: 	Engage Interactive
//	Requires:	jquery 1.4.x
//				engage.tblank.css
//
//	Copyright (c) 2010 Engage Interactive (engageinteractive.co.uk)
//	Dual licensed under the MIT and GPL licenses:
//	http://www.opensource.org/licenses/mit-license.php
//	http://www.gnu.org/licenses/gpl.html
//
//------------------------------------


// Activate the plugin
$(document).ready(function() {
	$('body').tblank();
});


// Plugin code
(function($){
	
	$.fn.tblank = function(options) {
		
		var defaults = {
			internal:	'internal',		// Add this class to a link and it will always open in the same window
			external:	'external',		// Add this class to a link and it will always open in a new window
			cookie:		true,			// Use a cookie to store the decision
			cookieName:	'engage.tblank',// What brand is your cookie?
			useBy:		365,			// The cookies use by date
			popupText:	'<p>You&rsquo;ve clicked an external link, how would you like to open it?</p><p>We will remember your answer for next time.</p>',
			onOpen: 	function(){},	// Fires before the popup opens
			onSame:		function(){},	// Fires for same window
			onNew:		function(){},	// Fires for new window
			onCancel:	function(){},	// Fires on cancel (clicking the cancel button or the background)
			devMode:	false
		},
		settings = $.extend({}, defaults, options);
	
		// Only show popup for external links (a link with a class of internal or external will never show the popup)
		$('a[href*="http://"]:not([href*="'+location.hostname+'"]):not([class*="'+ settings.internal +'"]):not([class*="'+ settings.external +'"]):not([id="ep_new"])').live('click',function() {

			if(settings.cookie == true){
				// What does the cookie say?
				c = $.cookie(settings.cookieName);
				
				// What does it want to do?
				if( c == 'new'){
					window.open($(this).attr('href'));
				}else if( c == 'same' ){
					window.location.href($(this).attr('href'));
				}else{
					external(this.href, $(this).attr('title'));
				}
			}else{
				external(this.href, $(this).attr('title'));
			}
			return false;

		});
		
		function external(url, title){
			
			// Popup HTML, remember to use \ for new lines
			html = '\
				<div id="externalPopup">\
					<div id="ep_container">\
						<h3 id="ep_title"><em></em></h3>\
						'+settings.popupText+'\
						<ul>\
							<li><a href="#" id="ep_new" class="' + settings.external + '">Новое окно</a></li>\
							<li><a href="#" id="ep_same" class="' + settings.internal + '">В этом окне</a></li>\
							<li><a href="#" id="ep_cancel" title="закрыть и вернуться на сайт">Закрыть</a></li>\
						</ul>\
					</div>\
					<span id="ep_background"></span>\
				</div>\
			';
			
			// Include the HTML at the end of the body
			$('body').append(html);
			
			// Set some content
			$('#ep_title em').before(title).text(url);
			$('#ep_new').attr('href',url).attr('title','Open '+title+' in a new window');
			$('#ep_same').attr('href',url).attr('title','Open '+title+' in the same window');
			
			// Fade the background
			$('#ep_background').css({opacity:0.8});
			
			// Show the popup - you could do something a bit more complex here if you wanted
			$('#externalPopup').show();
			
			// Fix ie6
			ie6('open');
			
			// Fire the onOpen callback once the popup opens
			settings.onOpen.call(this);
		}
		
		// New window
		$('#ep_new').live('click',function(){

			// Fire the onNew callback before visiting the link
			settings.onNew.call(this);
			
			$.cookie(settings.cookieName, 'new', { expires: settings.useBy });
			$('#externalPopup').remove();
			ie6('close');
			window.open($(this).attr('href'));
			return false;
		});
		
		// Same window
		$('#ep_same').live('click',function(){
			// Fire the onSame callback before visiting the link
			settings.onSame.call(this);
			
			$.cookie(settings.cookieName, 'same', { expires: settings.useBy });
		});
		
		// Cancel
		$('#ep_cancel, #ep_background').live('click',function(){
			
			// Fire the onCancel callback as the user cancels
			settings.onCancel.call(this);
			
			$.cookie(settings.cookieName, null);
			$('#externalPopup').remove();
			ie6('close');
			return false;
		});
		
		// Forced external links (add the class of external)
		$('a.external').live('click',function(){
			window.open($(this).attr('href'));
			return false;
		});
		
		
		// IE 6 Fixing
		// Hides the scrollbars and positions the popup absolutely (instead of fixed).
		// This stops the page from scrolling so that the popup can't be scrolled out of view.
		function ie6(act){
			
			// Check if we're on stuipid ie6 and fix it
			if ($.browser.msie && $.browser.version.substr(0,1)<7) {
				if(act == 'open'){
					pageH = $(window).height();
					pageY = $(window).scrollTop();
					$('#externalPopup').css({position: 'absolute', height:pageH, top: pageY});
					$('html, body').css({overflow:'hidden'});
					$('#ep_background').css({width:$(window).width(), height:pageH});
				}else if(act == 'close'){
					$('html, body').css({overflow:''});
				}
			}
		}
		
		
		// For testing this will add a couple of links to your page so that you can delete and show the cookies
		if(settings.devMode == true){
			$('body').prepend('<a href="#" id="ep_devDelete">Delete cookie</a> <a href="#" id="ep_devShow">Show cookie</a>');
		}
		
		$('#ep_devDelete').live('click', function(){
			$.cookie(settings.cookieName, null);
		});
		$('#ep_devShow').live('click', function(){
			alert( $.cookie(settings.cookieName) );
		});
	
	};
})(jQuery);


//------------------------------------
//
//	Cookie plugin
//
//	Copyright (c) 2006 Klaus Hartl (stilbuero.de)
//	Dual licensed under the MIT and GPL licenses:
//	http://www.opensource.org/licenses/mit-license.php
//	http://www.gnu.org/licenses/gpl.html
//
//------------------------------------

jQuery.cookie = function(name, value, options) {
	if (typeof value != 'undefined') {
		options = options || {};
		if (value === null) {
			value = '';
			options = $.extend({}, options);
			options.expires = -1
		}
		var expires = '';
		if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
			var date;
			if (typeof options.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000))
			} else {
				date = options.expires
			}
			expires = '; expires=' + date.toUTCString()
		}
		var path = options.path ? '; path=' + (options.path) : '';
		var domain = options.domain ? '; domain=' + (options.domain) : '';
		var secure = options.secure ? '; secure' : '';
		document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('')
	} else {
		var cookieValue = null;
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = jQuery.trim(cookies[i]);
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break
				}
			}
		}
		return cookieValue
	}
};
