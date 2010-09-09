		<div id="content">
		<h1>Vul je gegevens in</h1>
<? if ($errmsg) { ?>
		<p><?=$errmsg?></p>
<? } ?>
		<form action="gegevens" method="post">
		<p><label>Voornaam: <input type="text" name="voornaam" /></label></p>
		<p><label>Achternaam: <input type="text" name="achternaam" /></label></p>
<? foreach ($extras as $e) { ?>
		<p><label><?=$e->getValue()?><input type="text" name="<?=$e->getValue()?>" /></label></p>
<? } ?>
		<div class="vorige"><a href="bedrijven">VORIGE</a></div>
		<input class="volgende" type="submit" value="VOLGENDE" />
		</form>
		</div>
