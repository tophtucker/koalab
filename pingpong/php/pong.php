<?php

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
		default:
		return;
	}
}

function getOptionsFromLeaderboard() {
	$file_handle = fopen("./leaderboard.dat", "r");
	$return_string = "<option value='Select a Player'>Select a Player</option>";
	while (!feof($file_handle)) {
		$player = fgets($file_handle);
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
			$players_array[$pieces[0]] = $rest;
		}
	}
	fclose($file_handle_players);
	$counter = 1;
	
	//start returnString
	$return_string = "<h2>Current Leaderboard</h2><br><label class='lbplayer'>Player</label><label class='lbwins'>Wins</label><label class='lblosses'>Losses</label><label class='lblastplayed'>Last Played</label>";
	
	while (!feof($file_handle_leaderboard)) {
		$player = trim(fgets($file_handle_leaderboard), " \t\n\r\0");
		$player_stats = explode("$",$players_array[$player]);
		$this_entry = "<label class='lbplayer'>".$counter.". ".$player."</label><label class='lbwins'>".$player_stats[0]."</label><label class='lblosses'>".$player_stats[1]."</label><label class='lblastplayed'>".$player_stats[2]."</label>";
		$return_string .= $this_entry;
		$counter += 1;
	}
	fclose($file_handle_leaderboard);
	return $return_string;
}

function logGame($player1, $player2, $winner) {
	$player_file = "players.dat";
	$file_contents = file_get_contents($player_file);
	$file_contents = explode("\n", $file_contents);
	$new_file_contents = "";
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
}

function addPlayer($player, $email, $email_confirmed) {
	if ($email_confirmed) {
		$leaderboard_file = fopen("./leaderboard.dat", 'a');
		$players_file = fopen("./players.dat", 'a');
		fwrite($leaderboard_file, "\n".$player);
		fwrite($players_file, "\n%".$player."$0$0".'$'."never$".$email."%");
		fclose($leaderboard_file);
		fclose($players_file);
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
	$function_string = str_replace("false", "true", $page_dir[1]);
	$page_dir = $page_dir[0];
	
	$targetURL = $page_dir.$file_name.$function_string;
	
	$email_message .= $pageURL;
	
	$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email."\r\n" .
			'X-Mailer: PHP/' . phpversion();
	@mail($email_to, $email_subject, $email_message, $headers);
}

?>