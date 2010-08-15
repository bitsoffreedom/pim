<?php

require_once (PIM_BASE_PATH . '/View/Widget.php');

class View_SelectSectorWidget extends View_Widget
{
	public function __construct()
	{
		$this->setViewFile("sector.php");
	}

	public function render()
	{
		$this->renderInternal();
	}
}

?>
