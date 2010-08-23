<?php

require_once (PIM_BASE_PATH . '/Model/Persistable.php');

class Model_Location extends Model_Persistable
{
	/**
	*
	* @var string
	*/
	private static $tablename = 'location';

	/**
	*
	* @var Model_Datahamster|int
	*/
	private $datahamster;

	/**
	*
	* @var string
	*/
	private $name;


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
	* @return string
	*/
	public function getName()
	{
		return $this->name;
	}

	/**
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
	* @param string $name
	*/
	public function setName( $name )
	{
		$this->name = $name;
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
	* @return bool
	*/
	public function insert()
	{
		if ( !is_null( $this->id ) ) {
			return false;
		}

		$prep_query = "INSERT INTO `" . self::$tablename . "` " .
		    "(datahamster_id, name) " .
		    "VALUES (?, ?)";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );
			$dh_id = ( $this->datahamster instanceof Model_Datahamster )
			    ? $this->datahamster->getId() : $this->datahamster;

			if ( $stmt->bind_param( "is", $dh_id, $this->name ) ) {
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
		    "SET datahamster_id = ?, name = ? " .
		    "WHERE id = ?";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );
			$dh_id = ( $this->datahamster instanceof Model_Datahamster )
			    ? $this->datahamster->getId() : $this->datahamster;

			if ( $stmt->bind_param( "isi", $dh_id, $this->name, $this->id ) ) {
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
	* @return Model_Location
	*/
	public static function findById( $id )
	{
		$prep_query = "SELECT datahamster_id, name " .
		    "FROM `" . self::$tablename . "` " .
		    "WHERE id = ?";

		$connection = self::getConnection();
		$location = null;

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "i", $id ) ) {
				$result = $stmt->execute();

				if ( $result === true ) {
					if ( $stmt->bind_result( $datahamster_id, $name ) ) {
						if ( $stmt->fetch() ) {
							$location = new Model_Location( $id );
							$location->setDatahamster( $datahamster_id );
							$location->setName( $name );
						}
					}
				}
				$stmt->close();
			}
		}
		return $location;
	}

	/**
	* Returns all Locations currently available.
	* @return array
	*/
	public static function getAll()
	{
		$prep_query = "SELECT id, datahamster_id, name " .
		    "FROM `" . self::$tablename . "` ";

		$connection = self::getConnection();
		$locations = array();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			$result = $stmt->execute();

			if ( $result === true ) {

				if ( $stmt->bind_result( $id, $datahamster_id, $name ) ) {

					while ( $stmt->fetch() ) {
						$location = new Model_Location( $id );
						$location->setDatahamster( $datahamster_id );
						$location->setName( $name );
						$locations[] = $location;
					}
				}
			}
			$stmt->close();
		}
		return $locations;
	}
}
