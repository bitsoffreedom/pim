<?php

define( 'PIM_BASE_PATH', dirname( __FILE__ ) );
define( 'PIM_CONFIG_FILE', PIM_BASE_PATH . DIRECTORY_SEPARATOR . 'pim.conf' );
define( 'PHP_MIN_VERSION', '5.3.0' );
define( 'DEBUG', 1);

require_once ('Control/SelectSector.php');
require_once ('Control/SelectCompany.php');
require_once ('Control/Route.php');

require_once ('Control/Exceptions/NullPointerException.php');
require_once ('Control/Exceptions/IOException.php');
require_once ('Control/Exceptions/InvalidVersionException.php');

try {
	if ( version_compare( PHP_VERSION, PHP_MIN_VERSION ) < 0 ) {
	    throw new InvalidVersionException( 'PIM needs at least PHP version ' . PHP_MIN_VERSION . ', you have ' . PHP_VERSION );
	}

	$r = new Control_Route();
	$pageclass = $r->getPageClass();

	$p = new $pageclass($r);
	$p->render();
} catch (Control_Exceptions_InvalidVersionException $e) {
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
