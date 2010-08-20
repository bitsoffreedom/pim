<?php

require_once (PIM_BASE_PATH . '/View/Widget.php');

abstract class View
{
	// @var string
	private $buffer = NULL;

	// @param string $b
	protected function setBuffer($b)
	{
		$this->buffer = $b;
	}

	public function __toString()
	{
		return $this->buffer;
	}
}

class View_SelectCompany extends View
{
	// @var array
	private $category_list;

	public function __construct()
	{
		$cw = new CompanyWidget();
		$cw->render();

		$tw = new TopWidget();
		$tw->setBody($cw);
		$tw->render();

		$this->setBuffer((string)$tw);
	}
}

class View_SelectSector extends View
{
	// @var array
	private $category_list;

	public function __construct($c, $e = false)
	{
		$sw = new SelectSectorWidget();
		$sw->setCategoryList($c);
		if (!empty($e))
			$sw->setErrorMsg($e);
		$sw->render();

		$tw = new TopWidget();
		$tw->setBody($sw);
		$tw->render();

		$this->setBuffer((string)$tw);
	}
}

?>
