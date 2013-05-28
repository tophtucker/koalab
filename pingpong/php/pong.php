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
		default:
		return;
	}
}

function getOptionsFromLeaderboard() {
	$file_handle = fopen("./leaderboard.dat", "r");
	$return_string = "";
	while (!feof($file_handle)) {
		$player = fgets($file_handle);
		$return_string .= "<option value=".$player.">".$player."</option>";
	}
	fclose($file_handle);
	return $return_string;
}

function getLeaderboard() {
	$file_handle = fopen("./leaderboard.dat", "r");
	$return_string = "";
	while (!feof($file_handle)) {
		$player = fgets($file_handle);
		
		$return_string .= "<option value=".$line.">".$line."</option>";
	}
	fclose($file_handle);
	return $return_string;
}

?>