<script type="text/javascript">
	function ks_query() {
		var xmlhttp;
		if (window.XMLHttpRequest) {
		    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
			xmlhttp = new XMLHttpRequest();
		} else {
		    // AJAX mit IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("klassenstufe").innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","xmlhttp_index.php?con=ks_query",true);
		xmlhttp.send();
	}
	function ks_select (ks_id){
		function klasse (ks_id) {
			var xmlhttp;
			if (window.XMLHttpRequest) {
			    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
				xmlhttp = new XMLHttpRequest();
			} else {
			    // AJAX mit IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("klasse").innerHTML=xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET","xmlhttp_index.php?con=k_query&ksid="+ks_id,true);
			xmlhttp.send();
		}

		function disziplin (ks_id) {
			var xmlhttp;
			if (window.XMLHttpRequest) {
			    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
				xmlhttp = new XMLHttpRequest();
			} else {
			    // AJAX mit IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("disziplin").innerHTML=xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET","xmlhttp_index.php?con=disziplin_query&ksid="+ks_id,true);
			xmlhttp.send();
			}

		klasse(ks_id);
		disziplin(ks_id);
	}
	function lastwerte (wertIn, KsID, DiszID, StudentNumber, Sex, Ordering) {
		var wert = wertIn.replace(",",".");
		var oldStudentNumber = StudentNumber+"-last";				//id der Input types
		var everStudentNumber = StudentNumber+"-best";
		var pointsStudentNumber = StudentNumber+"-points";

		var OldValue = document.getElementById(everStudentNumber).value;	//bestwert
		document.getElementById(oldStudentNumber).value=wert;

		Sex	= (Sex == "0") ? "M" : "W";

		if(Ordering == "0") {
			if(wert < OldValue || OldValue == "") {
				document.getElementById(everStudentNumber).value=wert;
				var xmlhttp;
				if (window.XMLHttpRequest) {
				    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
					xmlhttp = new XMLHttpRequest();
				} else {
				    // AJAX mit IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200) {
						document.getElementById(pointsStudentNumber).value=xmlhttp.responseText;
						//document.write(xmlhttp.responseText);
					}
				}
				xmlhttp.open("GET","xmlhttp_index.php?calcPoints=jo&ksid="+KsID+"&diszid="+DiszID+"&wert="+wert+"&sex="+Sex,true);
				xmlhttp.send();
			}
		} else {
				if(wert > OldValue) {
				document.getElementById(everStudentNumber).value=wert;
				var xmlhttp;
				if (window.XMLHttpRequest) {
				    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
					xmlhttp = new XMLHttpRequest();
				} else {
				    // AJAX mit IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200) {
						document.getElementById(pointsStudentNumber).value=xmlhttp.responseText;
						//document.write(xmlhttp.responseText);
					}
				}
				xmlhttp.open("GET","xmlhttp_index.php?calcPoints=jo&ksid="+KsID+"&diszid="+DiszID+"&wert="+wert+"&sex="+Sex,true);
				xmlhttp.send();
			}
		}
	}

	function delRow(RowNumber, StudentID,diszID) {
		document.getElementById(RowNumber+"-last").value = "";
		document.getElementById(RowNumber+"-best").value = "";
		document.getElementById(RowNumber+"-points").value = "";

		var xmlhttp;
		if (window.XMLHttpRequest) {
		    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
			xmlhttp = new XMLHttpRequest();
		} else {
		    // AJAX mit IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				alert(xmlhttp.responseText);
				//document.write(xmlhttp.responseText);
			}
		}
		xmlhttp.open("GET","xmlhttp_index.php?con=delRow&studentid="+StudentID+"&diszID="+diszID,true);
		xmlhttp.send();
	}

	function nextTD(e, input) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			input++;
			var newFocusElement	= "input"+input;
			document.getElementById(newFocusElement).focus();
			document.getElementById(newFocusElement).activeElement();
		}
	}

</script>
<?php
	if(isset($_GET["sub"]) && $_GET["sub"] == "auswertung") {
		$maxNumber	= $_POST["number"];
		$disz_id	= $_POST["disz_id"];
		$newIn		= 0;
		$newUpdate	= 0;
		for($a = 1; $a <= $maxNumber; $a++) {
			$stud_id	= $_POST["$a"];
			$best_value	= $_POST["$a-best"];
			if($best_value != "") {
				$controllQuery = mysqli_query($GLOBALS["dbConnection"], "SELECT id FROM werte WHERE schueler_id = $stud_id AND disziplin_id = $disz_id");
				if($row = mysqli_fetch_assoc($controllQuery)) {
					$lastID	= $row["id"];
					mysqli_query($GLOBALS["dbConnection"], "UPDATE werte SET wert = $best_value WHERE id = $lastID");
					$newUpdate++;
				} else {
					mysqli_query($GLOBALS["dbConnection"], "INSERT INTO werte (schueler_id, disziplin_id, wert) VALUES ('$stud_id','$disz_id','$best_value')");
					$newIn++;
				}
			}

		}
		echo "Neu: $newIn <br>Aktualisiet: $newUpdate <br>";
	}
?>
<body onload="ks_query()">
	Werte werden automatisch aktualisiert!
	<form action="index.php" method="GET">
		<table>
			<tr>
				<td>
					<div>
						<select id="klassenstufe" name='ks' size='6' value="" onchange='ks_select(this.value)' />

						</select>
					</div>
				</td>
				<td>
					<div>
						<select id="klasse" name='klasse' size='6' value="" />

						</select>
					</div>
				</td>
				<td>
					<div>
						<select id="disziplin" name='disz' size='6' value="" />

						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<input type="hidden" name="con" value="selection" />
					<input type="submit" value="Los gehts!"/>
					<div id="divFORtable">
					</div>
				</td>
			</tr>
		</table>
	</form>
	<?php
		if(isset($_GET["klasse"])) {
			require 'querys/table.php';
		}
	?>
</body>
