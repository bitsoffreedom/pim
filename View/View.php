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
	public function __construct($c, $s, $sel)
	{
		$cw = new CompanyWidget();
		$cw->setCompanyList($c);
		$cw->setSectorList($s);
		$cw->render();

		$tw = new TopWidget();
		$tw->setBody($cw);
		$tw->setCompanySelection($sel);
		$tw->render();

		$this->setBuffer((string)$tw);
	}
}

class View_SelectSector extends View
{
	// @var array
	private $sector_list;

	public function __construct($c, $e = false)
	{
		$sw = new SelectSectorWidget();
		$sw->setSectorList($c);
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
