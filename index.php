<?php 

session_start(); 
$DATA = $_SESSION['data'];

// language support 
if ( preg_match('/^\w{2}$/i',$_GET['lang'],$foo) ) { 
    $lang = $foo[0]; 
    if ( isset($DATA["lang"]) ) $DATA["lang"] = $lang;

} elseif ( isset($DATA["lang"]) ) { 
    $lang = $DATA["lang"]; 

} else { 
    $lang = 'nl'; 
}

// add more entry's for different languages 
if ( $lang == 'nl' ) { 
 setlocale(LC_ALL, 'nl_NL');
 date_default_timezone_set('CET');

} elseif  ( $lang == 'en' ) { 
 setlocale(LC_ALL, 'nl_NL');
 date_default_timezone_set('GMT');
} 


if ( !isset($_POST['submit']) or !isset($DATA) ) { // we must be new here

    // TODO language specific reading of data 
    require 'data.php'; // create a empty data array

    // defaults 
    $DATA["lang"]      = $lang;
    $DATA["fname"]     = "";
    $DATA["lname"]     = "";
    $DATA["street"]    = "";
    $DATA["pcode"]     = "";
    $DATA["place"]     = "";
    $DATA["get_out"]   = 1;
    $DATA["get_remove"]= 0;
    $DATA["get_info"]  = 1;
    $DATA["bof_logo"]  = 0;
 
    require $lang . '/header.php'; 
    require $lang . '/naw_form.php';
    require $lang . '/footer.php';

} elseif ($_POST['submit'] == 'Terug')  { // guess we want to edit the defaults again

    require $lang . '/header.php'; 
    require $lang . '/naw_form.php';
    require $lang . '/footer.php';

} elseif ($_POST['submit'] == 'PDF')  {     // redirect to a PDF 

   header("HTTP/1.1 307 Moved Temporary", TRUE, 307);
   header('Location: /pimpam/pdf.php');   // TODO: dynamic URL

} elseif ($_POST['submit'] == 'Volgende') { // generate / show the generated letters  

    $templatedir   = $lang  . '/templates'; 
    $bof           = file_get_contents("$templatedir/bof.txt");
    $aanhef        = file_get_contents("$templatedir/aanhef.txt");
    $inzage        = file_get_contents("$templatedir/inzage.txt");
    $uitschrijving = file_get_contents("$templatedir/uitschrijving.txt");
    $verwijdering  = file_get_contents("$templatedir/verwijder_mijn_gegevens.txt");
    $hoogachtend   = file_get_contents("$templatedir/hoogachtend.txt");

    $DATA["submit"]     = strip_tags( $_POST["submit"] );
    $DATA["fname"]      = strip_tags( $_POST["fname"] );
    $DATA["lname"]      = strip_tags( $_POST["lname"] );
    $DATA["street"]     = strip_tags( $_POST["street"] );
    $DATA["pcode"]      = strip_tags( $_POST["pcode"] );
    $DATA["place"]      = strip_tags( $_POST["place"] );
    $DATA["get_out"]    = strip_tags( $_POST["get_out"] );
    $DATA["get_remove"] = strip_tags( $_POST["get_remove"] );
    $DATA["get_info"]   = strip_tags( $_POST["get_info"] );
    $DATA["bof_logo"]   = strip_tags( $_POST["bof_logo"] );
 
    foreach ($_POST["organisations"] as $i => $value) {

      if ( isset ( $DATA[$value]['NAAM'] ) ) {
		$DATA[$value]['target']    = 1;
		$DATA[$value]['fname']     = $DATA["fname"];
		$DATA[$value]['lname']     = $DATA["lname"];
		$DATA[$value]['street']    = $DATA["street"];
		$DATA[$value]['pcode']     = $DATA["pcode"];
		$DATA[$value]['place']     = $DATA["place"];
		$DATA[$value]['get_out']   = $DATA["get_out"];
		$DATA[$value]['get_remove']= $DATA["get_remove"];
		$DATA[$value]['get_info']  = $DATA["get_info"];
		$DATA[$value]['bof_logo']  = $DATA["bof_logo"];
      }

    }

    $date = strftime("%A %e %B %Y");


    require $lang . '/header.php'; 
    require $lang . '/letter_form.php'; 
    require $lang . '/footer.php';

} else { // we're not suppose to be here!

    require $lang . '/header.php'; 
    print "<center><font color='red' size=5> Something went wrong .. pray to your local Deity, or try again </font></center>";
    print "<pre>" . print_r($_POST) . "</pre>";
    print "<pre>" . print_r($DATA) . "</pre>";
    require $lang . '/footer.php';
}

// save data in session
$_SESSION['data'] = $DATA;


?>
