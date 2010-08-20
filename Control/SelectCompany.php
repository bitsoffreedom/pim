<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/View/View.php');

class Control_SelectCompany extends Control_Controller
{
	public function execGet()
	{
                return new View_SelectCompany();
	}

	public function execPost()
	{
	}
}

?>
