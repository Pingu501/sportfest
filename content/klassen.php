<?php
	echo "<a href='index.php?con=klassen&sub=new'>Klasse erstellen</a>";

	if(isset($_POST["newq"])){
		$ks_id	= $_POST["ks"];
		$klasse	= $_POST["klasse"];
		mysqli_query($GLOBALS["dbConnection"], "INSERT INTO klasse (klassenstufe_id,klasse) VALUES ('$ks_id','$klasse')");
	}

	if(isset($_POST["changeq"])){
		$k_id	= $_POST["id"];
		$ks_id	= $_POST["ks"];
		$klasse	= $_POST["klasse"];
		mysqli_query($GLOBALS["dbConnection"], "UPDATE klasse  SET klassenstufe_id='$ks_id',klasse='$klasse' WHERE id='$k_id'");
	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "delete") {										//Disziplin l�schen
		$id			= $_GET["id"];
		$check_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM schueler WHERE klasse_id = $id");
		$count	= 0;
		while($row = mysqli_fetch_assoc($check_query)) {
			$count++;
		}
		if($count == 0) {
			mysqli_query($GLOBALS["dbConnection"], "DELETE FROM klasse WHERE id = '$id'");
		} else {
			echo "Sie müssen vorher diese Klasse aus allen Schülern entfernen, da es sonst zu fehlern kommen kann!";
		}
	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "new") {
		echo "<form action='index.php?con=klassen&sub=newq' method='POST'>";
			echo "Klasse die erstellt werden soll: ";
			echo "<select name='ks'>";
			$ks_query 	= mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen ORDER BY nummer");
			while($row1 	= mysqli_fetch_assoc($ks_query)) {
				$ks_id	= $row1["id"];
				$ks_num	= $row1["nummer"];
				echo "<option value='",$ks_id,"'>",$ks_num,"</option>";
			}
			echo "</select>";

			echo "/";
			echo "<input type='text' name='klasse' size='2'>";
			echo "<input type='submit' name='newq' value='Erstellen'>";
		echo "</form>";
	}

	if(isset($_GET["sub"]) AND $_GET["sub"] == "change") {
		$k_id	= $_GET["id"];

		$k_query= mysqli_query($GLOBALS["dbConnection"], "SELECT klassenstufe_id,klasse FROM klasse WHERE id = '$k_id'");
		if($row = mysqli_fetch_assoc($k_query)) {
			$k_ksid_old	= $row["klassenstufe_id"];
			$k_klas_old	= $row["klasse"];
		}

		echo "<form action='index.php?con=klassen&sub=changeq' method='POST'>";
			echo "Klasse die bearbeitet werden soll: ";
			echo "<select name='ks'>";
			$ks_query 	= mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen ORDER BY nummer");
			while ($row1 	= mysqli_fetch_assoc($ks_query)) {
				$ks_id	= $row1["id"];
				$ks_num	= $row1["nummer"];

				if("$ks_id" == "$k_ksid_old"){
					echo "<option value='$ks_id' selected>",$ks_num,"</option>";
				} else {
					echo "<option value='$ks_id'>",$ks_num,"</option>";
				}
			}
			echo "</select>";

			echo "/";
			echo "<input type='text' name='klasse' value='",$k_klas_old,"' size='2'>";
			echo "<input type='hidden' name='id' value='",$k_id,"'>";
			echo "<input type='submit' name='changeq' value='Bearbeiten'>";
		echo "</form>";
	}

	echo "<table>";
	$k_query 	= mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM klasse ORDER BY klasse");
	while ($row = mysqli_fetch_assoc($k_query)) {
		echo "<tr>";
		$k_id	= $row["id"];
		$k_ksid	= $row["klassenstufe_id"];
		$k_klas	= $row["klasse"];

		$ks_query 	= mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen WHERE id = $k_ksid");
		if($row1 	= mysqli_fetch_assoc($ks_query)) {
			$ks_id	= $row1["id"];
			$ks_num	= $row1["nummer"];
		}
		echo "<td>",$ks_num,"/",$k_klas,"</td>";
		echo "<td><a href='index.php?con=klassen&sub=change&id=",$k_id,"'>Berarbeiten</a></td>";
		echo "<td><a href='index.php?con=klassen&sub=delete&id=",$k_id,"'>Löschen</a></td>";
	}
	echo "</table>";
?>
