

$(function () {
	
	// konami code - up up down down left right left right b a
	// var code1 = String.fromCharCode(38, 38, 40, 40, 37, 39, 37, 39, 66, 65);
	// apparently the konami code is too complicated, changing to kelseyrocks
	var code1 = String.fromCharCode(75, 69, 76, 83, 69, 89, 82, 79, 67, 75, 83);
	var code2 = String.fromCharCode(38, 38, 40, 40, 37, 39, 37, 39, 65, 66);
	var codeBuffer = "";
	$(document).keyup(function (e) {
		
		codeBuffer += String.fromCharCode(e.which);
		if (code1.substring(0, codeBuffer.length) == codeBuffer) {
			
			if (code1.length == codeBuffer.length) {
				toggle1();
				codeBuffer = String.fromCharCode(75, 69, 76, 83, 69, 89, 82, 79, 67, 75);
			}
		} else if (code2.substring(0, codeBuffer.length) == codeBuffer) {
			if (code2.length == codeBuffer.length) {
				toggle2();
				codeBuffer = "";
			}
		} else {
			codeBuffer = "";
		}
	});

	function toggle1() {

		
		if ($("body").raptorize) {
			$("body").raptorize();
		} else {
			$("head").append('<script src="js/raptor.js"></script>');
		}
	}

	function toggle2() {   

		
		window.DEBUG_FLAG = !window.DEBUG_FLAG;
		window.DEBUG_FILTER_INCLUDE = null;
		window.DEBUG_FILTER_EXCLUDE = null;
		window.logInited=true;

		updateConsoleVisibility();
	}
}); 