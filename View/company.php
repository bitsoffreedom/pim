		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>
			
			<p>Welke bedrijven wil je aanschrijven?</p>

			<div style="height: 100px; width: 400px;">

			<form action="bedrijven" method="post">
			<div style="float: left">
				<input type="text" name="naam" /> <br />
			</div>

			<div style="float: right">
<? foreach ($sectorlist as $s) { ?>
				<input type="checkbox" name="sectoren[]" value="<?=$s->getId()?>" /><?=$s->getName()?><br />
<? } ?>
			</div>

				<input type="submit" value="Zoeken" />
			</form>
			</div>

			<div>

<? if (empty($companylist)) { ?>
			<p>Niks gevonden</p>
<? } else { ?>

                        <table>
                        <form action="bedrijven" method="post">
<?	foreach ($companylist as $c) { ?>
                        <tr>
                                <td><?=$c->getName()?></td>
                                <td><?=$c->getDepartment()?></td>
                                <td><?=$c->getWeb()?></td>
                                <td><?=$c->getEmail()?></td>
                                <td><input type="checkbox" name="bedrijven[]" value="<?=$c->getId()?>" /></td>
                        </tr>
<?	} ?>
                        <tr><td><input type="submit" value="Voeg toe"/></td></tr>
                        </form>
                        </table>
<? } ?>
			
			<div class="vorige">VORIGE</div>
			<div class="volgende">VOLGENDE</div>
			</div>
		</div>
