<?php

class Session
{
	/**
	*
	* @var Session
	*/
	private static $instance;

	/**
	* Initializes the session.
	* I found this initialization stuff on
	* http://security.nl/artikel/34117/1/PHP_sessions%3B_hoe_het_wel_moet.html
	* @return bool
	*/
	public function __construct()
	{
		session_name( 'pim' );
		session_set_cookie_params( 0, '/' );
		session_cache_expire( 30 );

		/* This might cause problems for mozilla according to the PHP manual
		* page */
		session_cache_limiter( 'private' );

		if (!session_start()) {
			throw new Exception("Failed to start the session");
		}

		// Regenerates the session id to avoid session fixation.
		session_regenerate_id( true );

		if (!array_key_exists('initialized', $_SESSION)) {
			$_SESSION['sectors'] = Array();
			$_SESSION['companies'] = Array();
			$_SESSION['firstname'] = Array();
			$_SESSION['lastname'] = Array();
			$_SESSION['initialized'] = 1;
		}

	}

	/**
	 * Start a new session instance
	 * @return false on failure. true on success;
	 */
	public static function start()
	{
		if (isset(self::$instance))
			return (false);

		$c = __CLASS__;
		self::$instance = new $c;

		return (true);
	}

	/**
	 * Retrieve the current session instance
	 * @return null on failure an instance on success
	 */
	public static function get() 
	{
		if (!isset(self::$instance))
			return null;

		return self::$instance;
	}

	/**
	* Calls session_destroy()
	*/
	public function destroy()
	{
		session_destroy();
	}

	/**
	*
	* @return string
	*/
	public function getName()
	{
		return session_name();
	}

	/**
	*
	* @param string $name
	* @return mixed
	*/
	public function  __get( $name )
	{
		if ( isset( $_SESSION[ $name ] ) ) {
			return $_SESSION[ $name ];
		}
	}

	/**
	*
	* @param string $name
	* @param mixed $value
	*/
	public function  __set( $name, $value )
	{
		$_SESSION[ $name ] = $value;
	}

	/**
	*
	* @param string $name
	* @return bool
	*/
	public function  __isset( $name )
	{
		return isset( $_SESSION[ $name ] );
	}
}

?>
