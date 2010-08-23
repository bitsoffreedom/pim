<?php

require_once (PIM_BASE_PATH . '/Model/Persistable.php');

class Model_Category extends Model_Persistable
{
	/**
	*
	* @var string
	*/
	private static $tablename = 'category';

	/**
	*
	* @var string
	*/
	private $name;

	/**
	*
	* @var string
	*/
	private $description;

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
	*
	* @return string
	*/
	public function getDescription()
	{
		return $this->description;
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
	* @param string $description
	*/
	public function setDescription( $description )
	{
		$this->description = $description;
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
		    "(name, description) " .
		    "VALUES (?, ?)";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "ss", $this->name, $this->description ) ) {

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
		    "SET name = ?, description = ? " .
		    "WHERE id = ?";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "ssi", $this->name, $this->description
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
	* @return Model_Category
	*/
	public static function findById( $id )
	{
		$prep_query = "SELECT name, description " .
		    "FROM `" . self::$tablename . "` " .
		    "WHERE id = ?";

		$connection = self::getConnection();
		$category = null;

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "i", $id ) ) {
				$result = $stmt->execute();
				if ( $result === true ) {
					if ( $stmt->bind_result( $name, $description ) ) {
						if ( $stmt->fetch() ) {
							$category = new Model_Category( $id );
							$category->setName( $name );

						}
					}
				}
				$stmt->close();
			}
		}
		return $category;
	}

	/**
	* Returns all Users currently available.
	* @return array
	*/
	public static function getAll()
	{
		$prep_query = "SELECT id, name, description " .
		    "FROM `" . self::$tablename . "` ";

		$connection = self::getConnection();
		$categories = array();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			$result = $stmt->execute();

			if ( $result === true ) {
				if ( $stmt->bind_result( $id, $name, $description ) ) {
					while ( $stmt->fetch() ) {
						$category = new Model_Category( $id );
						$category->setName( $name );
						$categories[] = $category;
					}
				}
			}
			$stmt->close();
		}

		return $categories;
	}
}
