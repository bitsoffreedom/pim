<?php

require_once( PIM_BASE_PATH . '/Session.php' );

abstract class Controller
{
	// @var class Route
        protected $route;

	/* HTTP response */
	// @var string
	private $status_line;
	// @var string
	private $location;
	// @var string
	private $buffer;

	// @return string
	abstract function execGet();
	// @return string
	abstract function execPost();

	// @param Route r
	public function __construct($r)
	{
		$this->route = $r;

		if (Session::start() === false) {
			// "Could initialize the session"
			/* XXX: show what went wrong */
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			exit(0);
		}

		switch ($_SERVER['REQUEST_METHOD']) {
		case "GET":
			$this->status_line = "HTTP/1.1 200 OK";
			$this->buffer = $this->execGet();
			break;
		case "POST":
			$this->status_line = "HTTP/1.1 200 OK";
			$this->buffer = $this->execPost();
			break;
		default:
			$this->setStatusLine('HTTP/1.0 501 Not Implemented');
			$this->buffer = false;
		}
	}

	public function display()
	{
		header($this->status_line);
		if (!empty($this->location))
			header(sprintf('Location: %s', $this->location));

		// disable caching
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		
		if (!empty($this->buffer))
			echo $this->buffer;
	}

	// @param String $L
	protected function setStatusLine($l)
	{
		$this->status_line = $l;
	}

	// @param string $l
	protected function setLocation($l)
	{
		$this->location = $l;
	}

	// @param string $l
	protected function setBuffer($b)
	{
		$this->buffer = $b;
	}
}

?>
