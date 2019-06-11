<?php
	$ks_id	= $_GET["ksid"];

	$ks_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM klassenstufen WHERE id = $ks_id ORDER BY nummer");										//echo ALL disziplins
	if($row = mysqli_fetch_assoc($ks_query)) {
			$id			= $row["id"];
			$nummer		= $row["nummer"];

			$disziplin1	= $row["disziplin1"];
			$disziplin2	= $row["disziplin2"];
			$disziplin3	= $row["disziplin3"];
			$disziplin4	= $row["disziplin4"];

			for($for = 1;$for <= 4;$for++) {
				$disz_ueid 	= "disziplin$for";				//�bergangsvariable
				$disz_id	= $$disz_ueid;

				if ($disz_id == null) {
					continue;
				}

				$disz_query = mysqli_query($GLOBALS["dbConnection"], "SELECT * FROM disziplin WHERE id = '$disz_id'");
				while($row 	= mysqli_fetch_assoc($disz_query)) {

					$disz_bezeich	= $row["bezeichnung"];
					echo "<option value='$disz_id'>$disz_bezeich</option>";
				}
			}
	}
?>
