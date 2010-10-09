<?php
/**
 * @package ActiveRecord
 */

/**
 * Singleton to manage any and all database connections.
 *
 * @package ActiveRecord
 */
class ConnectionManager extends Singleton
{
	/**
	 * Array of {@link Connection} objects.
	 * @var array
	 */
	static private $connection;

	/**
	 * If $name is null then the default connection will be returned.
	 *
	 * @return Connection
	 */
	public static function get_connection()
	{
		if (!isset(self::$connection) || !self::$connection->connection)
			self::$connection = Connection::instance();

		return self::$connection;
	}

	/**
	 * Drops the connection from the connection manager. Does not actually close it since there
	 * is no close method in PDO.
	 */
	public static function drop_connection()
	{
		if (isset(self::$connection))
			unset(self::$connection);
	}
};
?>
