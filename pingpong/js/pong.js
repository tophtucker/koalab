function populatePlayerSelect(playerChanged) {
	console.log("populatePlayerSelect called, playerChanged = " + playerChanged);
	var p1value, p2value;
	if (playerChanged > 0) {
		var p1value = document.getElementById('player1').value;
		var p2value = document.getElementById('player2').value;
	}
	$.get( 
		"php/pong.php",
		{ function: "getOptionsFromLeaderboard" },
		function(data) {
			document.getElementById('player1').innerHTML = data;
			document.getElementById('player2').innerHTML = data;
			if(playerChanged == 0) {
				document.getElementById('player2').selectedIndex = 1;
			}
			else {
				$('#player1').val(p1value);
				$('#player2').val(p2value);
			}
			disableDoubles();
			populateWinnerSelect();
		}
	);
	
}

function populateWinnerSelect() {
	console.log('populateWinnerSelect called');
	// on any call other than first, save the selection
	var selectedIndex = 0;
	if (document.getElementById('winner').length > 0) {
		selectedIndex = document.getElementById('winner').selectedIndex;
	}
	var p1 = document.getElementById('player1').value;
	var p2 = document.getElementById('player2').value;
	var inner = '<option value=\''+p1+'\'>'+p1+'</option><option value=\''+p2+'\'>'+p2+'</option>';
	document.getElementById('winner').innerHTML = inner;
	document.getElementById('winner').selectedIndex = selectedIndex;
}

function disableDoubles() {
	console.log("disableDoubles called");
	var p1value = document.getElementById('player1').value;
	var p2value = document.getElementById('player2').value;
	$("#player1 option[value="+p2value+"]").remove();
	$("#player2 option[value="+p1value+"]").remove();
}

function getLeaderboard() {
	console.log("getLeaderboard called");
	$.get( 
		"php/pong.php",
		{ function: "getLeaderboard" },
		function(data) {
			document.getElementById('leaderboard').innerHTML = data;
		}
	);
}

function startup() {
	console.log("Startup called");
	populatePlayerSelect(0);
}