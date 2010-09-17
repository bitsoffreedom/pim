<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/Form.php');
require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/Model/Datahamster.php');

class Control_Generate extends Control_Controller
{
	public function execGet()
	{
		$companyids = Session::get()->companies;
		$companies = Model_Datahamster::findByIdList($companyids);

		$param = $this->route->getParam();

		foreach ($companies as $company) {
			if ($company->getId() == $param)
				return new View_Letter($company);
		}

		return new View_Generate();
	}

	public function execPost()
	{
		/* XXX: not supported */
	}
}

?>
