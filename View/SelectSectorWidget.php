<?php

require_once (PIM_BASE_PATH . '/View/Widget.php');

class View_SelectSectorWidget extends View_Widget
{
	private $category_list;
	private $errormsg;

	public function __construct()
	{
		$this->setViewFile("sector.php");
	}

	public function setCategoryList($c)
	{
		$this->category_list = $c;
	}

	public function setErrorMsg($m)
	{
		$this->errormsg = $m;
	}

	public function render()
	{
		$this->renderInternal(
			Array(
			"categorylist" => $this->category_list,
			"errormsg" => $this->errormsg
			));
	}
}

?>
