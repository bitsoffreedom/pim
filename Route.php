<?php

class Route
{
	// @var string
        private $pageclass;
	// @var string
        private $pagename;
	// @var string
	private $param;

	// @var array
        private $pages = array
            (
                "bedrijven" => "Control_SelectCompany",
                "sectoren" => "Control_SelectSector",
                "admin" => "Control_Admin",
                "gegevens" => "Control_UserInfo",
                "genereer" => "Control_Generate",
		"login" => "Control_Login",
            );

        public function __construct()
        {
		if (array_key_exists('page', $_GET) &&
		    array_key_exists($_GET['page'], $this->pages)) {
                        $this->pageclass = $this->pages[$_GET['page']];
                        $this->pagename = $_GET['page'];

			if(array_key_exists('param', $_GET) &&
			    ctype_digit($_GET['param']))
				$this->param = (int)$_GET['param'];
                } else {
                        /* Page not found! */
                        $this->pagename = "startpage";
                        $this->pageclass = "Control_SelectSector";
                }
        }

	// @return string
        public function getPageName()
        {
                return $this->pagename;
        }

	// @return string
        public function getPageClass()
        {
                return $this->pageclass;
        }

	// @return string
	public function getParam()
	{
		return $this->param;
	}
}

?>
