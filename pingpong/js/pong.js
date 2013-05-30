function populatePlayerSelect(playerChanged) {
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
	var p1value = document.getElementById('player1').value;
	var p2value = document.getElementById('player2').value;
	$("#player1 option[value="+p2value+"]").remove();
	$("#player2 option[value="+p1value+"]").remove();
}

function getLeaderboard() {
	$.get( 
		"php/pong.php",
		{ function: "getLeaderboard" },
		function(data) {
			document.getElementById('leaderboard').innerHTML = data;
		}
	);
}

function logGame(form) {
	$.get( 
		"php/pong.php",
		{ function: "logGame", player1: form.player1.value, player2: form.player2.value, winner: form.winner.value },
		function(data) {
			location.reload();
		}
	);
}

function addPlayer() {
	var player = document.getElementById('newPlayer').value;
	if (player.length == 0) {
		document.getElementById('warningLabel').innerHTML = "Needs to have length more than NOTHING";
	}
	if ( /[^A-Za-z\d]/.test(player) ) {
		document.getElementById('warningLabel').innerHTML = "Invalid Characters Detected";
	}
	// $.get( 
// 		"php/pong.php",
// 		{ function: "addPlayer", player1: player },
// 		function(data) {
// 			location.reload();
// 		}
// 	);
}

function startup() {
	populatePlayerSelect(0);
	getLeaderboard();
}