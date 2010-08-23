<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/Form.php');
require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/Model/Category.php');

class Control_SelectSector extends Control_Controller
{
	public function execGet()
	{
		/* XXX: get model from model class/object */
		$c = Model_Category::getAll();
                return new View_SelectSector($c);
	}

	public function execPost()
	{
		try {
			$f = new IntegerForm("sectoren");
			$s = $f->getIntegers();
			if (empty($s)) {
				/* XXX: get model from model class/object */
				$c = Model_Category::getAll();
				$v = new View_SelectSector($c, "Probeer maar, selecteer nog een keer niets.");
				return $v;
			} else {
				Session::get()->sectors = $s;
				$this->setLocation("bedrijven");
			}
		} catch (Exception $e) {
			return "Fout:" . $e->getMessage() . "\n";
		}
	}
}

?>
