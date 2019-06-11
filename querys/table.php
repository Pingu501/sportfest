<?php	
		$ks_id 	= $_GET["ks"];
		$k_id	= $_GET["klasse"];
		$disz_id= $_GET["disz"];
		
		$number = 0;
		$kNumQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT klasse FROM klasse WHERE id = $k_id");
		if($row = mysqli_fetch_assoc($kNumQuery)) {
			$kNum	= $row["klasse"];
		}
		$ksNumQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT nummer FROM klassenstufen WHERE id = $ks_id");
		if($row = mysqli_fetch_assoc($ksNumQuery)) {
			$ksNum	= $row["nummer"];
		}
		$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT bezeichnung,einheit,ordering FROM disziplin WHERE id = $disz_id");
		if($row = mysqli_fetch_assoc($disz_query)) {
			$disz_bezeich	= $row["bezeichnung"];
			$disz_einheit	= $row["einheit"];
			$ordering		= ($row["ordering"] == "ASC") ? "0" : "1";
			
			$pointsquery = mysqli_query($GLOBALS["dbConnection"], "SELECT valuetable FROM wertung WHERE klassenstufen_id = $ks_id");
			if($row2 = mysqli_fetch_assoc($pointsquery)) {
				$valueTable = $row2["valuetable"];
			}
		}
		if(!isset($valueTable)) {
			echo "Es muss erst eine Wertung für diese Disziplin in dieser Klassenstufe erstellt werden!";
		} else {
			$outMethod	= "return";
			echo "<p>Bitte geben Sie die Werte in der Tabelle ein. Bestättigen Sie die Eingaben mit dem Button 'eintragen' am Ende der Tabelle.</p>";
			echo "<p>Beachten Sie, dass der Button 'Zur&uuml;cksetzen' die Werte aus der Tabelle und der Datenbank entfernt!</p>";
			echo "<th>Klasse: $ksNum/$kNum - $disz_bezeich</th>";
			echo "<form action='index.php?con=selection&sub=auswertung' method='POST' onkeypress='return event.keyCode != 13;'>";
				echo "<table border='1'>";
					echo "<tr>";
						echo "<td>&nbsp</td>";
						echo "<td colspan='2'>Name</td>";
						echo "<td>Geschlecht</td>";
						echo "<td>Klassenplus</td>";
						echo "<td>Wert eingeben</td>";
						echo "<td>Letzter Wert</td>";
						echo "<td>Bester Wert</td>";
						echo "<td>Punkte</td>";
						echo "<td>L&ouml;schen</td>";
						echo "<input type='hidden' name='disz_id' value='$disz_id' />";
					echo "</tr>";
					
					$changeColor	= 0;
					$student_query = mysqli_query($GLOBALS["dbConnection"], "SELECT id,vname,nname,geschlecht,klassenplus FROM schueler WHERE klasse_id = $k_id ORDER BY geschlecht,nname,vname");
					while($row = mysqli_fetch_assoc($student_query)) {
						if($changeColor == 0) {
							$changeColor = 1;
						} else {
							$changeColor = 0;
						}
						echo "<tr class='highlight$changeColor'>";
						
						$stud_id= $row["id"];	
						$vname	= $row["vname"];
						$nname	= $row["nname"];
						$sex	= $row["geschlecht"];
						$kPlus	= $row["klassenplus"];
						//echo "->",$kPlus,"<-";
						$number ++;
						
						echo "<td>";
							$klassThisStud		= $ksNum+$kPlus;
							echo $klassThisStud;
							$KsIdQuery		= mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klassenstufen WHERE nummer = $klassThisStud");
							if($rowKS = mysqli_fetch_assoc($KsIdQuery)) {
								echo "&nbsp";	
								$KsID = $rowKS["id"]; 
							} else {
								echo "KS".$klassThisStud." existiert nicht";
							}
							$UmrechnungQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT valuetable FROM wertung WHERE klassenstufen_id = '$KsID' AND geschlecht = '$sex'");
							if($rowSU = mysqli_fetch_assoc($UmrechnungQuery)) {
								 $valueTable = $rowSU["valuetable"]; 
							} else {
								echo "Keine Umrechnung";
							}
							
							$oldValueQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT wert FROM werte WHERE schueler_id = $stud_id AND disziplin_id = $disz_id");
							if($rowTwo = mysqli_fetch_assoc($oldValueQuery)) {
								$dbValue			= $rowTwo["wert"];
								$points = calcPoints($KsID,$disz_id,$dbValue,$sex,$outMethod);
							} else {
								$dbValue	= "";
								$dbPoints	= "";
								$points		= "";
							}
							$outEinheit	= 0;
						echo "</td>";

							echo "<td>$vname</td><td>$nname </td>";
								echo "<input type='hidden' name='$number' value='$stud_id' />";
							echo "</td>";
							echo "<td> $sex </td>";
							echo "<td> $kPlus </td>";
							echo "<td>";
									$geschlecht = ($sex == "M") ? "0" : "1" ;
								echo "<input id='input$number' type='' value='' tabindex='$number' onchange='lastwerte(this.value,$KsID,$disz_id,$number,$geschlecht, $ordering);' onKeyPress='nextTD(event, $number);' />";
									if($disz_einheit == "Meter") {
										echo "m";
										$outEinheit	= 1;
									}
									if($disz_einheit == "Sekunden") {
										echo "sek";
										$outEinheit = 1;
									}
									if($outEinheit == 0) {
										echo $disz_einheit;
									}
							echo "</td>";
							echo "<td>";
								echo "<input type='text' id='$number-last' value='$dbValue' readonly/>";
							echo "</td>";
							echo "<td>";
								echo "<input type='text' name='$number-best' id='$number-best' value='$dbValue' readonly/>";
							echo "</td>";
							echo "<td>";
								echo "<input type='text' id='$number-points' value='$points' readonly/>";
							echo "</td>";
							echo "<td>";
								echo "<input type='button' value='Zurücksetzen' onclick='delRow($number,$stud_id,$disz_id)' />";
							echo "</td>";
						echo "</tr>";
					}
			
			echo "</table>";
			echo "<input type='hidden' name='number' value='$number' />";
			echo "<input type='submit' value='eintragen' />";
		}
	echo "</form>";
?>