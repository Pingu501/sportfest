<?php
	
	$ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM klassenstufen ORDER BY nummer");	
	while($row = mysqli_fetch_assoc($ks_query)) {
		$ks_id	= $row["id"];
		$ks_num	= $row["nummer"];
		echo "<option value='$ks_id'>$ks_num</option>";
	}
	
?>