<?php
	require "db_conf.php";
	require "function.php";
	
	if(isset($_GET["calcPoints"])) {
		$KsID	= $_GET["ksid"];
		$diszID	= $_GET["diszid"];
		$wert	= $_GET["wert"];
		$sex	= $_GET["sex"];
		$outMethod	= "echo";
		calcPoints($KsID,$diszID,$wert,$sex,$outMethod);
	} else {
		$query = $_GET["con"];
		require "querys/$query.php";
	}
?>