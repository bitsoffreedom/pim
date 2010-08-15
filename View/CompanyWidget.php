<?php

require_once (PIM_BASE_PATH . '/View/Widget.php');

class View_StartWidget extends View_Widget
{
	public function __construct()
	{
		$this->setViewFile("company.php");
	}

	public function render()
	{
		$this->renderInternal();
	}
}

?>
