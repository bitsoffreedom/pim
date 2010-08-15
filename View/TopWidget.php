<?php

require_once (PIM_BASE_PATH . '/View/Widget.php');

class View_TopWidget extends View_Widget
{
	private $body;

	public function __construct()
	{
		$this->setViewFile("top.php");
	}

	public function setBody($body)
	{
		$this->body = $body;
	}

	public function render()
	{
		$this->renderInternal(Array("body" => $this->body));
	}
}

?>
