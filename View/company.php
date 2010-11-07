		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>

			<div id="facets">

<? if (!empty($sel_sector_list)) { ?>
			<h2>Filters</h2>
			<ul>
	<? foreach ($sel_sector_list as $s) { ?>
				<li><?=$s->name?> <a href="sector_verwijderen/<?=$s->id?>">(Verwijder)</a></li>
	<? } ?>
			</ul>
<? } ?>
<?
if (!empty($not_sel_sector_list)) {
?>
			<h2>Per sector</h2>
			<ul>
			<?  foreach ($not_sel_sector_list as $s) { ?>
			<li><a href="sector_toevoegen/<?=$s->id?>"><?=$s->name?></a></li>
			<? } ?>
			</ul>
<? } ?>
			</div>
			
			<p>Welke bedrijven wil je aanschrijven?</p>

			<div style="height: 100px; width: 400px;">

			<form action="bedrijven" method="post">
			<div style="float: left">
				<input type="text" name="naam" /> <br />
				<input type="hidden" name="zoekform" value="0" />
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
                                <td><?=$c->name?></td>
                                <td><?=$c->department?></td>
                                <td><?=$c->web?></td>
                                <td><?=$c->email?></td>
                                <td><input type="checkbox" name="bedrijven[]" value="<?=$c->id?>" /></td>
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
