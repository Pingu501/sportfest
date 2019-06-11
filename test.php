<?php

	function calcPoints($ks,$disziplin,$wert) {
		// $ks			= "5";	
		// $disziplin 	= "50m-Lauf";
		// $wert		= "8,4";
		// $ordering	= "ASC";
		
		//$disziplin 	= "Wurfstab";
		//$wert			= "21,60";
		//$ordering		= "DESC";
		
		// $disziplin 	= "Weitsprung";
		// $wert		= "3,04"
		//ordering		= "DESC";
		
		$wert	= str_replace(",", ".", $wert);
		
		require_once 'excel_reader2.php';
		
		$data = new Spreadsheet_Excel_Reader("wertetabelle.xls");
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
				if($data->val($i,$werteCol) < $wert) {
					$Punkte	= $data->val($i,$werteCol);
				} else {
					break;
				}
			}
			
			if($ordering == "DESC") {
				if($data->val($i,$werteCol) <= $wert) {
					$Punkte	= $data->val($i,$werteCol);
				} else {
					break;
				}
			}
			
		}
		
		$PunkteBack	= $data->val($i,$punkteCol);
		//echo "<p>".$Punkte."</p>";
	}
	
?>