		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>
			
			<p>Welke sectoren wil je aanschrijven?</p>

<?  if (!empty($errormsg)) { ?>
                        <span><?=$errormsg?></span>
<? } ?>
                        <table>
                        <form action="sectoren" method="post">
<? foreach ($sectorlist as $c) { ?>
                        <tr>
                                <td><?=$c->getName()?></td>
                                <td><input type="checkbox" name="sectoren[]" value="<?=$c->getId()?>" /></td>
                        </tr>
<? } ?>
                        <tr><td><input class="volgende" type="submit" value="VOLGENDE" /></td></tr>
                        </form>
                        </table>
		</div>
