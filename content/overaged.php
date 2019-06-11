<?php
date_default_timezone_set('Europe/Berlin');
$thisYear	= date("Y"); //Bsp: Schuljahr 2016/17 muss hier 2017 stehen; Falls Sportfest vor Neujahr (1. Halbjahr) --> Dann 2016

echo("<b>Eingestelltes Jahr </b>(sollte das Jahr des 2. Schulhalbjahres sein!)<b>: </b>" . $thisYear);
echo("<br><b>Stichtag für Einschulung: </b>" . $_GET['stichTagDay'] . "." . $_GET['stichTagMonth'] . ".<br><br>");

require_once 'excel_reader2.php';

$data = new Spreadsheet_Excel_Reader("Sportfest-Schuelerdaten.xls");
$rows	= $data->rowcount($sheet_index=0);
$cols	= $data->colcount($sheet_index=0);

echo("<table>");

echo("<tr>");
	echo("<th>" . "Nachname" . "</th>");
	echo("<th>" . "Vorname" . "</th>");
	echo("<th>" . "Geburtstag" . "</th>");
	echo("<th>" . "Klasse" . "</th>");
	echo("<th>" . "Klassenabweichung" . "</th>");
echo("</tr>");

for($i = 1; $i <= $rows; $i++) {
	$nname		= utf8_encode($data->val($i,1));
	$vname		= utf8_encode($data->val($i,2));
	$bday		= utf8_encode($data->val($i,4));
	$klasse		= utf8_encode($data->val($i,5));

	$klassenstufeEXP = explode('/', $klasse);

	$klassenstufe = $klassenstufeEXP[0];


	if (isset($_GET['underaged'])) {
		if ($_GET['underaged'] = 'on') {
			$jmax = 3;
		}
	} else {
		$jmax = 0;
	}

	if (isset($_GET['overaged'])) {
		if ($_GET['overaged'] = 'on') {
			$jmin = -3;
		}
	} else {
		$jmin = 0;
	}

	for ($j = $jmin; $j <= $jmax; $j++) {
		$minDate = strtotime(($thisYear - $klassenstufe - 7 + $j) . '-' . $_GET['stichTagMonth'] . '-' . $_GET['stichTagDay']); //YYYY-MM-DD (Iso Standard)
		$maxDate = strtotime('+1 Year', $minDate);
		$bdayDate = strtotime($bday);

		if ($bdayDate >= $minDate && $bdayDate < $maxDate && $j != 0) {
			echo("<tr>");
				echo("<td>" . $nname . "</td>");
				echo("<td>" . $vname . "</td>");
				echo("<td>" . date("d.m.Y", $bdayDate) . "</td>");
				echo("<td>" . $klasse . "</td>");
				echo("<th>" . $j . "</th>");
			echo("</tr>");
		}
	}
}

echo("</table>");

?>
