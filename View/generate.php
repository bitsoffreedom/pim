		<div id="content">
	<ul>
<? foreach ($companylist as $c) { ?>
		<li><a style="color: black" href="/pim/genereer/<?=$c->getId()?>">Genereer <?=$c->getName()?></a></li>
<? } ?>
	</ul>
		<div class="vorige"><a href="gegevens">VORIGE</a></div>
		</div>
