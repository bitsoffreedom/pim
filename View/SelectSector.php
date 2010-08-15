<?php
namespace PIM;

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
