<?php

class Control_Route
{
        private $pageclass;
        private $pagename;
        private $pages = array
            (
                "bedrijven" => "Control_SelectCompany",
                "sectoren" => "Control_SelectSector",
                "admin" => "Control_Admin"
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

        public function getPageName()
        {
                return $pagename;
        }

        public function getPageClass()
        {
                return $this->pageclass;
        }
}

?>
