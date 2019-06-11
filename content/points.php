<?php
	echo "<form action='index.php?con=points' method='POST'>";
			echo "<input type='hidden' name='con' value='werte' />";
			echo "Zeige Schüler aus Klasse:";
			// echo "<select id='klassenstufe' name='klasseid' value='' />";   //*** Hier wird das Forumlar erzeugt id und name stimmen nicht überein!!!
			echo "<select id='klasseid' name='klasseid' value='' />";
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
			$outMethod	= "return";
			$k_id		= $_POST["klasseid"];
			$ks_query	= mysqli_query($GLOBALS["dbConnection"], "SELECT klassenstufe_id FROM klasse WHERE id='$k_id'");
			if($row 	= mysqli_fetch_assoc($ks_query)) { $ks_id = $row["klassenstufe_id"];}
			$disz_query	= mysqli_query($GLOBALS["dbConnection"], "SELECT disziplin1,disziplin2,disziplin3,disziplin4 FROM klassenstufen WHERE id=$ks_id");
			if($row 	= mysqli_fetch_assoc($disz_query)) {
				$diszID1 = $row["disziplin1"];
				$diszID2 = $row["disziplin2"];
				$diszID3 = $row["disziplin3"];
				$diszID4 = $row["disziplin4"];
			}
			for($for = 1; $for <= 4; $for++){
				$disz_ueid 	= "diszID$for";
				$disz_id	= $$disz_ueid;
				$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT bezeichnung FROM disziplin WHERE id = '$disz_id'");
				if($row = mysqli_fetch_assoc($disz_query)){
					$DiszName	= "diszBez$for";
					$$DiszName	= $row["bezeichnung"];
				}
			}
			echo "<form action='index.php?con=schueler&sub=editmode' method='POST'>";
				echo "<table border='1'>";
					echo "<tr>";
						echo "<td>Name</td>";
						echo "<td colspan='2'>$diszBez1</td>";
						echo "<td colspan='2'>$diszBez2</td>";
						echo "<td colspan='2'>$diszBez3</td>";
						echo "<td colspan='2'>$diszBez4</td>";
						echo "<td>Gesamtpunkte</td>";
						echo "<td>Notenvorschlag</td>";
					echo "</tr>";
				$schueler_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM schueler WHERE klasse_id = $k_id ORDER BY geschlecht,nname,vname ASC");
				while($row = mysqli_fetch_assoc($schueler_query)) {
					$id			= $row["id"];
					$vname		= $row["vname"];
					$nname		= $row["nname"];
					$k_id		= $row["klasse_id"];  // !!!!!!!!!!!!!!!!!!!!! da stand ["klassen_id"]
					$kPlus		= $row["klassenplus"];
					$sex		= $row["geschlecht"];


					// $ks_id enthält hier die ID aus der Tabelle Klassenstufe
					// Wir brauchen aber die Bezeichnung der Klassenstufe:

					$KsIdQuery		= mysqli_query($GLOBALS["dbConnection"], "SELECT nummer FROM klassenstufen WHERE id = $ks_id");
 					if($rowKS = mysqli_fetch_assoc($KsIdQuery)) {
						$klassenstufe_fab = $rowKS["nummer"];  // $KsID ist die Datenbak-ID der Klassenstufe,
					// Wir arbeiten mal hier schon das kPlus ein:
					$klassenstufe_fab=$klassenstufe_fab+$kPlus;
					// echo "Klassenstufe: $klassenstufe_fab <br>";
					}

					// Diese muss jetzt wieder in die id umgewandelt werden:

					$KsIdQuery		= mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klassenstufen WHERE nummer = $klassenstufe_fab");
 					if($rowKS = mysqli_fetch_assoc($KsIdQuery)) {
						$klassenstufe_id_fab = $rowKS["id"];  // $KsID ist die Datenbak-ID der Klassenstufe,
					// echo "Klassenstufe-ID: $klassenstufe_id_fab <br>";
					}

					$KsID = $klassenstufe_id_fab;

					// $klassThisStud		= $ks_num+$kPlus;   // Hier muss die Klassenstufen-ID ermittelt werden!
					                                          // Was steht eigentlich in ks_num drin???
					// $klassThisStud          = $ks_id+$kPlus;  // Das kPlus wird auf die $ks_id angewandt - das ist falsch!!
					// echo "klassThisStud: $klassThisStud ks_num: $ks_num ks_id: $ks_id<br>";
					// $KsID=$klassThisStud;

// ************klassThisStud ist bereits die richtige Klassenstufen-ID, daher kann folgendes wegfallen:
//					$KsIdQuery		= mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klassenstufen WHERE nummer = $klassThisStud");
// 					if($rowKS = mysqli_fetch_assoc($KsIdQuery)) {
//						$KsID = $rowKS["id"];  // $KsID ist die Datenbak-ID der Klassenstufe,
//					echo "KsID: $KsID <br>";
//					}

					$totalPoints= 0;
					echo "<tr>";
						echo "<td>";
						if ($kPlus>0) echo "<b>";
						echo utf8_encode($vname)," ",utf8_encode($nname);
						if ($kPlus>0) echo $kPlus;
						echo "</b></td>";
						for($for = 1; $for <= 4; $for++){
							echo "<td align='right'>";
								$disz_ueid 	= "diszID$for";
								$disz_id	= $$disz_ueid;

								$ValueQuery = mysqli_query($GLOBALS["dbConnection"], "SELECT wert FROM werte WHERE schueler_id = $id AND disziplin_id = $disz_id");
								if($ValueQuery && $row = mysqli_fetch_assoc($ValueQuery)) {
									$wert				= $row["wert"];
									$CalculationString 	= "$wert$calc";
									$CalculationString 	= str_replace(",", ".", $CalculationString);
									$points	= calcPoints($KsID,$disz_id,$wert,$sex,$outMethod);   // FAB: CalcPoints wurde mit der falschen Klassenstufe aufgerufen!!!
									                                                              // nämlich mit der ks_id, nicht der Nummer
									$totalPoints 		= $totalPoints+$points;
								} else {
									$wert	= "---";
									$points = "---";
								}
								echo "$wert";
							echo "</td>";
							echo "<td align='right'>";
								//echo "<input type='text' name='points$id' value='$points' readonly />";
								echo $points;
							echo "</td>";
						}
						echo "<td align='right'>";
							//echo "<input type='text' name='Totalpoints$id' value='$totalPoints' readonly />";
							echo $totalPoints;
						echo "</td>";


						echo "<td align='right'>";
						  	if($totalPoints>=168) echo "1";
							elseif($totalPoints>=101) echo "2";
							elseif($totalPoints>=51) echo "3";
							elseif($totalPoints>=21) echo "4";
							elseif($totalPoints>=1) echo "5";
							else echo "6";
						echo "</td>";






					echo "</tr>";
				}
				echo "</table>";
				echo "<input type='submit' value='editmode'>";
			echo "</form>";
		}
?>
