<?php
	if(session_id() == '') {
	    session_start();
	}
	require "db_conf.php";
	require "function.php";

	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
?>

<html>
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel='stylesheet' href='style.css' />
		<div class="menu">
			<?php require 'menu.php'; ?>
		</div>
	</head>

	<body>
		<div class="content">
			<?php
				if(isset($_GET["con"])) {
					$con = $_GET["con"];
					require "content/$con.php";
				} else {
					echo "Herzlich willkommen in den Tiefen der Informatik!";
				}
			?>
		</div>

		<div class='footer'>

		</div>
	</body>
</html>
