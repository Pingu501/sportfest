<form target="index.php" method="GET">
	<input type="hidden" value="overaged" name="con"></input>
	<fieldset>
		<input type="checkbox" name="overaged">Überalterte Schüler</input><br>
		<input type="checkbox" name="underaged">Unteralterte Schüler</input>
	</fieldset><br>
	Stichtag: <input type="number" name="stichTagDay" min="1" max="31" step="1" value="1" style="width: 40px;"></input> .
	<input type="number" name="stichTagMonth" min="1" max="12" step="1" value="6" style="width: 40px;"></input> .<br><br>
	
	<button type="submit">Anzeigen</button>
</form>