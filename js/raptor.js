/*
* jQuery Raptorize Plugin 1.0
* www.ZURB.com/playground
* Copyright 2010, ZURB
* Free to use under the MIT license.
* http://www.opensource.org/licenses/mit-license.php
*
* Modified
*/



(function ($) {   

	
	
	var raptorUrls = [
	//'http://i.imgur.com/e5Q7h.jpg', //Schiff
	'/img/kelseythumbsup.png' //kelsey
	];
	var raptorCounter = 0;

	$.fn.raptorize = function (options) {  

		
		//AnalyticsUtils.trackEvent("Special", "Raptor", null, null);

		var randomImageUrl = raptorUrls[Math.floor(Math.random() * raptorUrls.length)];
		//Yo' defaults
		var defaults = {
			enterOn: 'timer', //timer, konami-code, click
			delayTime: 100 //time before raptor attacks on timer mode
		};

		//Extend those options
		var options = $.extend(defaults, options);

		return this.each(function () {   

			

			var _this = $(this);
			var audioSupported = false;
			//Stupid Browser Checking which should be in jQuery Support
			//if ($.browser.mozilla && $.browser.version.substr(0, 5) >= "1.9.2" || $.browser.webkit) {
			//	audioSupported = true;
			//}

			$("#elRaptor").remove();
			//Raptor Vars
			var raptorImageMarkup = '<img style="display: none;z-index:30000" src="' + randomImageUrl + '" />';
			var locked = false;
			
			var andyImageUrl = '/img/andystern.png';
			var andyImageMarkup = '<img style="display: none;z-index:30000" src="' + andyImageUrl + '" />';

			var scaredKelseyUrl = '/img/scaredkelsey.png';

			//Append Raptor and Style
			var raptor = $(raptorImageMarkup);
			$('body').append(raptor);
			raptor.css({
				"position": "fixed",
				"bottom": "-310px",
				"right": "0",
				"display": "block"
			})
			
			//Append Andy and Style
			var andy = $(andyImageMarkup);
			$('body').append(andy);
			andy.css({
				"position": "fixed",
				"bottom": "-1000px",
				"left": "20px",
				"display": "block"
			})


			var scaredkelsey = new Image();
			scaredkelsey.src = scaredKelseyUrl;
			scaredkelsey.onload = function () { init() };

			function init() {
				var image = new Image();
				image.onload = function () { initAndyImage() };
				image.src = randomImageUrl;
			}
			
			function initAndyImage() {
				var andyimage = new Image();
				andyimage.onload = function () { initBoth() };
				andyimage.src = andyImageUrl;
			}
			
			function initBoth() {
				initAfterImageLoad();
				initAndyAfterImageLoad();
			}

			// Animating Code
			function initAfterImageLoad() {
				locked = true;

				// Movement Hilarity	
				raptor.animate({
					"bottom": "20px"
				}, 200, function () {

					$(this).animate({
						"bottom": "0"
					}, 200, function () {
						var offset = (($(this).position().left) - 580);
						$(this).delay(300).animate({
							"right": offset
						}, { duration: 1300, easing:'easeInQuad', complete: function () {
							$(this).attr("src", scaredKelseyUrl);
							$(this).delay(400).animate({
								"bottom": "-450px"
							}, { duration: 1000, easing:'easeInQuad', complete: function () {
								raptor.remove();
								locked = false;	
							}})
						}})
					});
				});
			}
			
			// Andy animation
			function initAndyAfterImageLoad() {
				
				// Movement Hilarity	
				andy.delay(1800).animate({
					"bottom": "20px"
				}, 200, function () {

					$(this).animate({
						"bottom": "0"
					}, 200, function () {
						var offset = (($(this).position().right) - 400);
						$(this).delay(300).animate({
							"left": "100px"
						}, { duration:1600, easing:'easeInOutCirc', complete: function () {
							$(this).animate({
								"left":"2000px"
							}, 500, function () {
								andy.remove();
							})
						}}  )
					});
				});
				
				
			}


		}); //each call
	} //orbit plugin call
})(jQuery);



$("body").raptorize();
$(window).scrollTop(9999999); // run away!