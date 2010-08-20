<?php
require_once( PIM_BASE_PATH . '/Control/Controller.php' );

class Control_Admin extends Control_Controller
{


    /**
     * Constructor
     * @param Route $r
     */
    public function __construct( Route $r ) {
        parent::__construct( $r );
    }

    public function execGet() {
        // First see if we're already logged in
        if ( $this->sessionOK() ) {
            
        }
        else {

        }
    }

    public function execPost() {
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
