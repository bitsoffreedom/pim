<?php

require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/View/SelectSectorWidget.php');
require_once (PIM_BASE_PATH . '/View/TopWidget.php');

class View_SelectSector extends View_View
{
	private $category_list;

	public function __construct($c, $e = false)
	{
		$sw = new View_SelectSectorWidget();
		$sw->setCategoryList($c);
		if (!empty($e))
			$sw->setErrorMsg($e);
		$sw->render();

		$tw = new View_TopWidget();
		$tw->setBody($sw);
		$tw->render();

		$this->setBuffer((string)$tw);
	}
}

?>
