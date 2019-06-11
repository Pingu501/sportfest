<?php

	function calcPoints($ksID,$disziplinID,$wert,$geschlecht,$outMethod) {

		//$geschlecht = ($geschlecht == "0") ? "M" : "W";
		// echo "calcPoints aufgerufen mit: $ksID, $disziplinID, $wert, $geschlecht <br>";

		$orderingQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT bezeichnung,ordering FROM disziplin WHERE id = '$disziplinID'");
		if($row = mysqli_fetch_assoc($orderingQuery)) {
			$ordering	= $row["ordering"];
			$disziplin	= $row["bezeichnung"];
		}

		// echo "SELECT valuetable FROM wertung WHERE geschlecht='$geschlecht' AND klassenstufen_id='$ksID' <br>";  // Hier sollte die NUMMER der Klassenstufe
		$tableQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT valuetable FROM wertung WHERE geschlecht='$geschlecht' AND klassenstufen_id='$ksID'");
		if($row = mysqli_fetch_assoc($tableQuery)) {
			$valueTable	= $row["valuetable"];
			// echo "$valueTable : ";
		}

		$wert	= str_replace(",", ".", $wert);

		require_once 'excel_reader2.php';

		$data = new Spreadsheet_Excel_Reader("wertetabellen/".$valueTable);
		$rows	= $data->rowcount($sheet_index=0);
		$cols	= $data->colcount($sheet_index=0);

		for($i = 1; $i <= $cols; $i++) {
			if($data->val(2,$i) == $disziplin) {
				break;
			}
		}

		if($data->val(3,$i) == "Punkte") {
			$punkteCol	= $i;
			$werteCol	= $i+1;
		} else {
			$punkteCol	= $i+1;
			$werteCol	= $i+2;
		}

		for($i = 5; $i <= $rows; $i++) {
			if($data->val($i,$werteCol) == NULL) {
				break;
			}

			if($ordering == "ASC") {
				if($data->val($i,$werteCol) <= $wert) {
					$Punkte	= $data->val($i,$punkteCol);
					if($wert > $data->val($i,$werteCol) && $data->val($i,$punkteCol) == "1") {
						$Punkte = 0;
						break;
					}
				} else {
					if($data->val($i,$punkteCol) == "100" && $wert < $data->val($i,$werteCol)) {
						$calcPoints	= $data->val("4",$punkteCol);
						$calcWert	= $data->val("4",$werteCol);
						$restWert	= $data->val($i,$werteCol) - $wert;
						$Punkte		= ($restWert/$calcWert*$calcPoints)+100;
					}
					break;
				}
			}

			if($ordering == "DESC") {
				if($data->val($i,$werteCol) <= $wert) {
					$Punkte	= $data->val($i,$punkteCol);

					if($Punkte == "100" && $wert > $data->val($i,$werteCol)) {
						$calcPoints	= $data->val("4",$punkteCol);
						$calcWert	= $data->val("4",$werteCol);
						$restWert	= $wert - $data->val($i,$werteCol);
						$Punkte		= ($restWert/$calcWert*$calcPoints)+100;
					}
				} else {
					if($wert < $data->val($i,$werteCol) && $data->val($i,$punkteCol) == "1") {
						$Punkte = 0;
						break;
					}
					break;
				}
			}

		}

		if($outMethod == "echo") {
			echo "$Punkte";
		} else {
			return $Punkte;
		}

		//echo "<p>".$Punkte."</p>";
	}

?>
