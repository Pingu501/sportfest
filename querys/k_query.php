<?php
	$ks_id = $_GET["ksid"];
	$k_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM klasse WHERE klassenstufe_id = $ks_id ORDER BY klasse");
	while($row = mysqli_fetch_assoc($k_query)) {
		$k_id		= $row["id"];
		$k_klasse	= $row["klasse"];
		echo "<option value='$k_id'>$k_klasse</option>";
	}
?>