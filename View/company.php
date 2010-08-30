		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>
			
			<p>Welke bedrijven wil je aanschrijven?</p>

			<div style="height: 100px; width: 400px;">

			<form action="bedrijven" method="post">
			<div style="float: left">
				<input type="text" name="naam" /> <br />
				<input type="hidden" name="zoekform" value="0" />
			</div>

			<div style="float: right">
<? foreach ($sectorlist as $s) { ?>
	<?
	if (in_array($s->getId(), $sel_sectorlist))
		$checked = "checked";
	else
		$checked = "";
	?>
				<input type="checkbox" name="sectoren[]" value="<?=$s->getId()?>" <?=$checked?> /><?=$s->getName()?><br />
<? } ?>
			</div>

				<input type="submit" value="Zoeken" />
			</form>
			</div>

			<div>

                        <form action="bedrijven" method="post">
<? if (empty($companylist)) { ?>
			<p>Niks gevonden</p>
<? } else { ?>

                        <table>
<?	foreach ($companylist as $c) { ?>
                        <tr>
                                <td><?=$c->getName()?></td>
                                <td><?=$c->getDepartment()?></td>
                                <td><?=$c->getWeb()?></td>
                                <td><?=$c->getEmail()?></td>
                                <td><input type="checkbox" name="bedrijven[]" value="<?=$c->getId()?>" /></td>
                        </tr>
<?	} ?>
                        <tr><td><input type="submit" name="btn1" value="Voeg toe"/></td></tr>
                        </table>
<? } ?>
			
			<input type="hidden" name="lijstform" value="0" />
			<div class="vorige"><a href="sector">VORIGE</a></div>
			<input class="volgende" type="submit" name="btn2" value="VOLGENDE" />
			</div>
                        </form>
		</div>
