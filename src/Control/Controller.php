<?php
namespace PIM;

abstract class Control_Controller
{
        private $route;
	
	abstract function execGet();
	abstract function execPost();

        public function __construct($r)
        {
                $this->route = $r;

		if (session_start() == FALSE) {
			throw new Exception("Could initialize the session");
		}
		session_cache_expire(30);
		/* This might cause problems for mozilla according to the PHP manual
		 * page */
		session_cache_limiter("private");
        }

	public function render()
	{
		switch ($_SERVER['REQUEST_METHOD']) {
		case "GET":
			$this->execGet();
			break;
		case "POST":
			$this->execPost();
			break;
		default:
			throw new \Exception('Can\'t deal with this method');
		}

	}

	protected function redirect($l)
	{
		header(sprintf('Location: %s', $l));
	}

	protected function setSectors($sectors)
	{	
		$_SESSION['sectors'] = $sectors;
	}
}

?>
