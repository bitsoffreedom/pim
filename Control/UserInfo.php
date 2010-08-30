<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/Form.php');
require_once (PIM_BASE_PATH . '/View/View.php');

class Control_UserInfo extends Control_Controller
{

	public function execGet()
	{
		return new View_UserInfo();
	}

	public function execPost()
	{
	}
}

?>
