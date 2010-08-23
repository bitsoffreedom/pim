<?php

require_once (PIM_BASE_PATH . '/Model/Persistable.php');

class Model_DatahamsterExra extends Model_Persistable
{
	/**
	*
	* @var string
	*/
	private static $tablename = 'datahamster_extra';

	/**
	*
	* @var Model_Datahamster|int
	*/
	private $datahamster;

	/**
	*
	* @var string
	*/
	private $key;

	/**
	*
	* @var string
	*/
	private $value;

	/**
	* Constructor
	* @param int $id
	*/
	public function __construct( $id = null )
	{
		parent::__construct( $id );
	}

	/**
	* Destructor
	*/
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	*
	* @return Model_Datahamster
	*/
	public function getDatahamster()
	{
		if ( is_numeric( $this->datahamster ) ) {
			$this->datahamster = Model_Datahamster::findById( $this->datahamster );
		}
		return $this->datahamster;
	}

	/**
	*
	* @return string
	*/
	public function getKey()
	{
		return $this->key;
	}

	/**
	*
	* @return string
	*/
	public function getValue()
	{
		return $this->value;
	}

	/**
	*
	* @param Model_Datahamster|int $datahamster
	*/
	public function setDatahamster( $datahamster )
	{
		$this->datahamster = $datahamster;
	}

	/**
	*
	* @param string $key
	*/
	public function setKey( $key )
	{
		$this->key = $key;
	}

	/**
	*
	* @param string $value
	*/
	public function setValue( $value )
	{
		$this->value = $value;
	}

	/**
	* @return bool
	*/
	public function insert()
	{
		if ( !is_null( $this->id ) ) {
			return false;
		}

		$prep_query = "INSERT INTO `" . self::$tablename . "` " .
		    "(datahamster_id, `key`, `value`) " .
		    "VALUES (?, ?, ?)";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );
			$dh_id = ( $this->datahamster instanceof Model_Datahamster )
			    ? $this->datahamster->getId() : $this->datahamster;

			if ( $stmt->bind_param( "iss", $dh_id, $this->key, $this->value ) ) {

				$result = $stmt->execute();

				if ( $result === true ) {
					$this->id = $stmt->insert_id;
				}
				$stmt->close();
				return $result;
			}
		}

		return false;
	}

	/**
	* @return bool
	*/
	public function update()
	{
		if ( is_null( $this->id ) ) {
			return false;
		}

		$prep_query = "UPDATE `" . self::$tablename . "` " .
		    "SET datahamster_id = ?, `key` = ?, `value` = ? " .
		    "WHERE id = ?";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );
			$dh_id = ( $this->datahamster instanceof Model_Datahamster )
			    ? $this->datahamster->getId() : $this->datahamster;

			if ( $stmt->bind_param( "issi", $dh_id, $this->key, $this->value
			    , $this->id ) ) {

				$result = $stmt->execute();
				$stmt->close();
				return $result;
			}
		}

		return false;
	}

	/**
	* @return bool
	*/
	public function delete()
	{
		if ( is_null( $this->id ) ) {
			return false;
		}

		$prep_query = "DELETE FROM `" . self::$tablename . "` " .
		    "WHERE id = ?";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "i", $this->id ) ) {

				$result = $stmt->execute();

				if ( $result === true ) {
					$this->id = null;
				}

				$stmt->close();
				return $result;
			}
		}

		return false;
	}

	/**
	*
	* @param int $id
	* @return Model_DatahamsterExtra
	*/
	public static function findById( $id )
	{
		$prep_query = "SELECT datahamster_id, `key`, `value` " .
		    "FROM `" . self::$tablename . "` " .
		    "WHERE id = ?";

		$connection = self::getConnection();
		$datahamster_extra = null;

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "i", $id ) ) {

				$result = $stmt->execute();

				if ( $result === true ) {

					if ( $stmt->bind_result( $datahamster_id, $key, $value ) ) {

						if ( $stmt->fetch() ) {
							$datahamster_extra = new Model_DatahamsterExtra( $id );
							$datahamster_extra->setDatahamster( $datahamster_id );
							$datahamster_extra->setKey( $key );
							$datahamster_extra->setValue( $value );

						}

					}

				}
				$stmt->close();
			}
		}

		return $datahamster_extra;
	}

	/**
	*
	* @param int $id
	* @return array
	*/
	public static function findAllByDatahamster( Model_Datahamster $datahamster )
	{
		$prep_query = "SELECT id, `key`, `value` " .
		    "FROM `" . self::$tablename . "` " .
		    "WHERE datahamster_id = ?";

		$connection = self::getConnection();
		$datahamster_extras = array();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "i", $datahamster->getId() ) ) {

				$result = $stmt->execute();

				if ( $result === true ) {

					if ( $stmt->bind_result( $id, $key, $value ) ) {

						while ( $stmt->fetch() ) {
							$datahamster_extra = new Model_DatahamsterExtra( $id );
							$datahamster_extra->setDatahamster( $datahamster->getId() );
							$datahamster_extra->setKey( $key );
							$datahamster_extra->setValue( $value );
							$datahamster_extras[] = $datahamster;
						}

					}

				}
				$stmt->close();
			}
		}

		return $datahamster_extras;
	}

	/**
	* Returns all DatahamsterExtra currently available.
	* @return array
	*/
	public static function getAll()
	{
		$prep_query = "SELECT id, datahamster_id, `key`, `value` " .
		    "FROM `" . self::$tablename . "` ";

		$connection = self::getConnection();
		$datahamster_extras = array();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			$result = $stmt->execute();

			if ( $result === true ) {

				if ( $stmt->bind_result( $id, $datahamster_id, $key, $value ) ) {

					while ( $stmt->fetch() ) {
						$datahamster_extra = new Model_DatahamsterExtra( $id );
						$datahamster_extra->setDatahamster( $datahamster_id );
						$datahamster_extra->setKey( $key );
						$datahamster_extra->setValue( $value );
						$datahamster_extras[] = $datahamster_extra;
					}

				}

			}
			$stmt->close();
		}

		return $datahamster_extras;
	}
}
