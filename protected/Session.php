<?php

class Session {

    /**
     *
     * @var Session
     */
    private static $instance = null;

    /**
     *
     * @var bool
     */
    private $started = false;

    /**
     * Calls session_start()
     * @return bool
     */
    public function start() {
        return $this->init();
    }

    /**
     * Calls session_destroy()
     */
    public function destroy() {
        session_destroy();
        $this->started = false;
    }

    /**
     * Regenerates the session id to avoid session fixation.
     */
    public function regenerateId() {
        session_regenerate_id( true );
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return session_name();
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function  __get( $name ) {
        if ( $this->started ) {
            if ( isset( $_SESSION[ $name ] ) ) {
                return $_SESSION[ $name ];
            }
            return null;
        }
        else {
            return null;
        }
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function  __set( $name, $value ) {
        if ( $this->started ) {
            $_SESSION[ $name ] = $value;
        }
    }

    /**
     * Initializes the session.
     * I found this initialization stuff on
     * http://security.nl/artikel/34117/1/PHP_sessions%3B_hoe_het_wel_moet.html
     * @return bool
     */
    private function init() {
        if ( !$this->started ) {
            session_name( 'pim' );
            session_set_cookie_params( 0, '/' );
            session_cache_expire( 30 );

            /* This might cause problems for mozilla according to the PHP manual
             * page */
            session_cache_limiter( 'private' );
        }
        
        $success = session_start();
        $this->initialized = $success;
        $this->started = $success;
        return $success;
    }

    /**
     *
     * @return Session
     */
    public static function get() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Session();
            return self::$instance;
        }

        self::$instance->regenerateId();
        return self::$instance;
    }
}