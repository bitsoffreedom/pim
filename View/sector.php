	<div id="wizard">
		<div id="wiz_nav">
			<div id="grrbalk"></div>
			<span id="bucket">Mijn lijstje</span>
			<span id="arrow1"><h3>1</h3> Wie wil je aanschrijven?</span>
			<span id="arrow2"><h3>2</h3> Vul je gegevens in</span>
			<span id="arrow3"><h3>3</h3> Maak de brieven</span>
		</div>
		
		<div id="content">
			<h1>1. Wie wil je aanschrijven?</h1>
			
			<p>Welke sectoren wil je aanschrijven?</p>
<?php
	if (!empty($errormsg)) {
		printf("<span>%s</span>", $errormsg);
	}
?>

			<form action="sectoren" method="post">
			<ul>
<?php
	foreach ($categorylist as $c) {
		printf("<li>a: %s</li>", $c->getName());
		printf("<input type=\"checkbox\" name=\"sectoren[]\" value=\"%d\" />", $c->getName(), $c->getId());
	}
?>
				<input type="submit" />
			</ul>
			</form>
			
			
			<div class="vorige">VORIGE</div>
			<div class="volgende">VOLGENDE</div>
		</div>
		
		<div id="wizard_bottom"></div>
	</div>
