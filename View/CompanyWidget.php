<?php

class View_CompanyWidget extends View_Widget
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
