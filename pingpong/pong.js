function populateWinnerSelect() {
	console.log('popwin');
	var p1 = document.getElementById('player1').value;
	var p2 = document.getElementById('player2').value;
	var inner = '<option value=\''+player1+'\'>'+player1+'</option><option value=\''+player2+'\'>'+player2+'</option>';
	document.getElementById('winner').innerHTML = inner;
	document.getElementById('winner').value = player1;
}

function disableDoubles() {
	var p1select = document.getElementById('player1');
	var p2select = document.getElementById('player2');
	var p1options = p1select.options;
	var p2options = p2select.options;
	var p1value = p1select.value;
	var p2value = p2select.value;
	for (int i = 0 ; i < p1options.length ; i++) {
		if (p1options[i] === p2value){
			p1select.remove(i);
			break;
		}
	}
	for (int j = 0 ; j < p2options.length ; j++) {
		if (p2options[j] === p1value){
			p2select.remove(j);
			break;
		}
	}
}

function startup() {
	console.log('wat');
	document.getElementById('player2').selectedIndex = 1;
	disableDoubles();
	populateWinnerSelect();
}