<?php

// Ping pong stuff:
// Are you sure? when logging a game CHECK
// WE sign them up, their confirmation email will have a link to the page that they should save as a bookmark or whatever
// Email confirmation to loser when logging a game
// API to remove players?  or just go in and delete from text file
// 7-day bump down to bottom

include "ChromePhp.php";

if( $_REQUEST["function"] )
{
	$function = $_REQUEST['function'];
	switch($function){
		case 'getOptionsFromLeaderboard':
		echo getOptionsFromLeaderboard();
		break;
		case 'getLeaderboard':
		echo getLeaderboard();
		break;
		case 'logGame':
		$player1 = $_REQUEST['player1'];
		$player2 = $_REQUEST['player2'];
		$winner = $_REQUEST['winner'];
		logGame($player1, $player2, $winner);
		break;
		case 'addPlayer':
		$player = $_REQUEST['player'];
		$email = $_REQUEST['email'];
		$email_confirmed = $_REQUEST['email_confirmed'];
		addPlayer($player, $email, $email_confirmed);
		break;
		case 'checkBumps':
		checkBumps();
		break;
		default:
		return;
	}
}

function getOptionsFromLeaderboard() {
	$file_handle = fopen("./leaderboard.dat", "r");
	$return_string = "<option value='Select a Player'>Select a Player</option>";
	while (!feof($file_handle)) {
		$player = fgets($file_handle);
		$player = trim($player, "%\n\t");
		if(strlen($player) == 0) {
			continue;
		}
		$return_string .= "<option value=".$player.">".$player."</option>";
	}
	fclose($file_handle);
	return $return_string;
}

function getLeaderboard() {
	$file_handle_leaderboard = fopen("./leaderboard.dat", "r");
	$return_string = "";
	$file_handle_players = fopen("./players.dat", "r");
	$players_array = array();
	while (!feof($file_handle_players)) {
		$line = fgets($file_handle_players);
		if ( $line[0] == "%" ) {
			// strip the %'s and newline stuff
			$line = trim($line, "%\n\t");
			// split into categories
			$pieces = explode("$", $line);
			$rest = $pieces[1]."$".$pieces[2]."$".$pieces[3];
			if ($pieces[0] == "")
				continue;
			$players_array[$pieces[0]] = $rest;
		}
	}
	fclose($file_handle_players);
	$counter = 1;
	
	//start returnString
	$return_string = "<h2>Current Leaderboard</h2><br><label class='lbplayer'>Player</label><label class='lbwins'>Wins</label><label class='lblosses'>Losses</label><label class='lblastplayed'>Last Played</label>";
	
	while (!feof($file_handle_leaderboard)) {
		$player = trim(fgets($file_handle_leaderboard), " \t\n\r\0");
		// pool of the damned
		if ($player == "")
			break;
		$player_stats = explode("$",$players_array[$player]);
		$this_entry = "<label class='lbplayer'>".$counter.". ".$player."</label><label class='lbwins'>".$player_stats[0]."</label><label class='lblosses'>".$player_stats[1]."</label><label class='lblastplayed'>".$player_stats[2]."</label>";
		$return_string .= $this_entry;
		$counter += 1;
	}
	
	$return_string.="<h3>The Pool of the Damned<h3>";
	
	while (!feof($file_handle_leaderboard)) {
		$player = trim(fgets($file_handle_leaderboard), " \t\n\r\0");
		if($player == "")
			continue;
		$player_stats = explode("$",$players_array[$player]);
		$this_entry = "<label class='lbplayer'>".$player."</label><label class='lbwins'>".$player_stats[0]."</label><label class='lblosses'>".$player_stats[1]."</label><label class='lblastplayed'>".$player_stats[2]."</label>";
		$return_string .= $this_entry;
	}
	
	fclose($file_handle_leaderboard);
	return $return_string;
}

function logGame($player1, $player2, $winner) {
	$player_file = "players.dat";
	$file_contents = file_get_contents($player_file);
	$file_contents = explode("\n", $file_contents);
	$new_file_contents = "";
	$loser = ($player1==$winner)?$player2:$player1;
	$winner_pos = getPlayerPosition($winner);
	$loser_pos = getPlayerPosition($loser);
	$positions = getPositions();
	if ($player1_pos == -1) {
		$player1_pos = count($positions);
	}
	if ($player2_pos == -1) {
		$player2_pos = count($positions);
	}
	foreach ($file_contents as $line) {
		if ($line[0] != "%") {
			$new_file_contents .= $line."\n";
			continue;
		}
		$line = trim($line, "%\n\t");
		$line = explode("$", $line);
		if ($line[0] == $player1) {
			if ($winner == $player1) {
				$line[1] = intval($line[1])+1;
			}
			else {
				$line[2] = intval($line[2])+1;
			}
			date_default_timezone_set('America/New_York');
			$line[3] = date('m/d/Y', time());
		}
		elseif ($line[0] == $player2) {
			if ($winner == $player2) {
				$line[1] = intval($line[1])+1;
			}
			else {
				$line[2] = intval($line[2])+1;
			}
			date_default_timezone_set('America/New_York');
			$line[3] = date('m/d/Y', time());
		}
		$line = join("$",$line);
		$line = "%".$line."%";
		$new_file_contents .= $line."\n";
	}
	$new_file_contents = trim($new_file_contents, "\n");
	file_put_contents($player_file, $new_file_contents);
	
	// affect leaderboard
	$difference = $loser_pos - $winner_pos;
	if($difference == 2 || $difference == 1) {
		printLeaderboard(__LINE__);
		changePosition($winner, -$difference);
		printLeaderboard(__LINE__);
		if( $difference == 2) {
		changePosition($loser, $difference-1);
		}
		printLeaderboard(__LINE__);
	}
	// if no difference, they both came from pool of the damned
	elseif($difference == 0) {
		changePosition($winner, -1);
		changePosition($loser, -1);
	}
}

function printLeaderboard($line) {
	ChromePhp::log($line.": Printing Leaderboard");
	$positions = getPositions();
	foreach($positions as $key=>$value) {
		ChromePhp::log("\t".$key.". ".$value);
	}
	ChromePhp::log("\t-----DAMNED-----");
	$damned = getPoolOfTheDamned();
	foreach($damned as $entry) {
		ChromePhp::log("\t".$entry);
	}
}

function addPlayer($player, $email, $email_confirmed) {
	ChromePhp::log("start, confirmed = ".$email_confirmed);
	if ($email_confirmed == "true") {
		ChromePhp::log("here confirmed = ".$email_confirmed);
		$leaderboard_file = fopen("./leaderboard.dat", 'a');
		$players_file = fopen("./players.dat", 'a');
		fwrite($leaderboard_file, "\n".$player);
		fwrite($players_file, "\n%".$player."$0$0".'$'."never$".$email."%");
		fclose($leaderboard_file);
		fclose($players_file);
		
		// get current url
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80") {
		    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else {
		    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	
		//get just the directory in the url
		$file_name = substr(__FILE__, strlen(__DIR__), strlen(__FILE__));
		$page_dir = explode($file_name, $pageURL);
		$page_dir = explode("php", $page_dir[0]);
		$page_dir = $page_dir[0];
		
		$target_file_name = "pong.html";
	
		$targetURL = $page_dir.$target_file_name;
		
		ChromePhp::log($targetURL);
		
		header('Location: '.$targetURL);
		
		ChromePhp::log($targetURL);
		
		return;
	}
	$email_to = $email;
	$email_subject = "Confirmation for Koa Labs Ping Pong";
	$email_from = "sam@koalab.com";
	$email_message = "Hello ".$player.", \n\nClick on dat link down der to finish signing up.\n\n";
	
	// get current url
	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
	    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else 
	{
	    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	//get just the directory in the url
	$file_name = substr(__FILE__, strlen(__DIR__), strlen(__FILE__));
	$page_dir = explode($file_name, $pageURL);
	$function_string = str_replace("email_confirmed=false", "email_confirmed=true", $page_dir[1]);
	$page_dir = $page_dir[0];
	
	$targetURL = $page_dir.$file_name.$function_string;
	
	$email_message .= $targetURL;
	
	$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email."\r\n" .
			'X-Mailer: PHP/' . phpversion();
	@mail($email_to, $email_subject, $email_message, $headers);
}

function checkBumps() {
	$file_handle_players = fopen("./players.dat", "r");
	$players_array = array();
	while (!feof($file_handle_players)) {
		$line = fgets($file_handle_players);
		if ( $line[0] == "%" ) {
			// strip the %'s and newline stuff
			$line = trim($line, "%\n\t");
			// split into categories
			$pieces = explode("$", $line);
			$players_array[$pieces[0]] = $pieces[3];
		}
	}
	$todays_date = date("m/d/Y");
	$today_timestamp = strtotime($todays_date);
	$last_week_timestamp = $today_timestamp-7*86400+7200;
	foreach ($players_array as $player => $last_played) {
		$last_played_timestamp = strtotime($last_played);
		if ($last_week_timestamp > $last_played_timestamp && !playerIsInPoolOfTheDamned($player)) {
			changePosition($player, 9999);
		}
	}
	fclose($file_handle_players);
}

function changePosition($player, $motion) {
	$position_array = getPositions();
	$position = getPlayerPosition($player);
	$count = count($position_array);
	if ($position == -1 && $motion < 0) {
		$position = $count;
		removeFromPoolOfTheDamned($player);
	}
	if($position+$motion < 0 && $motion < 0) {
		$motion = -$position;
	}
	$toTheDamned = false;
	if ($position+$motion > $count-1 && $motion > 0) {
		$toTheDamned = true;
		addToPoolOfTheDamned($player);
	}
	while ($motion > 0) {
		if( $position == $count ) {
			if ( $toTheDamned ) {
				unset($position_array[$position]);
				break;
			}
			else {
				break;
			}
		}
		$temp = $position_array[$position+1];
		$position_array[$position+1] = $player;
		$position_array[$position] = $temp;
		$position++;
		$motion--;
	}
	
	while ($motion < 0) {
		
		$temp = $position_array[$position-1];
		$position_array[$position-1] = $player;
		$position_array[$position] = $temp;
		$position--;
		$motion++;
	}
	$count = count($position_array);
	for( $i = 0 ; $i < $count ; $i++ ) {
		$new_file_contents .= $position_array[$i]."\n";
	}
	//now for the pool of the damned
	$pool = getPoolOfTheDamned();
	if (count($pool) > 0)
		$new_file_contents .= $pool[0];
	for( $i = 1 ; $i < count($pool) ; $i++ ) {
		$new_file_contents .= "\n".$pool[$i];
	}
	trim($new_file_contents, "\n");
	file_put_contents("./leaderboard.dat", $new_file_contents);
}

function getPlayerPosition($player) {
	$position_array = getPositions();
	foreach($position_array as $index => $entry) {
		if ($entry == $player)
			return $index;
		if ($entry == "")
			return -1;
	}
}

function getPositions() {
	$position_array = array();
	$file_handler = fopen("./leaderboard.dat", "r");
	$i = 0;
	while (!feof($file_handler)) {
		$line = fgets($file_handler);
		$line = trim($line, "%\n\t");
		// found pool of the damned
		if ($line == "")
			break;
		$position_array[$i] = $line;
		$i++;
	}
	fclose($file_handler);
	return $position_array;
}

function removeFromPoolOfTheDamned($player) {
	$file_handler = fopen("./leaderboard.dat", "r");
	$new_file_contents = "";
	$first = true;
	while (!feof($file_handler)) {
		$line = fgets($file_handler);
		$line = trim($line, "%\n\t");
		if ($line == $player)
			continue;
		if ($first) {
			$new_file_contents .= $line;
			$first = false;
			continue;
		}
		$new_file_contents .= "\n".$line;
	}
	trim($new_file_contents, "\n");
	fclose($file_handler);
}

function addToPoolOfTheDamned($player) {
	$file_handler = fopen("./leaderboard.dat", "a");
	fwrite($file_handler, "\n".$player);
	fclose($file_handler);
	$pool = getPoolOfTheDamned();
	foreach($pool as $entry) {
	}
}

function getPoolOfTheDamned() {
	$file_handler = fopen("./leaderboard.dat", "r");
	while (!feof($file_handler)) {
		$line = fgets($file_handler);
		$line = trim($line, "%\n\t");
		// find pool of the damned
		if ($line == "")
			break;
	}
	$pool = array();
	$i = 0;
	while (!feof($file_handler)) {
		$line = fgets($file_handler);
		$line = trim($line, "%\n\t");
		$pool[$i] = $line;
		$i++;
	}
	fclose($file_handler);
	return $pool;
}

function playerIsInPoolOfTheDamned($player) {
	$file_handler = fopen("./leaderboard.dat", "r");
	while (!feof($file_handler)) {
		$line = fgets($file_handler);
		$line = trim($line, "%\n\t");
		if ($line == $player)
			return false;
		if ($line == "")
			return true;
	}
}

?>