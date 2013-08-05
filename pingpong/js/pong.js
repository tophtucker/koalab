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
			if (playerChanged > 0) {
				$('#player1').val(p1value);
				$('#player2').val(p2value);
			}
			disableDoubles();
			populateWinnerSelect();
		}
	);
	
}

function checkBumps() {
	$.get(
		"php/pong.php",
		{function: "checkBumps"},
		function(data) {
			populatePlayerSelect(0);
			getLeaderboard();
		}
	);
}

function populateWinnerSelect() {
	if (document.getElementById('player1').selectedIndex == 0 || document.getElementById('player2').selectedIndex == 0) {
		document.getElementById('winner').innerHTML = "<option>Choose 2 Players</option";
		return;
	}
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
	if (document.getElementById('player2').selectedIndex > 0)
		$("#player1 option[value="+p2value+"]").remove();
	if (document.getElementById('player1').selectedIndex > 0)
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
	if (form.player1.selectedIndex == 0 || form.player2.selectedIndex == 0) {
		document.getElementById('logWarningLabel').innerHTML="You gotta select a player, bro.";
		return;
	}
	var player1 = form.player1.value;
	var player2 = form.player2.value;
	var winner = form.winner.value;
	var loser = (player1==winner)?player2:player1;
	
	var r=window.confirm("Are you sure that " + winner + " beat " + loser);
	if (!r)
		return;
	$.get( 
		"php/pong.php",
		{ function: "logGame", player1: player1, player2: player2, winner: winner },
		function(data) {
			// location.reload();
		}
	);
}

function addPlayer() {
	var player = document.getElementById('newPlayer').value;
	if (player.length == 0) {
		document.getElementById('warningLabel').innerHTML = "Name needs to have length more than NOTHING";
		return;
	}
	if ( /[^A-Za-z\d]/.test(player) ) {
		document.getElementById('warningLabel').innerHTML = "Invalid Characters Detected";
		return;
	}
	var email = document.getElementById('newPlayerEmail').value;
	$.get( 
		"php/pong.php",
		{ function: "addPlayer", player: player, email: email, email_confirmed: false },
		function(data) {
			// location.reload();
		}
	);
}

function startup() {
	checkBumps();
}