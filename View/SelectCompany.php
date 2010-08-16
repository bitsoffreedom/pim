<?php

require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/View/CompanyWidget.php');
require_once (PIM_BASE_PATH . '/View/TopWidget.php');

class View_SelectCompany extends View_View
{
	private $category_list;

	public function __construct()
	{
		$cw = new View_CompanyWidget();
		$cw->render();

		$tw = new View_TopWidget();
		$tw->setBody($cw);
		$tw->render();

		$this->setBuffer((string)$tw);
	}
}

?>
