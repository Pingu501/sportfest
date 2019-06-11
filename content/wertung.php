<?php
	if(isset($_GET["sub"]) && $_GET["sub"] == "newq") {
		$sex		= $_POST["sex"];
		$ks_id		= $_POST["ks"];
		$valueTable	= $_POST["valueTable"];
		
		$controll_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM wertung WHERE klassenstufen_id = '$ks_id' AND geschlecht = '$sex'");
		if($row = mysqli_fetch_assoc($controll_query)) {
			$old_id	= $row["id"];
			$update_query = mysqli_query($GLOBALS["dbConnection"], "UPDATE wertung SET valuetable = '$valueTable' WHERE id = $old_id");
			if($update_query == TRUE) {
				echo "Eine alte wurde überschrieben.";
			} else {
				echo "Es ist ein Fehler aufgetreten!";
			}
		} else {
			$value_query = mysqli_query($GLOBALS["dbConnection"], "INSERT INTO wertung (geschlecht,klassenstufen_id,valuetable) VALUES('$sex', '$ks_id', '$valueTable')");
			if($value_query == TRUE) {
				echo "Wurde erstellt.";
			} else {
				echo "Es ist ein Fehler aufgetreten!";
			}
		}
	}
	if(isset($_GET["sub"]) && $_GET["sub"] == "deleteq") {
		if(isset($_POST["yesiwill"]) && $_POST["yesiwill"] == "JA") {
			$value_id	= $_POST["value_id"];
			mysqli_query($GLOBALS["dbConnection"], "DELETE FROM wertung WHERE id = $value_id");
			echo "Sollte gelöscht sein";
		}
	}
	
	if(isset($_GET["sub"]) && $_GET["sub"] == "new") {
		echo "<form action='index.php?con=wertung&sub=newq' method='POST'>";
			echo "Wertung für Klassenstufe";
			echo "<select name='ks'>";
				$ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen ORDER BY nummer");
				while($ks_row = mysqli_fetch_assoc($ks_query)){
					$ks_id	= $ks_row["id"];
					$ks_num	= $ks_row["nummer"];
					echo "<option value='$ks_id'>$ks_num</option>";
				}
			
			echo "</select>";
			
			echo "<select name='sex'>";
					echo "<option value='W'>Mädchen</option>";
					echo "<option value='M'>Jungs</option>";
				echo "</select>";
				
			echo "<br>Wertung: Sie m&uuml;ssen die Wertetabellen unter im Ordner wertetabellen speichern um sie hier auswühlen zu können.";
			echo "<p>";
				echo "<select name='valueTable'>";
					if ($handle = opendir('wertetabellen')) {
					    while (false !== ($entry = readdir($handle))) {
					        if ($entry != "." && $entry != "..") {
					            echo "<option value='$entry'>$entry</option>";
					        }
					    }
					    closedir($handle);
					}
				echo "</select>";
			echo "</p>";
			echo "<p><input type='submit' value='Erstellen'></p>";
		echo "</form>";
	}
	if(isset($_GET["delete"])) {
		$value_id	= $_GET["delete"];
		echo "<form action='index.php?con=wertung&sub=deleteq' method='POST'>";
			echo "Sind Sie sich sicher, dass sie die Wertung $value_id löschen wollen?";
			echo "<input type='hidden' name='value_id' value='$value_id'>";
			echo "<input type='submit' value='JA' name='yesiwill'>";
		echo "</form>";
	}
	if(!isset($_GET["sub"])) {
		$count = 0;
		echo "<a href='index.php?con=wertung&sub=new'>Neue Wertung erstellen</a>";
		echo "<table border='1'>";
		$value_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,geschlecht,klassenstufen_id,valuetable FROM wertung ORDER BY klassenstufen_id");
		while($row = mysqli_fetch_assoc($value_query)) {
			$count++;
			$value_id	= $row["id"];
			$geschlecht	= $row["geschlecht"];
				if($geschlecht == "M") {
					$sexOutput	= "Jungs";
				} else {
					$sexOutput	= "Mädchen";
				}
			$ks_id	= $row["klassenstufen_id"];
				$ks_query 	= mysqli_query($GLOBALS["dbConnection"], "SELECT nummer FROM klassenstufen WHERE id = $ks_id");
				if($ks_row 	= mysqli_fetch_assoc($ks_query)) {
					$ks_num	= $ks_row["nummer"];
				}
			$valueTable	= $row["valuetable"];
			
			echo "<tr>";
				echo "<td>$ks_num</td>";
				echo "<td>$sexOutput</td>";				
				echo "<td>$valueTable</td>";
				
				echo "<td><a href='index.php?con=wertung&sub=delete&delete=$value_id'>löschen</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		if($count == 0){
			echo "Es wurden noch keine Wertungen erstellt!";
		} 
	}
?>