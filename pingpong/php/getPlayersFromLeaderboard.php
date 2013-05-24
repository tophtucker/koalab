<?php 
include './ChromePhp.php';
ChromePhp::log('here');
$file_handle = fopen("./leaderboard.dat", "r");
while (!feof($file_handle)) {
	$line = fgets($file_handle);
	echo "<option value=".$line.">".$line."</option>";
}
fclose($file_handle);
return 3; ?>