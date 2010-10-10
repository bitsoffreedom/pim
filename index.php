<?php

define( 'PIM_BASE_PATH', dirname( __FILE__ ) );
define( 'PIM_CONFIG_FILE', PIM_BASE_PATH . DIRECTORY_SEPARATOR . 'pim.conf' );
define( 'PHP_MIN_VERSION', '5.3.0' );
define( 'DEBUG', 1);

require_once ('Controller.php');
require_once ('Session.php');
require_once ('Route.php');

if (version_compare( PHP_VERSION, PHP_MIN_VERSION ) < 0 ) {
	/* XXX: show what went wrong */
	header('HTTP/1.0 500 Internal Server Error');
	exit(0);
}

$r = new Route();
$pageclass = $r->getPageClass();

$p = new $pageclass($r);
$p->display();

?>
