<?php
require_once( 'autoload.php' );

use PIM\Control_Exceptions_InvalidVersionException as InvalidVersionException;

define( 'PIM_CONFIG_FILE', PIM_BASE_PATH . DIRECTORY_SEPARATOR . 'pim.conf' );
define( 'PHP_MIN_VERSION', '5.3.0' );
define( 'DEBUG', 1);

try {
	if ( version_compare( PHP_VERSION, PHP_MIN_VERSION ) < 0 ) {
	    throw new InvalidVersionException( 'PIM needs at least PHP version ' . PHP_MIN_VERSION . ', you have ' . PHP_VERSION );
	}

	$r = new PIM\Control_Route();
	$pageclass = $r->getPageClass();

	$pageclass = "PIM\\" . $pageclass;
	$p = new $pageclass($r);
	$p->render();
} catch (InvalidVersionException $e) {
	if (DEBUG) {
		echo 'Exception: ' . $e->getMessage() . "\n";
		die();
	}
	/* XXX: Give HTTP error 500 back with some message */
} catch (Exception $e) {
	if (DEBUG) {
		echo 'Exception: ' . $e->getMessage() . "\n";
		die();
	}
	/* XXX: Give HTTP error 500 back with some message */
}

/*
$address = new PIM\Model_Address();
$address->setStreet( 'Bleekstraat' );
$address->setPostalCode( '1000AA' );
$address->setHouseNumber( 1 );
$address->setCity( 'Amsterdam' );
var_dump( $address->insert() );
echo PHP_EOL;
*/

/*
$address = PIM\Model_Address::findById( 1 );
var_dump( $address );
echo PHP_EOL;

$address->setHouseNumber( 31 );
$address->update();
$address = PIM\Model_Address::findById( 1 );
var_dump( $address );
*/
