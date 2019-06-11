<?php
	if(isset($_GET["sub"]) AND $_GET["sub"] == "newq") {										//Disziplin in DB eintragen
		$bezeich	= $_POST["bezeich"];
		$einheit	= $_POST["einheit"];
		$ordering	= $_POST["ordering"];
		$picture	= $_POST["picture"];
		if($bezeich != "") {
			mysqli_query($GLOBALS["dbConnection"], "INSERT INTO disziplin (bezeichnung, einheit, ordering, picture) VALUES('$bezeich', '$einheit', '$ordering', '$picture')");
		} else {
			echo "Bezeichnung muss Ausgefüllt sein!";
		}

	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "changeq") {										//ge�nderte Disziplin in DB eintragen
		$id			= $_POST["id"];
		$bezeich	= $_POST["bezeich"];
		$einheit	= $_POST["einheit"];
		$ordering	= $_POST["ordering"];
		$picture	= $_POST["picture"];
		if($bezeich != "") {
			$updateQuery	= mysqli_query($GLOBALS["dbConnection"], "UPDATE disziplin SET bezeichnung='$bezeich',einheit='$einheit',ordering='$ordering',picture='$picture' WHERE id='$id'");
			if($updateQuery == TRUE) {
				echo "Änderung erfolgreich ausgeführt";
			} else {
				echo "Es ist ein Fehler aufgetreten!";
				echo mysqli_error($verbindung);
			}
		} else {
			echo "Bezeichnung muss Ausgefüllt sein!";
		}

	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "deleteq") {										//Disziplin l�schen
		$id			= $_GET["id"];
		$check_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klassenstufen WHERE disziplin1=$id OR disziplin2=$id OR disziplin3=$id");
		$count = mysqli_num_rows($check_query);
		if($count == 0) {
			mysqli_query($GLOBALS["dbConnection"], "DELETE FROM disziplin WHERE id = '$id'");
		} else {
			echo "Sie m&uuml;ssen vorher diese Disziplin aus allen Klassenstufen entfernen, da es sonst zu fehlern kommen kann!";
		}
	}

	echo "<p>";
		echo "<a href='index.php?con=disziplin&sub=new'>Neue Disziplin Anlegen</a>";
	echo "</p>";

	if(isset($_GET["sub"]) AND $_GET["sub"] == "new") {											//Anlegen der Disziplin
		echo "<p>Neue Disziplin erstellen:</p>";
		echo "<p>Die Bilder f&uuml;r die Urkunden bitte in das Verzeichnis 'bilder' legen, diese erscheinen dann hier und k&ouml;nnen dann ausgew&auml;hlt werden.</p>";

		echo "<form action='index.php?con=disziplin&sub=newq' method='POST'>";
			echo "<table>";
				echo "<tr>";
					echo "<td>Bezeichnung: </td>";
					echo "<td>";
						echo "<input type='text' name='bezeich' required/>";
					echo "</td>";
				echo "</tr><tr>";
					echo "<td>Einheit: </td>";
					echo "<td>";
						echo "<select name='einheit'>";
							echo "<option value='Meter'>Meter - Längeneinheit</option>";
							echo "<option value='Sekunden'>Sekunden - Zeiteinheit</option>";
						echo "</select>";
					echo "</td>";
				echo "</tr><tr>";
					echo "<td>";
						echo "Bester Wert";
					echo "</td>";
					echo "<td>";
						echo "<select name='ordering'>";
							echo "<option value='DESC'>hoch</option>";
							echo "<option value='ASC'>niedrig</option>";
						echo "</select>";
					echo "</td>";
				echo "</tr><tr>";
					echo "<td>Bild:</td>";
					echo "<td>";
						echo "<select name='picture'>";
							echo "<option value=''>---</option>";
							if ($handle = opendir('bilder/')) {
							    while (false !== ($entry = readdir($handle))) {
							    	if($entry != "." && $entry != "..") {
							       		echo "<option value='$entry'>$entry</option>";
							       	}
							    }
							}
						echo "</select>";
					echo "</td>";
				echo "</tr><tr>";
					echo "<td colspan='2' align='center'><input type='submit' value='erstellen'></td>";
				echo "</tr>";
			echo "</table>";
		echo "</form>";
	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "change") {											//Change ausf�llen
		echo "<p>Disziplin bearbeiten:</p>";
		echo "<p>Die Bilder f&uuml;r die Urkunden bitte in das Verzeichnis 'bilder' legen, diese erscheinen dann hier und k&ouml;nnen dann ausgew&auml;hlt werden.</p>";

		$id = $_GET["id"];
		$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM disziplin WHERE id = '$id'");
		while($row = mysqli_fetch_assoc($disz_query)) {												//Mit aktuellen werten ausgeben
			$bezeichnung 	= $row["bezeichnung"];
			$einheit		= $row["einheit"];
			$ordering		= $row["ordering"];
			$picture		= $row["picture"];
		}
		echo "<form action='index.php?con=disziplin&sub=changeq' method='POST'>";
			echo "<table>";
				echo "<tr>";
					echo "<td>Bezeichnung: </td>";
					echo "<td><input type='text' name='bezeich' value='",$bezeichnung,"'required/></td>";
				echo "</tr><tr>";
					echo "<td>Einheit: </td>";
					echo "<td>";
					echo "<select name='einheit'>";
						if($einheit == "Meter") {
							echo "<option value='Meter' selected>Meter - Längeneinheit</option>";
							echo "<option value='Sekunden'>Sekunden - Zeiteinheit</option>";
						} else {
							echo "<option value='Meter'>Meter - Längeneinheit</option>";
							echo "<option value='Sekunden' selected>Sekunden - Zeiteinheit</option>";
						}
						echo "</select>";
						echo "</td>";
				echo "</tr><tr>";
					echo "<td>";
						echo "Bester Wert";
					echo "</td>";
					echo "<td>";
						echo "<select name='ordering'>";
							if($ordering == "ASC") {
								echo "<option value='DESC'>hoch</option>";
								echo "<option value='ASC' selected >niedrig</option>";
							} else {
								echo "<option value='DESC' selected >hoch</option>";
								echo "<option value='ASC'>niedrig</option>";
							}
						echo "</select>";
					echo "</td>";
				echo "</tr><tr>";
					echo "<td>Bild:</td>";
					echo "<td>";
						echo "<select name='picture'>";
							echo "<option value=''>---</option>";
							if ($handle = opendir('bilder/')) {
							    while (false !== ($entry = readdir($handle))) {
							    	if($entry != "." && $entry != "..") {
							    		if($entry == $picture) {
							    			echo "<option value='$entry' selected>$entry</option>";
							    		} else {
							       			echo "<option value='$entry'>$entry</option>";
							       		}
							       	}
							    }
							}
						echo "</select>";
					echo "</td>";
				echo "</tr><tr>";
					echo "<td colspan='2'>";
						echo "<input type='hidden' name='id' value='",$id,"'>";
						echo "<input type='submit' value='Edit'>";
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		echo "</form>";
	}

	$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM disziplin");										//echo ALL disziplins
	echo "<table>";
	while($row = mysqli_fetch_assoc($disz_query)) {
		echo "<tr>";
			$id				= $row["id"];
			$bezeichnung 	= $row["bezeichnung"];
			$einheit		= $row["einheit"];
			echo "<td>",$bezeichnung,"</td>";
			echo "<td>",$einheit,"</td>";
			echo "<td><a href='index.php?con=disziplin&sub=change&id=",$id,"'>Bearbeien</a></td>";
			echo "<td><a href='index.php?con=disziplin&sub=deleteq&id=",$id,"'>Löschen</a></td>";
		echo "</tr>";
	}
?>
