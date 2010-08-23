<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/Model/Datahamster.php');
require_once (PIM_BASE_PATH . '/Model/Category.php');

class Control_SelectCompany extends Control_Controller
{
	public function execGet()
	{
		$_SESSION['companies'] = Array();
		try {
			$c = Model_Category::getAll();
			$hamsters = Model_Datahamster::categorySearch($_SESSION['sectors']);
			if (!empty($_SESSION['companies']))
				$sel = Model_Datahamster::findByIdList($_SESSION['companies']);
			else
				$sel = Array();
			return new View_SelectCompany($hamsters, $c, $sel);
		} catch (Exception $e) {
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			return "Fout:" . $e->getMessage() . "\n";
		}
	}

	public function execPost()
	{
		/* This code is to update the company list */
		/* This basically is the wrong place to put this. However, If
		 * we put it in a separate controller we have to bounce the
		 * user back to this page. Please note  that this code has to
		 * return something if it modifies the session state. */
		try {
			$companyform = new IntegerForm("bedrijven");
			$companies = $companyform->getIntegers();
			if (!empty($companies)) {
				/* XXX: WRONG WRONG WRONG WRONG */
				$_SESSION['companies'] = $companies;

				$c = Model_Category::getAll();
				$sel = Model_Datahamster::findByIdList($_SESSION['companies']);
				return new View_SelectCompany(Array(), $c, $sel);
			}
		} catch (Exception $e) {
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			return "Fout:" . $e->getMessage() . "\n";
		}

		try {
			$sectorform = new IntegerForm("sectoren");
			$sectors = $sectorform->getIntegers();

			$searchform = new StringForm("naam");
			$cname = $searchform->getString();

			// With an empty search field merely get the companies
			// from the specified sectors.
			if (empty($cname)) {
				if (empty($sectors)) {
					$c = Model_Category::getAll();
					$sel = Model_Datahamster::findByIdList($_SESSION['companies']);
					return new View_SelectCompany(Array(), $c, $sel);
				} else {
					$_SESSION['sectors'] = $sectors;

					$c = Model_Category::getAll();
					$hamsters = Model_Datahamster::categorySearch($_SESSION['sectors']);
					$sel = Model_Datahamster::findByIdList($_SESSION['companies']);
					return new View_SelectCompany($hamsters, $c, $sel);
				}
			} else {
				if (empty($sectors)) {
					/* XXX: please select a sector */
				} else {
					$_SESSION['sectors'] = $sectors;

					$c = Model_Category::getAll();
					$hamsters = Model_Datahamster::hamsterSearch($sectors, $cname);
					$sel = Model_Datahamster::findByIdList($_SESSION['companies']);
					return new View_SelectCompany($hamsters, $c, $sel);
				}
			}
		} catch (Exception $e) {
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			return "Fout:" . $e->getMessage() . "\n";
		}

	}
}

?>
