		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>
			
			<p>Welke sectoren wil je aanschrijven?</p>

<?  if (!empty($errormsg)) { ?>
                        <span><?=$errormsg?></span>
<? } ?>
                        <table>
                        <form action="sectoren" method="post">
<? foreach ($sectorlist as $s) { ?>
	<?
	if (in_array($s->id, $sel_sectorlist))
		$checked = "checked";
	else
		$checked = "";
	?>
                        <tr>
                                <td><?=$s->name?></td>
                                <td><input type="checkbox" name="sectoren[]" value="<?=$s->id?>" <?=$checked?> /></td>
                        </tr>
<? } ?>
                        <tr><td><input class="volgende" type="submit" value="VOLGENDE" /></td></tr>
                        </form>
                        </table>
		</div>
