<?php

require_once (PIM_BASE_PATH . '/Control/Controller.php');
require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/Model/Datahamster.php');
require_once (PIM_BASE_PATH . '/Model/Sector.php');

class Control_SelectCompany extends Control_Controller
{
	public function execGet()
	{
		try {
			$sectors = Session::get()->sectors;
			if (empty($sectors))
				throw new Exception('No sectors selected');
			$hamsters = Model_Datahamster::sectorSearch($sectors);
			return new View_SelectCompany($hamsters);
		} catch (Exception $e) {
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			return "Fout:" . $e->getMessage() . "\n";
		}
	}

	public function execPost()
	{
		/*
		 * Based on the Key given in the form the form which is used is
		 * identified.
		 */
		$sb = new KeyExists(Array("zoekform", "lijstform"));
		if ($sb->getKey() == "zoekform") {
			return $this->searchform();
		} else if ($sb->getKey() == "lijstform") {
			return $this->listform();
		}

		/* XXX: I'm not entirely sure this is the proper
		 * response. Also an error should be written back. */
		$this->setStatusLine('HTTP/1.0 400 Bad Request');
		return "";
	}

	private function searchform()
	{
		try {
			$sectorform = new IntegerForm("sectoren");
			$sectors = $sectorform->getIntegers();

			$searchform = new StringForm("naam");
			$cname = $searchform->getString();

			// With an empty search field merely get the companies
			// from the specified sectors.
			if (empty($cname) && empty($sectors)) {
				return new View_SelectCompany(Array());
			} else if (empty($cname) && !empty($sectors)) {
				Session::get()->sectors = $sectors;

				$hamsters = Model_Datahamster::sectorSearch(Session::get()->sectors);
				return new View_SelectCompany($hamsters);
			} else if (!empty($cname) && empty($sectors)) {
				/* XXX: please select a sector */
				return new View_SelectCompany(Array());
			} else if (!empty($cname) && !empty($sectors)) {
				Session::get()->sectors = $sectors;

				$hamsters = Model_Datahamster::hamsterSearch($sectors, $cname);
				return new View_SelectCompany($hamsters);
			}
		} catch (Exception $e) {
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			return "Fout:" . $e->getMessage() . "\n";
		}
	}

	private function listform()
	{
		try {
			$companyform = new IntegerForm("bedrijven");
			$company_ids = $companyform->getIntegers();

			if (!empty($company_ids)) {
				/* XXX: This basic check assumes that all the MySQL
				 * innodb constraints are met */
				$company_list = Model_Datahamster::findByIdList($company_ids);
				if (count($company_ids) != count($company_list))
					throw new Exception("Not all Ids where found");

				if (empty(Session::get()->companies))
					Session::get()->companies = $company_ids;
				else
					Session::get()->companies = array_unique(array_merge(Session::get()->companies, $company_ids));

			}
			$sb = new KeyExists(Array("btn1", "btn2"));
			switch ($sb->getKey()) {
			case "btn1":
				$company_sids = Session::get()->companies;
				if ($company_sids == NULL)
					$company_sids = Array();
				$hamsters = Model_Datahamster::findByIdList($company_sids);
				return new View_SelectCompany($hamsters);
			case "btn2":
				$this->setLocation("gegevens");
				break;
			default:
				/* shouldn't happen */
				break;
			}
		} catch (Exception $e) {
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			return "Fout:" . $e->getMessage() . "\n";
		}
	}
}

?>
