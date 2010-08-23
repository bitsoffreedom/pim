		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>
			
			<p>Welke sectoren wil je aanschrijven?</p>

<?  if (!empty($errormsg)) { ?>
                        <span><?=$errormsg?></span>
<? } ?>
                        <table>
                        <form action="sectoren" method="post">
<? foreach ($categorylist as $c) { ?>
                        <tr>
                                <td><?=$c->getName()?></td>
                                <td><input type="checkbox" name="sectoren[]" value="<?=$c->getId()?>" /></td>
                        </tr>
<? } ?>
                        <tr><td><input style="position: absolute; top: 325px; left: 10px; border: 0px; font: 12px Arial, Helvetica, sans-serif, courier; font-size: 10px; color: #D2CCC5; width: 102px; height: 31px; background-image: url('http://localhost/pim/static/volgende.png')" type="submit" value="VOLGENDE" /></td></tr>
                        </form>
                        </table>

			
			
		</div>
