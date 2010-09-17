		<div id="content">
		<h1>Vul je gegevens in</h1>
<? if ($errmsg) { ?>
		<p><?=$errmsg?></p>
<? } ?>
		<form action="gegevens" method="post">
		<p><label>Voornaam: <input type="text" name="voornaam" value="<?=$firstname_val?>" /></label></p>
		<p><label>Achternaam: <input type="text" name="achternaam" value="<?=$lastname_val?>"/></label></p>
<? foreach ($extras as $e) {
	if (isset($extras_val) && array_key_exists($e->getId(), $extras_val))
		$e_val = $extras_val[$e->getId()];
	else
		$e_val = "";
?>
		<p><label><?=$e->getValue()?><input type="text" name="<?=$e->getId()?>" value="<?=$e_val?>" /></label></p>
<? } ?>
		<div class="vorige"><a href="bedrijven">VORIGE</a></div>
		<input class="volgende" type="submit" value="VOLGENDE" />
		</form>
		</div>
