<?php
namespace PIM;

class Control_SelectSectorForm extends Control_Form
{
	private $sectors = NULL;

	public function __construct()
	{
		$s = Array();

		if (!is_array($_POST) || !is_array($_POST['sectoren']))
			return;

		foreach ($_POST['sectoren'] as $value) {
			if (!ctype_digit($value)) {
				throw new \Exception('Wrong POST data received');
			}
			array_push($s, $value);
		}

		$this->sectors = $s;
	}

	public function getSectors()
	{
		return $this->sectors;
	}
}
?>
