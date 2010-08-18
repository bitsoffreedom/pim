<?php

require_once( PIM_BASE_PATH . '/protected/Session.php' );

abstract class Control_Controller
{
        private $route;

	/* HTTP response */
	private $status_line;
	private $location;
	private $buffer;

	abstract function execGet();
	abstract function execPost();

    public function __construct($r)
    {
        $this->route = $r;

        if (Session::get()->start() === false) {
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
		
		if (!empty($this->buffer))
			echo $this->buffer;
	}

	protected function setSectors($sectors)
	{	
		$_SESSION['sectors'] = $sectors;
	}

	protected function setStatusLine($l)
	{
		$this->status_line = $l;
	}
	
	protected function setLocation($l)
	{
		$this->location = $l;
	}
	
	protected function setBuffer($b)
	{
		$this->buffer = $b;
	}
}

?>
