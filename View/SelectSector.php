<?php

require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/View/SelectSectorWidget.php');
require_once (PIM_BASE_PATH . '/View/TopWidget.php');

class View_SelectSector extends View_View
{
	public function __construct()
	{
		$sw = new View_SelectSectorWidget();
		$sw->render();

		$tw = new View_TopWidget();
		$tw->setBody($sw);
		$tw->render();

		$this->setBuffer((string)$tw);
	}
}

?>
