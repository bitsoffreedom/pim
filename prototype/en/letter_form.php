<?php 

foreach ($DATA as $key => $value) {
  if ( is_int($key) and isset ( $DATA[$key]['target'] ) and $DATA[$key]['target'] == 1 ) {

echo '
<img id="top" src="top.png" alt="">
<div id="form_container">

<h1><a>Brief naar : ' . $DATA[$key]['NAAM'] . '</a></h1>
<form id="form_211376" class="appnitro"  method="post" action="' . $PHP_SELF . '">
<div class="form_description">
<h2>Brief: ' . $DATA[$key]['NAAM'] . '</h2>
<p></p>
</div>
';
	$DATA[$key]['brief'] = ''; 
	if ( $DATA[$key]['fname'] or $DATA[$key]['lname'] ) 
		$DATA[$key]['brief'] .= '<br>' . $DATA[$key]['fname'] . ' ' . $DATA[$key]['lname'] . "\n"; 

	if ( $DATA[$key]['street'] ) 
		$DATA[$key]['brief'] .= '<br>' . $DATA[$key]['street'] . "\n"; 

	if ( $DATA[$key]['pcode'] or $DATA[$key]['place']) 
		$DATA[$key]['brief'] .= '<br>' . $DATA[$key]['pcode'] . ' ' . $DATA[$key]['place'] . "\n"; 
	
	$DATA[$key]['brief'] .= "<br>\n";
	
	if ( $DATA[$key]['NAAM'] ) 
		$DATA[$key]['brief'] .= '<br>' . $DATA[$key]['NAAM'] . "\n"; 

	if ( $DATA[$key]['CONTACT'] ) 
		$DATA[$key]['brief'] .= '<br>For: ' . $DATA[$key]['CONTACT'] . "\n"; 
	
	if ( $DATA[$key]['STRAAT'] )  
		$DATA[$key]['brief'] .= '<br>' . $DATA[$key]['STRAAT'] . "\n"; 
	
	if ( $DATA[$key]['POSTCODE'] or $DATA[$key]['PLAATS'])  
		$DATA[$key]['brief'] .= '<br>' . $DATA[$key]['POSTCODE'] . ' ' . $DATA[$key]['PLAATS'] . "\n"; 

	$DATA[$key]['brief'] .= '<center>' . $DATA[$key]['place'] . "," . $date . '</center><br>';
	$DATA[$key]['brief'] .= '<br>' . $aanhef . "\n"; 
	$DATA[$key]['brief'] .= '<br>' . $aanleiding . "\n"; 
	
	if ( $DATA[$key]['get_info'] )   $DATA[$key]['brief'] .= '<br><br>' . $inzage . "\n"; 
	if ( $DATA[$key]['get_out'] )    $DATA[$key]['brief'] .= '<br><br>' . $uitschrijving . "\n"; 
	if ( $DATA[$key]['get_remove'] ) $DATA[$key]['brief'] .= '<br><br>' . $verwijdering . "\n"; 
	
	$DATA[$key]['brief'] .= '<br><br>' . $hoogachtend . "\n<br><br>". $DATA[$key]['place']; 
	
	if ( $DATA[$key]['boflogo'] ) 
		$DATA[$key]['brief']  .= $bof; 

	print $DATA[$key]['brief'];

	echo '<li class="buttons">
	<input id="element_5" name="brief[]"   type="hidden" maxlength="2048" value="' . $DATA[$key]['brief']   . '"> 
	<input id="element_5" name="pdflogo[]" type="hidden" maxlength="512"  value="' . $DATA[$key]['pdflogo'] . '"> 
	<input type="hidden" name="form_id" value="211375" />
        <input id="saveForm" class="button_text" type="submit" name="submit" value="PDF" />
	<input id="saveForm" class="button_text" type="submit" name="submit" value="Terug" />
	</li>
	</ul>
	</form>	
	</div>
	<img id="bottom" src="bottom.png" alt="">';
  } // end if
} // end foreach
?>
