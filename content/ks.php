<?php
	if(isset($_GET["sub"]) AND $_GET["sub"] == "newq") {										//Klassenstufe in DB eintragen

		$nummer	= $_POST["nummer"];
		$disz1	= $_POST["disz1"];
		$disz2	= $_POST["disz2"];
		$disz3	= $_POST["disz3"];
		$disz4  = $_POST["disz4"];

		if($nummer != "") {
			mysqli_query($GLOBALS["dbConnection"], "INSERT INTO klassenstufen (nummer, disziplin1, disziplin2, disziplin3, disziplin4) VALUES('$nummer','$disz1','$disz2','$disz3','$disz4')");
		} else {
			echo "nummer muss Ausgef&uuml;llt sein!";
		}
	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "changeq") {										//geänderte Klassenstufe in DB eintragen
		$id		= $_POST["id"];
		$nummer	= $_POST["nummer"];
		$disz1	= $_POST["disz1"];
		$disz2	= $_POST["disz2"];
		$disz3	= $_POST["disz3"];
		$disz4  = $_POST["disz4"];

		if($nummer != "") {
			mysqli_query($GLOBALS["dbConnection"], "UPDATE klassenstufen SET nummer='$nummer', disziplin1='$disz1', disziplin2='$disz2', disziplin3='$disz3', disziplin4='$disz4' WHERE id = '$id'");
		} else {
			echo "nummernung muss Ausgef&uuml;llt sein!";
		}

	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "deleteq") {										//Klassenstufe löschen
		$id			= $_GET["id"];
		$check_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klasse WHERE klassenstufe_id = '$id'");
		$count	= 0;
		while($row = mysqli_fetch_assoc($check_query)) {
			$count++;
		}
		if($count == 0) {
			mysqli_query($GLOBALS["dbConnection"], "DELETE FROM klassenstufen WHERE id = '$id'");
		} else {
			echo "Sie m&uuml;ssen vorher diese Klassenstufe aus allen Klassen entfernen, da es sonst zu fehlern kommen kann!";
		}
	}

	echo "<p>";
		echo "<a href='index.php?con=ks&sub=new'>Neue Klassenstufe Anlegen</a>";
	echo "</p>";

	if(isset($_GET["sub"]) AND $_GET["sub"] == "new") {											//Anlegen der Klassenstufe
		echo "create klassenstufe";

		echo "<form action='index.php?con=ks&sub=newq' method='POST'>";
			echo "<table>";
				echo "<tr>";
					echo "<td>Nummer: </td>";
					echo "<td colspan='4'><input type='text' name='nummer' required/></td>";
				echo "</tr>";
				echo "<tr>";
					for($for = 1; $for <= 4; $for++){
						echo "<td>";
							echo "<select name='disz",$for,"'>";
								echo "<option/>";
								$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,bezeichnung FROM disziplin");
								while($row = mysqli_fetch_assoc($disz_query)){
									$disz_id	= $row["id"];
									$bezeich	= $row["bezeichnung"];
									echo "<option value='$disz_id'>$bezeich</option>";
								}
							echo "</select>";
						echo "</td>";
					}
					echo "<td><input type='submit' value='create'></td>";
			echo "</table>";
		echo "</form>";
	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "change") {											//Change ausf�llen
		echo "change klassenstufe";
		$id = $_GET["id"];
		$ks_query 	= mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM klassenstufen WHERE id = '$id'");
		while($row 	= mysqli_fetch_assoc($ks_query)) {												//Mit aktuellen werten ausgeben
			$nummer	= $row["nummer"];
			$disziplin1	= $row["disziplin1"];
			$disziplin2	= $row["disziplin2"];
			$disziplin3	= $row["disziplin3"];
			$disziplin4	= $row["disziplin4"];
		}
		echo "<form action='index.php?con=ks&sub=changeq' method='POST'>";
			echo "<table>";
				echo "<tr>";
					echo "<td>Nummer: </td>";
					echo "<td><input type='text' name='nummer' value='",$nummer,"'required/></td>";
				echo "</tr><tr>";

				for($for = 1; $for <= 4; $for++){
						echo "<td>";
							echo "<select name='disz",$for,"'>";
								$disz_ueid 	= "disziplin$for";				//�bergangsvariable
								$diszold_id	= $$disz_ueid;
								$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,bezeichnung FROM disziplin");
								while($row = mysqli_fetch_assoc($disz_query)){
									$disz_id	= $row["id"];
									$bezeich	= $row["bezeichnung"];
									if($disz_id == $diszold_id) {
										echo "<option value='$disz_id' selected>$bezeich</option>";
									} else {
										echo "<option value='$disz_id'>$bezeich</option>";
									}
								}

							echo "</select>";
						echo "</td>";
					}

					echo "<td colspan='2'>";
						echo "<input type='hidden' name='id' value='",$id,"'>";
						echo "<input type='submit' value='change'>";
						echo "</td>";
			echo "</table>";
		echo "</form>";
	}

	$ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM klassenstufen ORDER BY nummer");										//echo ALL disziplins
	echo "<table border='1'>";
	while($row = mysqli_fetch_assoc($ks_query)) {
		echo "<tr>";
			$id			= $row["id"];
			$nummer		= $row["nummer"];
			$disziplin1	= $row["disziplin1"];
			$disziplin2	= $row["disziplin2"];
			$disziplin3	= $row["disziplin3"];
			$disziplin4	= $row["disziplin4"];

			echo "<td>",$nummer,"</td>";

			for($for = 1;$for <= 4;$for++) {
				$disz_ueid 	= "disziplin$for";				//�bergangsvariable
				$disz_id	= $$disz_ueid;

				$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM disziplin WHERE id = '$disz_id'");
				echo "<td>";
				while($row 	= mysqli_fetch_assoc($disz_query)) {
					echo $row["bezeichnung"];
				}
				echo "</td>";
			}

			echo "<td><a href='index.php?con=ks&sub=change&id=",$id,"'>Bearbeien</a></td>";
			echo "<td><a href='index.php?con=ks&sub=deleteq&id=",$id,"'>Löschen</a></td>";
			echo "</tr><tr>";

		echo "</tr>";
	}
	echo "</table>";
?>
