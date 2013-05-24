<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Koa Labs</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Koa is a shared workspace in the heart of Harvard Square for promising start-ups. Founded in 2012 by serial entrepreneur Andy Palmer, the space provides a collaborative environment to power the next generation of innovation.">
	
	<!-- favicon -->
	<link rel="shortcut icon" href="img/favicon.ico">
	
	<!-- Styles -->
	<link href="../css/bootstrap.css" rel="stylesheet">
	<link href="../css/application.css" rel="stylesheet"> <!-- custom koa style -->
	<link href="../css/index.css" rel="stylesheet">  <!-- custom koa style -->
	<link href="pong.css" rel="stylesheet">
	<link href="../css/bootstrap-responsive.css" rel="stylesheet">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="../assets/ico/favicon.png">
	</head>
	<body>
		<div class="totality">
			<br>
			<h1>Ping pong is serious business.</h1>
			<br>
			
			<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="log_game.php">
					<h1 align="center">Log a Game</h1>

					<label>Player 1
						<span class="small">Select a Player</span>
					</label>
					<select name="player1" id="player1"><?php
						$file_handle = fopen("./leaderboard.dat", "r");
						while (!feof($file_handle)) {
						   $line = fgets($file_handle);
						   echo "<option value=".$line.">".$line."</option>";
						}
						fclose($file_handle);
						?>
					</select>
					<br>
					<br>
					<br>
					<label>Player 2
						<span class="small">Select another Player</span>
					</label>
					<select name="player1" id="player1"><?php
						$file_handle = fopen("./leaderboard.dat", "r");
						while (!feof($file_handle)) {
						   $line = fgets($file_handle);
						   echo "<option value=".$line.">".$line."</option>";
						}
						fclose($file_handle);
						?>
					</select>
					
					<br>
					<br>
					<br>

					<label>Phone Number
						<span class="small">Add a convenient number</span>
					</label>
					<input type="tel" name="phone" id="phone" />
	  
					<label>Membership Option
						<span class="small">Select your desired plan</span>
					</label>
					<select name="option" id="membership-option">
						<option value="dedicated-desk">Dedicated Desk</option>
						<option value="dedicated-office">Dedicated Office</option>
					</select>
	  
					<label>Start Date
						<span class="small">When do you need the space?</span>
					</label>
					<input type="date" name="start-date" id="start-date" />
	  
					<label>Number of People
						<span class="small">How many people need space?</span>
					</label>
					<input type="number" name="num-people" id="num-people" min="1" value="1" />
	  
					<div class="center">
						<button type="submit">Apply</button>
					</div>
					<div class="spacer"></div>

				</form>
			</div>
		
			<br>
			<p>Register to play and you start at the bottom of the rankings. You can officially challenge someone who is two ranks above you or less. If you beat them- you get there place and they go down a rank. for example:<br>
				<br>
				1. Red <br>
				2. Blue <br>
				3. Green <br>
				4. Yellow <br>
				<br>
				Yellow can challenge green or blue. If yellow beats green-<br>
				<br>
				1. Red <br>
				2. Blue <br>
				3. Yellow <br>
				4. Green <br>
				<br>
				But if yellow challenged blue and won <br>
				<br>
				1. Red <br>
				2. Yellow <br>
				3. Blue <br>
				4. Green <br>
				<br>
				Notable additions:<br>
				-you can only officially challenge somebody once per day<br></p>
			</div>
		</body>
		</html>