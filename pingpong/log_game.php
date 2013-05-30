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
	<link href="pong.css" rel="stylesheet">  <!-- custom koa style -->
	<link href="css/bootstrap-responsive.css" rel="stylesheet">

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
		<?php
		
		$fh = fopen($file, 'a') or die("can't open file");
		fwrite($fh, $email_message);
		fclose($fh);
		
		?>
		
		<h2 align="center">Yay the game has been logged.</h2>
		
		<br>
	
		<input class = "pongbutton" type="button" value="Back" onclick="location.href='./pong.html'" />
 
	</body>
	</html>