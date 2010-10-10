<?php
/**
 * @package ActiveRecord
 */

require 'Column.php';

/**
 * The base class for database connection adapters.
 *
 * @package ActiveRecord
 */
abstract class Connection
{
	/**
	 * The PDO connection object.
	 * @var mixed
	 */
	public $connection;

	/**
	 * The last query run.
	 * @var string
	 */
	public $last_query;

	/**
	 * Default PDO options to set for each connection.
	 * @var array
	 */
	static $PDO_OPTIONS = array(
		PDO::ATTR_CASE				=> PDO::CASE_LOWER,
		PDO::ATTR_ERRMODE			=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_ORACLE_NULLS		=> PDO::NULL_NATURAL,
		PDO::ATTR_STRINGIFY_FETCHES	=> false);

	/**
	 * The quote character for stuff like column and field names.
	 * @var string
	 */
	static $QUOTE_CHARACTER = '`';

	/**
	 * Default port.
	 * @var int
	 */
	static $DEFAULT_PORT = 0;

	/**
	 * Retrieve a database connection.
	 *
	 * @return Connection
	 * @see parse_connection_url
	 */
	public static function instance()
	{
		$info = self::parse_config();

		require_once (dirname(__FILE__) . '/adapters/MysqlAdapter.php');

		try {
			$connection           = new MysqlAdapter($info);
			$connection->protocol = $info->protocol;

			if (isset($info->charset))
				$connection->set_encoding($info->charset);
		} catch (PDOException $e) {
			throw new DatabaseException($e);
		}
		return $connection;
	}

	public static function parse_config()
	{
		$config = parse_ini_file( PIM_CONFIG_FILE );
		$info = new stdClass();
		$info->protocol = 'mysql';
		$info->host = $config['host'];
		$info->db = $config['database'];
		$info->user = $config['username'];
		$info->pass = $config['password'];

		return $info;
	}

	/**
	 * Class Connection is a singleton. Access it via instance().
	 *
	 * @param array $info Array containing URL parts
	 * @return Connection
	 */
	protected function __construct($info)
	{
		try
		{
			// unix sockets start with a /
			if ($info->host[0] != '/')
			{
				$host = "host=$info->host";

				if (isset($info->port))
					$host .= ";port=$info->port";
			}
			else
				$host = "unix_socket=$info->host";

			$this->connection = new PDO("$info->protocol:$host;dbname=$info->db",$info->user,$info->pass,self::$PDO_OPTIONS);
		} catch (PDOException $e) {
			throw new DatabaseException($e);
		}
	}

	/**
	 * Retrieves column meta data for the specified table.
	 *
	 * @param string $table Name of a table
	 * @return array An array of {@link Column} objects.
	 */
	public function columns($table)
	{
		$columns = array();
		$sth = $this->query_column_info($table);

		while (($row = $sth->fetch()))
		{
			$c = $this->create_column($row);
			$columns[$c->name] = $c;
		}
		return $columns;
	}

	/**
	 * Escapes quotes in a string.
	 *
	 * @param string $string The string to be quoted.
	 * @return string The string with any quotes in it properly escaped.
	 */
	public function escape($string)
	{
		return $this->connection->quote($string);
	}

	/**
	 * Retrieve the insert id of the last model saved.
	 *
	 * @param string $sequence Optional name of a sequence to use
	 * @return int
	 */
	public function insert_id($sequence=null)
	{
		return $this->connection->lastInsertId($sequence);
	}

	/**
	 * Execute a raw SQL query on the database.
	 *
	 * @param string $sql Raw SQL string to execute.
	 * @param array &$values Optional array of bind values
	 * @return mixed A result set object
	 */
	public function query($sql, &$values=array())
	{
		$this->last_query = $sql;

		try {
			if (!($sth = $this->connection->prepare($sql)))
				throw new DatabaseException($this);
		} catch (PDOException $e) {
			throw new DatabaseException($this);
		}

		$sth->setFetchMode(PDO::FETCH_ASSOC);

		try {
			if (!$sth->execute($values))
				throw new DatabaseException($this);
		} catch (PDOException $e) {
			throw new DatabaseException($sth);
		}
		return $sth;
	}

	/**
	 * Execute a query that returns maximum of one row with one field and return it.
	 *
	 * @param string $sql Raw SQL string to execute.
	 * @param array &$values Optional array of values to bind to the query.
	 * @return string
	 */
	public function query_and_fetch_one($sql, &$values=array())
	{
		$sth = $this->query($sql,$values);
		$row = $sth->fetch(PDO::FETCH_NUM);
		return $row[0];
	}

	/**
	 * Execute a raw SQL query and fetch the results.
	 *
	 * @param string $sql Raw SQL string to execute.
	 * @param Closure $handler Closure that will be passed the fetched results.
	 */
	public function query_and_fetch($sql, Closure $handler)
	{
		$sth = $this->query($sql);

		while (($row = $sth->fetch(PDO::FETCH_ASSOC)))
			$handler($row);
	}

	/**
	 * Returns all tables for the current database.
	 *
	 * @return array Array containing table names.
	 */
	public function tables()
	{
		$tables = array();
		$sth = $this->query_for_tables();

		while (($row = $sth->fetch(PDO::FETCH_NUM)))
			$tables[] = $row[0];

		return $tables;
	}

	/**
	 * Starts a transaction.
	 */
	public function transaction()
	{
		if (!$this->connection->beginTransaction())
			throw new DatabaseException($this);
	}

	/**
	 * Commits the current transaction.
	 */
	public function commit()
	{
		if (!$this->connection->commit())
			throw new DatabaseException($this);
	}

	/**
	 * Rollback a transaction.
	 */
	public function rollback()
	{
		if (!$this->connection->rollback())
			throw new DatabaseException($this);
	}

	/**
	 * Tells you if this adapter supports sequences or not.
	 *
	 * @return boolean
	 */
	function supports_sequences() { return false; }

	/**
	 * Return a default sequence name for the specified table.
	 *
	 * @param string $table Name of a table
	 * @param string $column_name Name of column sequence is for
	 * @return string sequence name or null if not supported.
	 */
	public function get_sequence_name($table, $column_name)
	{
		return "{$table}_seq";
	}

	/**
	 * Return SQL for getting the next value in a sequence.
	 *
	 * @param string $sequence_name Name of the sequence
	 * @return string
	 */
	public function next_sequence_value($sequence_name) { return null; }

	/**
	 * Quote a name like table names and field names.
	 *
	 * @param string $string String to quote.
	 * @return string
	 */
	public function quote_name($string)
	{
		return $string[0] === self::$QUOTE_CHARACTER || $string[strlen($string)-1] === self::$QUOTE_CHARACTER ?
			$string : self::$QUOTE_CHARACTER . $string . self::$QUOTE_CHARACTER;
	}

	/**
	 * Return a date time formatted into the database's date format.
	 *
	 * @param DateTime $datetime The DateTime object
	 * @return string
	 */
	public function date_to_string($datetime)
	{
		return $datetime->format('Y-m-d');
	}

	/**
	 * Return a date time formatted into the database's datetime format.
	 *
	 * @param DateTime $datetime The DateTime object
	 * @return string
	 */
	public function datetime_to_string($datetime)
	{
		return $datetime->format('Y-m-d H:i:s T');
	}

	/**
	 * Converts a string representation of a datetime into a DateTime object.
	 *
	 * @param string $string A datetime in the form accepted by date_create()
	 * @return DateTime
	 */
	public function string_to_datetime($string)
	{
		$date = date_create($string);
		$errors = DateTime::getLastErrors();

		if ($errors['warning_count'] > 0 || $errors['error_count'] > 0)
			return null;

		return new DateTime($date->format('Y-m-d H:i:s T'));
	}

	/**
	 * Adds a limit clause to the SQL query.
	 *
	 * @param string $sql The SQL statement.
	 * @param int $offset Row offset to start at.
	 * @param int $limit Maximum number of rows to return.
	 * @return string The SQL query that will limit results to specified parameters
	 */
	abstract function limit($sql, $offset, $limit);

	/**
	 * Query for column meta info and return statement handle.
	 *
	 * @param string $table Name of a table
	 * @return PDOStatement
	 */
	abstract public function query_column_info($table);

	/**
	 * Query for all tables in the current database. The result must only
	 * contain one column which has the name of the table.
	 *
	 * @return PDOStatement
	 */
	abstract function query_for_tables();

	/**
	 * Executes query to specify the character set for this connection.
	 */
	abstract function set_encoding($charset);
};
?>
