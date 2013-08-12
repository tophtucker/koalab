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
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/application.css" rel="stylesheet"> <!-- custom koa style -->
	<link href="css/index.css" rel="stylesheet">  <!-- custom koa style -->
	<link href="css/bootstrap-responsive.css" rel="stylesheet">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<![endif]-->
		
		<!-- Evergage Javascript Beacon -->
		<script src="js/evergage.js"></script>

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="../assets/ico/favicon.png">
	</head>
	<body>
		
		<?php
		
		
		if(isset($_POST['email'])) {
     
			// EDIT THE 2 LINES BELOW AS REQUIRED
			$email_to = "samgqroberts@gmail.com";
			$email_subject = "Application for Membership from www.koalab.com";
			$email_from = "sam@koalab.com";
     
     
			function died($error) {
				// your error code can go here
				echo "We are very sorry, but there were error(s) found with the form you submitted. ";
				echo "These errors appear below.<br /><br />";
				echo $error."<br /><br />";
				echo "Please go back and fix these errors.<br /><br />";
				die();
			}
     
			// validation expected data exists
			if(!isset($_POST['name']) ||
				!isset($_POST['email']) ||
					!isset($_POST['phone']) ||
						!isset($_POST['option'])||
							!isset($_POST['start-date'])||
			!isset($_POST['num-people'])) {
				died('We are sorry, but there appears to be a problem with the form you submitted.');       
			}
     
			$name = $_POST['name']; // required
			$email = $_POST['email']; // required
			$phone = $_POST['phone']; // required
			$option = $_POST['option']; // required
			$numDesks = $_POST['num-desks']; // not required
			$startDate = $_POST['start-date']; // required
			$numPeople = $_POST['num-people']; // required
			date_default_timezone_set('America/New_York');
			$date = date('m/d/Y h:i:s a', time());
     
			$error_message = "";
			$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
			if(!preg_match($email_exp,$email)) {
				$error_message .= 'The Email Address you entered does not appear to be valid.<br />';
			}
			$string_exp = "/^[A-Za-z .'-]+$/";
			if(!preg_match($string_exp,$name)) {
				$error_message .= 'The Name you entered does not appear to be valid.<br />';
	
				if(strlen($error_message) > 0) {
					died($error_message);
				}
			}
			$email_message = "Form details below.\n\n";
     
			function clean_string($string) {
				$bad = array("content-type","bcc:","to:","cc:","href");
				return str_replace($bad,"",$string);
			}
     
			$email_message .= "Name: ".clean_string($name)."\n";
			$email_message .= "Email: ".clean_string($email)."\n";
			$email_message .= "Telephone: ".clean_string($phone)."\n";
			$email_message .= "Membership Option: ".clean_string($option)."\n";
			if ($numDesks)
				$email_message .= "Number of Desks: ".$numDesks."\n";
			$email_message .= "Requested Starting Date: ".clean_string($startDate)."\n";
			$email_message .= "Number of People: ".clean_string($numPeople)."\n";
			$email_message .= "Date Submitted: ".clean_string($date)."\n";
			$email_message .= "\n";
     
			//until we are sure the email message works, we will append applications.txt
			$datadir = "./data";
			$file = $datadir."/applications.txt";
			$didwrite = true;
			if ( !file_exists($datadir) ) {
				if ( is_writeable(".")) {
					mkdir ($datadir, 0776);
					$fh = fopen($file, 'a') or die("can't open file");
					fwrite($fh, "THIS FILE HOLDS BACKUP DATA FOR APPLICATION SUBMISSIONS FROM WWW.KOALAB.COM/APPLICATION.HTML");
					fclose($fh);
				}
				else {
					$email_message.="\nWARNING: Due to a problem with file permissions, this application could not be logged.\n";
					$didwrite = false;
				}
			}
			//catch if unable to get to file, meaning we didn't create it.  probably a file permissions thing.
			if(!file_exists($file)) {
				if(!$didwrite)
					$email_message .= "\nWARNING: Application could not be logged, reason unknown.\n";
			}
			else {
				$fh = fopen($file, 'a') or die("can't open file");
				fwrite($fh, $email_message);
				fclose($fh);
			}
			// create email headers
			$headers = 'From: '.$email_from."\r\n".
				'CC: Kelsey <kagcole@gmail.com>\r\n'.
				'Reply-To: '.$email."\r\n" .
					'X-Mailer: PHP/' . phpversion();
			@mail($email_to, $email_subject, $email_message, $headers);
		}
		?>
 
		<!-- include your own success html here -->
	
		<h2 align="center">Thank you for contacting us. We will be in touch with you very soon.</h2>
	
		<br>
	
		<input style = "height:20px; 
		width:100px; 
		margin: -20px -50px; 
		position:relative;
		top:50%; 
		left:50%;""
		type="button"
		value="Back"
		onclick="location.href='./index.shtml'" />
 
	</body>
	</html>