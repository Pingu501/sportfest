<?php
	if(isset($_GET["sub"]) && $_GET["sub"] == "newq") {
		$number	= $_POST["number"];
		for($a = 1; $a <= $number; $a ++) {
			$vname	= $_POST["vname_$a"];
			$nname	= $_POST["nname_$a"];
			$gebdate= $_POST["gebdate_$a"];
			$sex	= $_POST["sex_$a"];
			$k_id	= $_POST["klasseid_$a"];
			$kPlus	= $_POST["klassenplus_$a"];
			//echo "$vname - $nname - $gebdate - $sex - $k_id <br>";
			$eintrag = mysqli_query($GLOBALS["dbConnection"], "INSERT INTO schueler (vname, nname, gebdatum, geschlecht, klasse_id, klassenplus) VALUES ('$vname', '$nname', '$gebdate', '$sex', '$k_id', '$kPlus')");
			//echo mysqli_errno();
			echo "Eintrag von $number neuen Schülern war erfolgreich";
		}
	}

	if(isset($_GET["sub"]) && $_GET["sub"] == "editq") {
		foreach ($_REQUEST as $key => $value) {
			//echo $key," - ",$value,"<br>";
			if($key >= 0 && $value == ""){
				$stud_id= $key;
				$vname	= $_POST["vname_$stud_id"];
				$nname	= $_POST["nname_$stud_id"];
				$gebdate= $_POST["gebdate_$stud_id"];
				$sex	= $_POST["sex_$stud_id"];
				$k_id	= $_POST["klasseid_$stud_id"];
				$kPlus	= $_POST["klassenplus_$stud_id"];


				/**$stud_query = mysqli_query($GLOBALS["dbConnection"], "SELECT vorname,nachname,geburtsdatum,klasse_id,geschlecht FROM schueler WHERE id = $stud_id");
				if($row = mysqli_fetch_assoc($stud_query)) {
					$db_vname		= $row["vorname"];
					$db_nname		= $row["nachname"];
					$db_gebdate		= $row["geburtsdatum"];
					$db_sex			= $row["geschlecht"];
					$db_stud_k_id	= $row["klasse_id"];

				} **/
				if(isset($_POST["delete_$stud_id"])) {
					if($_POST["deletesure_$stud_id"] == "yes") {
						$value_del	= mysqli_query($GLOBALS["dbConnection"], "DELETE FROM werte WHERE schueler_id = '$stud_id'");
						$sql_delete = mysqli_query($GLOBALS["dbConnection"], "DELETE FROM schueler WHERE id = '$stud_id'");
					}
				} else {
					$sql_update = mysqli_query($GLOBALS["dbConnection"], "UPDATE schueler SET vname = '$vname', nname = '$nname', gebdatum = '$gebdate', geschlecht = '$sex', klasse_id = '$k_id', klassenplus = '$kPlus' WHERE id = '$stud_id'");
				}
			}
		}
	}

	if(isset($_GET["sub"]) && $_GET["sub"] == "editmode") {
		echo "<form action='index.php?con=schueler&sub=editq' method='POST'>";
			echo "<table>";
			echo "<tr>";
				echo "<td>Vorname</td>";
				echo "<td>Nachname</td>";
				echo "<td>Geburtsdatum</td>";
				echo "<td>Geschlecht</td>";
				echo "<td>Klasse</td>";
				echo "<td>Klassenplus</td>";
				echo "<td>Wirklich löschen?</td>";
			echo "</tr>";
			foreach ($_REQUEST as $key => $value) {
				$exp_key 	= explode("_", $key);
				$key_value	= $exp_key[0];
				if($key_value == "edit"){
					$todo 		= explode("_", $value);
					$task 		= $todo[0];
					$stud_id 	= $todo[1];

					echo "<input type='hidden' name='$stud_id' value=''>";

					if($task != "nothing") {

						$stud_query = mysqli_query($GLOBALS["dbConnection"], "SELECT vname,nname,gebdatum,klasse_id,geschlecht,klassenplus FROM schueler WHERE id=$stud_id");
						if($row = mysqli_fetch_assoc($stud_query)) {
							$vname		= $row["vname"];
							$nname		= $row["nname"];
							$gebdate	= $row["gebdatum"];
							$sex		= $row["geschlecht"];
							$stud_k_id	= $row["klasse_id"];
							$kPlusDB	= $row["klassenplus"];

							echo "<tr>";
								echo "<td><input type='text' name='vname_$stud_id' value='$vname' /></td>";
								echo "<td><input type='text' name='nname_$stud_id' value='$nname' /></td>";
								echo "<td><input type='text' name='gebdate_$stud_id' value='$gebdate' /></td>";
								echo "<td>";
									echo "<select name='sex_$stud_id'>";
										if($sex == "M") {
											echo "<option value='M' selected>M</option>";
											echo "<option value='W'>W</option>";
										} else {
											echo "<option value='M'>M</option>";
											echo "<option value='W' selected>W</option>";
										}
									echo "</select>";
								echo "</td>";
								echo "<td>";
									echo "<select name='klasseid_$stud_id'>";
									$select_ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen ORDER BY nummer");
									while($row = mysqli_fetch_assoc($select_ks_query)) {
										$ks_id 	= $row["id"];
										$ks_num	= $row["nummer"];
										$select_k_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,klasse FROM klasse WHERE klassenstufe_id = $ks_id ORDER BY klasse");
										while($row2	= mysqli_fetch_assoc($select_k_query)) {
											$k_id	= $row2["id"];
											$k_num	= $row2["klasse"];
											if($stud_k_id == $k_id) {
												echo "<option value='$k_id' selected>$ks_num/$k_num</option>";
											} else {
												echo "<option value='$k_id'>$ks_num/$k_num</option>";
											}
										}
									}
								echo "</td>";
								echo "<td>";
									echo "<select name='klassenplus_$stud_id'>";
										for($kPlus = -3; $kPlus <= 2; $kPlus++) {
											if($kPlus == $kPlusDB) {
												echo "<option value='$kPlus' selected>$kPlus</option>";
											} else {
												echo "<option value='$kPlus'>$kPlus</option>";
											}
										}
									echo "</select>";
								echo "</td>";
								echo "<td>";
									if($task == "delete") {
										echo "<input type='hidden' name='delete_$stud_id' value='delete_$stud_id' />";
										echo "<input type='radio' name='deletesure_$stud_id' value='yes' />ja <br>";
										echo "<input type='radio' name='deletesure_$stud_id' value='no' selected />nein";
									}
								echo "</td>";
							echo "</tr>";
						}
					}
				}
			}
		echo "<input type='submit'>";
	echo "</form>";
	}
	if(isset($_POST["newstud"])) {
		$number	= $_POST["newstud"];
		echo "<form action='index.php?con=schueler&sub=newq' method='POST'>";
			echo "<input type='hidden' name='number' value='$number'>";
			echo "<table>";
			echo "<tr>";
				echo "<td></td>";
				echo "<td>Vorname</td>";
				echo "<td>Nachname</td>";
				echo "<td>Geburtsdatum</td>";
				echo "<td>Geschlecht</td>";
				echo "<td>Klasse</td>";
			echo "</tr>";
			for($a = 1; $a <= $number; $a++) {
				echo "<tr>";
					echo "<td>$a</td>";
					echo "<td><input type='text' name='vname_$a'></td>";
					echo "<td><input type='text' name='nname_$a'></td>";
					echo "<td><input type='text' name='gebdate_$a'></td>";
					echo "<td>";
						echo "<select name='sex_$a'>";
							echo "<option value='M'>M</option>";
							echo "<option value='W'>W</option>";
						echo "</select>";
					echo "</td>";
					echo "<td>";
						echo "<select name='klasseid_$a'>";
						$select_ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen ORDER BY nummer");
						while($row = mysqli_fetch_assoc($select_ks_query)) {
							$ks_id 	= $row["id"];
							$ks_num	= $row["nummer"];
							$select_k_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,klasse FROM klasse WHERE klassenstufe_id = $ks_id ORDER BY klasse");
							while($row2	= mysqli_fetch_assoc($select_k_query)) {
								$k_id	= $row2["id"];
								$k_num	= $row2["klasse"];
								echo "<option value='$k_id'>$ks_num/$k_num</option>"; }
						}
					echo "</td>";
					echo "<td>";
						echo "<select name='klassenplus_$a'>";
							for($kPlus = -3; $kPlus <= 2; $kPlus++) {
								if($kPlus == 0) {
									echo "<option value='$kPlus' selected>$kPlus</option>";
								} else {
									echo "<option value='$kPlus'>$kPlus</option>";
								}
							}
						echo "</select>";
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<input type='submit' value='Und los gehts!'>";
		echo "</form>";
	}

	if(!isset($_GET["sub"])) {
		echo "<form action='index.php?con=schueler&sub=new' method='POST'>";
			echo "<input type='number' max='30' min='1' value='1' name='newstud'>";
			echo "<input type='submit' value='Neue Schüler anlegen'>";
		echo "</form>";

		echo "<form action='index.php?con=schueler' method='POST'>";
			echo "Zeige Schüler aus Klasse:";
			echo "<select name='klasseid'>";
			$select_ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,nummer FROM klassenstufen ORDER BY nummer");
			while($row = mysqli_fetch_assoc($select_ks_query)) {
				$ks_id 	= $row["id"];
				$ks_num	= $row["nummer"];
				$select_k_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,klasse FROM klasse WHERE klassenstufe_id = $ks_id ORDER BY klasse");
				while($row2	= mysqli_fetch_assoc($select_k_query)) {
					$k_id	= $row2["id"];
					$k_num	= $row2["klasse"];
					if(isset($_POST["klasseid"]) && ($_POST["klasseid"] == $k_id)) {
						echo "<option value='$k_id' selected>$ks_num/$k_num</option>";
					} else {
						echo "<option value='$k_id'>$ks_num/$k_num</option>"; }

				}
			}
			echo "</select>";
			echo "<input type='submit' value='OK!'>";
		echo "</form>";

		if(isset($_POST["klasseid"])) {
			$k_id = $_POST["klasseid"];
			$schueler_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM schueler WHERE klasse_id = $k_id ORDER BY nname");
		} else {
			$schueler_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM schueler ORDER BY klasse_id"); }
		echo "<form action='index.php?con=schueler&sub=editmode' method='POST'>";
			echo "<table>";
				echo "<tr>";
					echo "<td>Name</td>";
					echo "<td>Bearbeiten</td>";
					echo "<td>Löschen</td>";
					echo "<td>Nichts</td>";
				echo "</tr>";
			while($row = mysqli_fetch_assoc($schueler_query)) {
				echo "<tr>";
					$id		= $row["id"];
					$vname	= $row["vname"];
					$nname	= $row["nname"];
					$k_id	= $row["klasse_id"];
					echo "<td>",utf8_encode($vname)," ",utf8_encode($nname),"</td>";
					echo "<td><input type='radio' name='edit_$id' value='edit_$id' /></td>";
					echo "<td><input type='radio' name='edit_$id' value='delete_$id' /></td>";
					echo "<td><input type='radio' name='edit_$id' value='nothing' checked /></td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<input type='submit' value='editmode'>";
		echo "</form>";
	}
?>
