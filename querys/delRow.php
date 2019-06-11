<?php

	$studentID	= $_GET["studentid"];
	$diszID		= $_GET["diszID"];

	$query	= mysqli_query($GLOBALS["dbConnection"], "DELETE FROM werte WHERE schueler_id = $studentID AND disziplin_id = $diszID");
	if($query) {
		echo "Eintrag erfolgreich entfernt.";
	} else {
		echo "Es ist ein Fehler aufgetreten.";
	}

?>
