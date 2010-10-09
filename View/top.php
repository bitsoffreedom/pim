<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
	<title>Privacy Inzage Machine (PIM) - Wie weet wat van mij?</title> 
	<link rel="stylesheet" href="static/style.css">
</head>
<body>

<div id="container">

	<ul class="nav">
		<li><a href="#over">OVER</a></li>
		<li><a href="#contact">CONTACT</a></li>
		<li><a href="#pers">PERS</a></li>
		<li><a href="#privacy">PRIVACY</a></li>
	</ul>
	
	<div id="logo"><h1>Privacy Assistent - Privacy Inzage Machine</h1></div>
        <div id="wizard">
                <div id="wiz_nav">
                        <div id="grrbalk"></div>
                        <span id="bucket">Mijn lijstje</span>
                        <span id="arrow1"><h3>1</h3> Wie wil je aanschrijven?</span>
                        <span id="arrow2"><h3>2</h3> Vul je gegevens in</span>
                        <span id="arrow3"><h3>3</h3> Maak de brieven</span>
                </div>
                <div style="left: 30px; float: left">
                        <ul>
<? foreach ($companies as $company) { ?>
                        <li><?=$company->name?></li>
<? } ?>
                        </ul>
                </div>
<?=$body?>
                <div id="wizard_bottom"></div>
        </div>

	<div id="footer">
		&copy; BitsOfFreedom, alle rechten voorbehouden. PIM is open source. Word <a href="#vriend">vriend</a>, <a href="#domee">doe mee</a> of <a href="#doneer">doneer</a>
		
		<div></div>
	</div>

</div>
</body>
</html>
