<?php

define( 'PIM_BASE_PATH', dirname( __FILE__ ) );
define( 'PIM_CONFIG_FILE', PIM_BASE_PATH . DIRECTORY_SEPARATOR . 'pim.conf' );
define( 'PHP_MIN_VERSION', '5.3.0' );
define( 'DEBUG', 1);

require_once ('Model/Persistable.php');
require_once ('Model/Address.php');
require_once ('Model/Location.php');
require_once ('Model/Category.php');
require_once ('Model/User.php');
require_once ('Model/Datahamster.php');
require_once ('Model/DatahamsterExtra.php');
require_once ('Model/Userdata.php');
require_once ('Control/Controller.php');
require_once ('Control/Form.php');
require_once ('Control/SelectSectorForm.php');
require_once ('Control/Exceptions/NullPointerException.php');
require_once ('Control/Exceptions/IOException.php');
require_once ('Control/Exceptions/InvalidVersionException.php');
require_once ('Control/SelectSector.php');
require_once ('Control/Route.php');
require_once ('Control/SelectCompany.php');
require_once ('View/View.php');
require_once ('View/Widget.php');
require_once ('View/SelectSector.php');
require_once ('View/TopWidget.php');
require_once ('View/SelectSectorWidget.php');
require_once ('View/CompanyWidget.php');

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
