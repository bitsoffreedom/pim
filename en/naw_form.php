<img id="top" src="top.png" alt="">
<div id="form_container">
<h1><a>De BOF pim,pam,pet </a></h1>
<form id="form_211375" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<div class="form_description">
<h2>The BOF pim,pam,pet </h2>
<p>We need your data to generate the letters. These are only kept while this session lasts, and never stored permanently. TODO: MORE ENGLISCHE TEKSTS <a href="http://wiki.bof.nl/wiki/ervaringen">ervaringen</a>. Voor een gids die je uitlegt wat je zelf kan doen om je privacy te verbeteren, zie onze <a href="http://wiki.bof.nl/wiki/Zelfverdedigingsgids" >Zelfverdedigingsgids</a> </p>
</div>						
<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Surname</label>
		<div>
			<input id="element_1" name="fname" class="element text medium" type="text" maxlength="255" value="<?php echo $DATA['fname'] ?>"> 
		</div><p class="guidelines" id="guide_1"><small>Geef je voornaam op</small></p> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Lastname</label>
		<div>
			<input id="element_2" name="lname" class="element text medium" type="text" maxlength="255" value="<?php echo $DATA['lname'] ?>"> 
		</div><p class="guidelines" id="guide_2"><small>Geef je achternaam op</small></p> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Straatnaam met nummer en mogelijke extensie </label>
		<div>
			<input id="element_3" name="street" class="element text medium" type="text" maxlength="255" value="<?php echo $DATA['street'] ?>"> 
		</div><p class="guidelines" id="guide_3"><small>Iets wat TNT kan bereiken</small></p> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Postcode </label>
		<div>
			<input id="element_4" name="pcode" class="element text medium" type="text" maxlength="255" value="<?php echo $DATA['pcode'] ?>"> 
		</div><p class="guidelines" id="guide_4"><small>Postcode .. je weet wel .. manier van snailmail routing info</small></p> 
		</li>		<li id="li_5" >
		<label class="description" for="element_5">Plaatnaam </label>
		<div>
			<input id="element_5" name="place" class="element text medium" type="text" maxlength="255" value="<?php echo $DATA['place'] ?>"> 
		</div><p class="guidelines" id="guide_5"><small>Plaatsnaam, en optioneel het land als het niet Nederland is</small></p> 
		</li>		<li id="li_6" >
		<label class="description" for="element_6">Welke organisaties wil je aanschrijven? </label>
		<span>

<?php 
    foreach ($DATA as $i => $value) {
	if ( is_int($i) and isset ( $DATA[$i]['NAAM'] ) ) { 
		if ( $DATA[$i]['target'] == 1) { 
			$check = 'checked'; 
 		} else { 
			$check = ''; 
		}
		echo '<input id="element_6_1" name="organisations[]" class="element checkbox" type="checkbox" value="' . $i .'" ' . $check . '>
<label class="choice" for="element_6_1">' . $DATA[$i]['NAAM'] . '</label>';
        }

    } 
?>

		</span><p class="guidelines" id="guide_6"><small>Zet een vinkje voor elke organisatie waarvoor je een brief wilt genereren. Je kan later meer details opgeven.</small></p> 
		</li>		<li id="li_7" >
		<label class="description" for="element_7">Standaard opties per brief </label>
		<span>
<input id="element_7_1" name="get_info" class="element checkbox" type="checkbox" value="1" <?php if ($DATA['get_info']) echo "checked" ?> >
<label class="choice" for="element_7_1">Verzoek inzage</label>
<input id="element_7_2" name="get_out" class="element checkbox" type="checkbox" value="1" <?php if ($DATA['get_out']) echo "checked" ?> >
<label class="choice" for="element_7_2">Verzoek uitschrijving van mailings</label>
<input id="element_7_3" name="get_remove" class="element checkbox" type="checkbox" value="1" <?php if ($DATA['get_remove']) echo "checked" ?> >
<label class="choice" for="element_7_3">Verzoek verwijdering van gegevens</label>
<input id="element_7_4" name="bof_logo" class="element checkbox" type="checkbox" value="1" <?php if ($DATA['bof_logo']) echo "checked" ?> >
<label class="choice" for="element_7_4">Vermeld op de brief dat ie gegenereerd is door de BOF PIM</label>

		</span><p class="guidelines" id="guide_7"><small>Geef hier op wat de standaard opties zijn per brief. Je kan deze later per brief aanpassen.</small></p> 
		</li>
				<li class="buttons">
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Volgende" />
		</li>
			</ul>
		</form>	
	</div>
