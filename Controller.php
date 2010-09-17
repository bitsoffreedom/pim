<?php

require_once( PIM_BASE_PATH . '/Session.php' );
require_once (PIM_BASE_PATH . '/Form.php');
require_once (PIM_BASE_PATH . '/View/View.php');
require_once (PIM_BASE_PATH . '/Model/Datahamster.php');
require_once (PIM_BASE_PATH . '/Model/Sector.php');

abstract class Controller
{
	// @var class Route
        protected $route;

	/* HTTP response */
	// @var string
	private $status_line;
	// @var string
	private $location;
	// @var string
	private $buffer;

	// @return string
	abstract function execGet();
	// @return string
	abstract function execPost();

	// @param Route r
	public function __construct($r)
	{
		$this->route = $r;

		if (Session::start() === false) {
			// "Could initialize the session"
			/* XXX: show what went wrong */
			$this->setStatusLine('HTTP/1.0 500 Internal Server Error');
			exit(0);
		}

		switch ($_SERVER['REQUEST_METHOD']) {
		case "GET":
			$this->status_line = "HTTP/1.1 200 OK";
			$this->buffer = $this->execGet();
			break;
		case "POST":
			$this->status_line = "HTTP/1.1 200 OK";
			$this->buffer = $this->execPost();
			break;
		default:
			$this->setStatusLine('HTTP/1.0 501 Not Implemented');
			$this->buffer = false;
		}
	}

	public function display()
	{
		header($this->status_line);
		if (!empty($this->location))
			header(sprintf('Location: %s', $this->location));

		// disable caching
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		
		if (!empty($this->buffer))
			echo $this->buffer;
	}

	// @param String $L
	protected function setStatusLine($l)
	{
		$this->status_line = $l;
	}

	// @param string $l
	protected function setLocation($l)
	{
		$this->location = $l;
	}

	// @param string $l
	protected function setBuffer($b)
	{
		$this->buffer = $b;
	}
}

class Control_Admin extends Controller
{
	/**
	* Constructor
	* @param Route $r
	*/
	public function __construct( Route $r )
	{
		parent::__construct( $r );
	}

	public function execGet()
	{
		// First see if we're already logged in
		if ( $this->sessionOK() ) {
		    
		}
		else {

		}
	}

	public function execPost()
	{
		// First see if we're already logged in
		if ( $this->sessionOK() ) {

		}
		else {
		    
		}
	}

	/**
	*
	* @return bool
	*/
	private function sessionOK() {
		$session = Session::get();
		$session->start();

		return ( isset( $session->user )
		    && $session->user instanceof Session_User );
	}
}

class Control_Generate extends Controller
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

class Control_SelectCompany extends Controller
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
				Session::get()->sectors = Array();
				return new View_SelectCompany(Array());
			} else if (empty($cname) && !empty($sectors)) {
				Session::get()->sectors = $sectors;

				$hamsters = Model_Datahamster::sectorSearch(Session::get()->sectors);
				return new View_SelectCompany($hamsters);
			} else if (!empty($cname) && empty($sectors)) {
				Session::get()->sectors = Array();
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
				/* XXX: if company_list is empty show error and don't continue */
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

class Control_UserInfo extends Controller
{
	public function execGet()
	{
		return new View_UserInfo();
	}

	public function execPost()
	{
		$fields = Array();
		$ses_companies = Session::get()->companies;

		/* This page is dependent on company data in the Session state */
		if (empty($ses_companies)) {
			/* XXX: throw decent error */
			return;
		}

		$firstname_form = new StringForm("voornaam");
		$firstname = $firstname_form->getString();

		Session::get()->firstname = $firstname;

		$lastname_form = new StringForm("achternaam");
		$lastname = $lastname_form->getString();

		Session::get()->lastname = $lastname;

		if (empty($firstname))
			$fields[] = "voornaam";
		if (empty($lastname))
			$fields[] = "achternaam";

		$extras = Array();

		/* For all comppanies in the session state find their corresponding fields */
		$companies = Model_Datahamster::findByIdList($ses_companies);
		foreach ($companies as $c)
			$extras = array_merge($extras, $c->getExtras());

		if (!empty($extras)) {
			foreach ($extras as $extra) {
				$form = new StringForm($extra->getId());
				$value = $form->getString();

				/* Direct modifications of the session state isn't possible. This is Fucked */
				$ses_extras = Session::get()->extras;
				$ses_extras[$extra->getId()] = $value;
				Session::get()->extras = $ses_extras;

				if (empty($value)) {
					$fields[] = $extra->getValue();
				}
			}
		}

		if (count($fields) == 1) {
			return new View_UserInfo("Voer een " . $fields[0] . " in");
		} else if (count($fields) > 1) {
			$field = array_pop($fields);
			$field_list = implode(", ", $fields); 

			return new View_UserInfo("Voer een " . $field_list . " en " . $field . " in");
		}

		/* Success! */
		$this->setLocation("genereer");
	}
}

?>
