<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/Control/SelectSectorForm.php');
require_once (PIM_BASE_PATH . '/View/SelectSector.php');

class Control_SelectSector extends Control_Controller
{
	public function execGet()
	{
                echo new View_SelectSector();
	}

	public function execPost()
	{
		try {
			$f = new Control_SelectSectorForm();
			$s = $f->getSectors();
			if (empty($s)) {
				$v = new View_SelectSector();
				echo $v;
			} else {
				$this->setSectors($s);
				$this->redirect("bedrijven");
			}
		} catch (Exception $e) {
			echo "Fout:" . $e->getMessage() . "\n";
		}
	}
}

?>
