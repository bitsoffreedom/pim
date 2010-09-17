<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/Form.php');
require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/Model/Sector.php');

class Control_SelectSector extends Controller
{
	public function execGet()
	{
		/* XXX: get model from model class/object */
		$c = Model_Sector::getAll();
                return new View_SelectSector($c);
	}

	public function execPost()
	{
		try {
			$f = new IntegerForm("sectoren");
			$s = $f->getIntegers();
			if (empty($s)) {
				Session::get()->sectors = Array();
				$c = Model_Sector::getAll();
				$v = new View_SelectSector($c, "U heeft geen sector geselecteerd.");
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
