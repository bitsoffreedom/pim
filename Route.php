<?php

class Route
{
	// @var string
        private $pageclass;
	// @var string
        private $pagename;
	// @var array
        private $pages = array
            (
                "bedrijven" => "Control_SelectCompany",
                "sectoren" => "Control_SelectSector",
                "admin" => "Control_Admin",
                "gegevens" => "Control_UserInfo",
            );

        public function __construct()
        {
                if (array_key_exists('page', $_GET) && array_key_exists($_GET['page'], $this->pages)) {
                        $this->pageclass = $this->pages[$_GET['page']];
                        $this->pagename = $_GET['page'];

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
}

?>
