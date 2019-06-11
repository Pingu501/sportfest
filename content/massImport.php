<?php
$errorLog = "";
date_default_timezone_set('Europe/Berlin');
$thisYear	= date("Y");
//$thisYear	= "2012";

echo "Aktuelles Jahr/Eingestelltes Jahr:".$thisYear;

require_once 'excel_reader2.php';

$data = new Spreadsheet_Excel_Reader("Sportfest-Schuelerdaten.xls");
$rows	= $data->rowcount($sheet_index=0);
$cols	= $data->colcount($sheet_index=0);


echo "Bitte die Sch&uuml;lerdaten in einer 'Sportfest-Schuelerdaten.xls' im Rootverzeichnis hinterlegen";
echo "Es stehen ".$rows." Schüler zum Importieren bereit<br>";

echo "<form action='index.php?con=massImport' method='POST'>";
	echo "<input type='submit' name='ImportYes' value='Alle möglichen Schüler importieren'>";
echo "</form>";

echo "<table>";

	echo "<tr>";
		echo "<td>Klasse</td>";
		echo "<td>Nachname</td>";
		echo "<td>Vorname</td>";
		echo "<td>Geburtstag</td>";
		echo "<td>Klassenplus</td>";
		echo "<td>Fehlermeldung</td>";
	echo "</tr>";

for($i = 1; $i<=$rows; $i++) {

	echo "<tr>";

	$kPlus	= "";

	$nname		= $data->val($i,1);
	$vname		= $data->val($i,2);
	$sex		= $data->val($i,3);
	$bday		= $data->val($i,4);
	$klasse		= $data->val($i,5);

	if($sex == "m") {
		$sex = "M";
	}
	if($sex == "w") {
		$sex = "W";
	}

	$bDayEx		= explode(".", $bday);
	$bDayYear	= $bDayEx[2];

	$klassEx	= explode("/", $klasse);

	if(isset($klassEx[1])) {

		$ksNum	= $klassEx[0];
		$kNum	= $klassEx[1];

		echo "<td>".$ksNum."/".$kNum."</td>";

		echo "<td>".$nname."</td>";

		echo "<td>".$vname."</td>";

		echo "<td>".$bday."</td>";

		$ksQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klassenstufen WHERE nummer = $ksNum");
		if($row = mysqli_fetch_assoc($ksQuery)) {
			$ksID = $row["id"];

			$kQuery		= mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM klasse WHERE klassenstufe_id = $ksID AND klasse = $kNum");
			if($row2	= mysqli_fetch_assoc($kQuery)) {
				$kID 	= $row2["id"];

				$kPlusYear1	= $thisYear-($ksNum+6);						//Bekomme eigentliches Geburtsjahr f�r die Klassenstufe
				$string		= "12/31/$kPlusYear1";						// Als Unix timestamp
				$timestamp1 = strtotime($string);

				$kPlusYear2	= $kPlusYear1-1;							// Und das zweite Jahr dazu
				$string	= "01/01/$kPlusYear2";							// Als Unix timestamp
				$timestamp2 = strtotime($string);

				$bdayEng	= $bDayEx[1]."/".$bDayEx[0]."/".$bDayEx[2]+$tempkPlus;
				$bDayStamp	= strtotime($bdayEng);

				if($bDayYear <= $kPlusYear1 AND $bDayYear >= $kPlusYear2) {
					$kPlus = 0;
				} else {
					for($a = 1; $a <= 3; $a++){
						if($bDayYear+$a <= $kPlusYear1 AND $bDayYear+$a >= $kPlusYear2 AND $kPlus == "") {
							$kPlus = $a;
						}
					}
					for($a = 1; $a <= 3; $a++){
						if($bDayYear-$a <= $kPlusYear1 AND $bDayYear-$a >= $kPlusYear2  AND $kPlus == "") {
							$kPlus = "-".$a;
						}
					}
				}

				echo "<td style='padding-left: 40px'>".$kPlus."</td>";
				//echo $kPlusYear1." - ".$bday." - ".$kPlusYear2. "-->".$kPlus;

				if(isset($_POST["ImportYes"])) {
					echo "<td>";
						$checkQuery	= mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM schueler WHERE (vname = '$vname' AND nname = '$nname' AND gebdatum = '$bday')");
						if($row3 = mysqli_fetch_assoc($checkQuery)) {
							echo "Schüler bereits angelegt! Überspringe diesen Schüler";
						} else {
							$InsertQuery = mysqli_query($GLOBALS["dbConnection"], "INSERT INTO schueler(vname,nname,gebdatum,geschlecht,klasse_id,klassenplus,anwesend)
														VALUES('$vname','$nname','$bday','$sex','$kID','$kPlus','1')");
							if($InsertQuery == true) {
								echo "importiert";
							} else {
								echo "Fehler!";
							}
						}
					echo "</td>";
				}

			} else {
				echo "<td></td><td>Klasse $ksNum/$kNum existiert noch nicht </td>";
			}
		} else {
			echo "<td></td><td>Klassenstufe $ksNum existiert noch nicht </td>";
		}
		echo "</tr>";
	} else {
												//OBERSTUFE
	}
}

echo "</table>";

?>
