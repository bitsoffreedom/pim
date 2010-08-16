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

if (version_compare( PHP_VERSION, PHP_MIN_VERSION ) < 0 ) {
	/* XXX: show what went wrong */
	header('HTTP/1.0 500 Internal Server Error');
	exit(0);
}

$r = new Control_Route();
$pageclass = $r->getPageClass();

$p = new $pageclass($r);
$p->display();

?>
