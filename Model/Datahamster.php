<?php

require_once (PIM_BASE_PATH . '/Model/Persistable.php');

class Model_Datahamster extends Model_Persistable
{
	/**
	*
	* @var string
	*/
	private static $tablename = 'datahamster';

	/**
	*
	* @var string
	*/
	private $street = null;

	/**
	*
	* @var int
	*/
	private $house_number = null;

	/**
	*
	* @var string
	*/
	private $addition = null;

	/**
	*
	* @var string
	*/
	private $postal_code = null;

	/**
	*
	* @var string
	*/
	private $city = null;

	/**
	*
	* @var Model_Sector|int
	*/
	private $sector;

	/**
	*
	* @var Model_Datahamster|int
	*/
	private $parent;

	/**
	*
	* @var string
	*/
	private $name;

	/**
	*
	* @var string
	*/
	private $department;

	/**
	*
	* @var string
	*/
	private $web;

	/**
	*
	* @var string
	*/
	private $email;

	/**
	*
	* @var array
	*/
	private $datahamster_extra;

	/**
	* Constructor
	* @param int $id
	*/
	public function __construct( $id = null )
	{
		parent::__construct( $id );
		$this->datahamster_extra = null;
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
	* @return Model_Sector
	*/
	public function getSector()
	{
		if ( is_numeric( $this->sector ) ) {
			$this->sector = Model_Sector::findById( $this->sector );
		}
		return $this->sector;
	}

	/**
	*
	* @return Model_Datahamster
	*/
	public function getParent()
	{
		if ( is_numeric( $this->parent ) ) {
			$this->parent = Model_Datahamster::findById( $this->parent );
		}
		return $this->parent;
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
	public function getDepartment()
	{
		return $this->department;
	}

	/**
	*
	* @return string
	*/
	public function getWeb()
	{
		return $this->web;
	}

	/**
	*
	* @return string
	*/
	public function getEmail()
	{
		return $this->email;
	}

	/**
	* @return array
	*/
	public function getExtras()
	{
		if ( is_null( $this->datahamster_extra ) ) {
			$this->datahamster_extra =
			    Model_DatahamsterExtra::findAllByDatahamster( $this );
		}
		return $this->datahamster_extra;
	}

	/**
	* @return string
	*/
	public function getStreet()
	{
		return $this->street;
	}

	/**
	* @return int
	*/
	public function getHouseNumber()
	{
		return $this->house_number;
	}

	/**
	* @return string
	*/
	public function getAddition()
	{
		return $this->addition;
	}

	/**
	* @return string
	*/
	public function getPostalCode()
	{
		return $this->postal_code;
	}

	/**
	* @return string
	*/
	public function getCity()
	{
		return $this->city;
	}

	/**
	*
	* @param Model_Sector|int $sector
	*/
	public function setSector( $sector )
	{
		$this->sector = $sector;
	}

	/**
	*
	* @param Model_Datahamster|int $parent
	*/
	public function setParent( $parent )
	{
		$this->parent = $parent;
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
	* @param string $department
	*/
	public function setDepartment( $department )
	{
		$this->department = $department;
	}

	/**
	*
	* @param string $web
	*/
	public function setWeb( $web )
	{
		$this->web = $web;
	}

	/**
	*
	* @param string $email
	*/
	public function setEmail( $email )
	{
		$this->email = $email;
	}

	/**
	*
	* @param string $street
	*/
	public function setStreet( $street )
	{
		$this->street = $street;
	}

	/**
	*
	* @param int $house_number
	*/
	public function setHouseNumber( $house_number )
	{
		$this->house_number = $house_number;
	}

	/**
	*
	* @param string $addition
	*/
	public function setAddition( $addition )
	{
		$this->addition = $addition;
	}

	/**
	*
	* @param string $postal_code
	*/
	public function setPostalCode( $postal_code )
	{
		$this->postal_code = $postal_code;
	}

	/**
	*
	* @param string $city
	*/
	public function setCity( $city )
	{
		$this->city = $city;
	}

	/**
	* Adds the given Model_DatahamsterExtra to the list of extra data.
	* Note that, if the Model_DatahamsterExtra is not yet persisted, it will
	* be after using this function.
	*
	* @param Model_DatahamsterExra &$extra
	* @return bool
	*/
	public function addExtra( Model_DatahamsterExra &$extra )
	{
		if ( is_null( $extra->getId() ) ) {
			if ( !$extra->insert() ) {
				return false;
			}
		}

		if ( !in_array($extra, $this->datahamster_extra ) ) {
			$this->datahamster_extra[] = $extra;
		}
		return true;
	}

	/**
	*
	* @param array $extras An array with instances of Model_DatahamsterExtra
	* @throws Exception if persisting a Model_DatahamsterExtra fails
	*/
	public function addAllExtras( array &$extras )
	{
		foreach( $extras as &$extra ) {
			if ( !$this->addExtra( $extra ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	*
	* @param Model_DatahamsterExtra $extra
	* @return bool
	*/
	public function removeExtra( Model_DatahamsterExtra &$extra )
	{
		if ( in_array( $extra, $this->datahamster_extra ) ) {
			if ( !$extra->delete() ) {
				return false;
			}

			for( $i = 0; $i < count( $this->datahamster_extra ); $i++ ) {
				if ( $this->datahamster_extra[ $i ] === $extra ) {
					unset( $this->datahamster_extra[ $i ] );
					$this->datahamster_extra = array_values( $this->datahamster_extra );
					break;
				}
			}
		}
		return true;
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
		    "( sector_id, parent_id, name, department, web, email," .
		    " street, house_number, addition, postal_code, city) " .
		    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			$sector_id = ($this->sector instanceof Model_Sector )
			    ? $this->sector->getId() : $this->sector;
			$parent_id = ( $this->parent instanceof Model_Datahamster )
			    ? $this->parent->getId() : $this->parent;

			if ($stmt->bind_param( "iisssssisss", $sector_id ,
			    $parent_id, $this->name, $this->department,
			    $this->web , $this->email, $this->street, $this->house_number,
			    $this->addition, $this->postal_code, $this->city) ) {

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
		    "SET sector_id = ? , parent_id = ?" .
		    ", name = ?, department = ?, web = ?, email = ?" .
		    ", street = ?, house_number = ?, addition = ?" .
		    ", postal_code = ?, city = ? " .
		    "WHERE id = ?";

		$connection = self::getConnection();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			$sector_id = ($this->sector instanceof Model_Sector )
			    ? $this->sector->getId() : $this->sector;
			$parent_id = ( $this->parent instanceof Model_Datahamster )
			    ? $this->parent->getId() : $this->parent;

			if ( $stmt->bind_param( "iissssisisssi", $sector_id
			    , $parent_id, $this->name, $this->department, $this->web
			    , $this->email, $this->id, $this->street, $this->house_number
			    , $this->addition, $this->postal_code, $this->city  ) ) {

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
	* @return Model_Datahamster
	*/
	public static function findById( $id )
	{
		$prep_query = "SELECT sector_id, parent_id, name " .
		    ", department, web, email, street, house_number, addition" .
		    ", postal_code, city " .
		    "FROM `" . self::$tablename . "` " .
		    "WHERE id = ?";

		$connection = self::getConnection();
		$datahamster = null;

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			if ( $stmt->bind_param( "i", $id ) ) {

				$result = $stmt->execute();

				if ( $result === true ) {

					if ( $stmt->bind_result( $sector_id,
					    $parent_id, $name, $department,
					    $web, $email, $street, $house_number,
					    $addition , $postal_code, $city) ) {

						if ( $stmt->fetch() ) {
							$datahamster = new Model_Datahamster( $id );
							$datahamster->setSector( $sector_id );
							$datahamster->setDepartment( $department_id );
							$datahamster->setEmail( $email );
							$datahamster->setName( $name );
							$datahamster->setParent( $parent_id );
							$datahamster->setWeb( $web );
							$datahamster->setStreet( $street );
							$datahamster->setHouseNumber( $house_number );
							$datahamster->setAddition( $addition );
							$datahamster->setPostalCode( $postal_code );
							$datahamster->setCity( $city );
						}

					}
				}
				$stmt->close();
			}
		}
		return $datahamster;
	}

	/**
	* Returns all Users currently available.
	* @return array
	*/
	public static function getAll()
	{
		$prep_query = "SELECT id, sector_id, parent_id, name " .
		    ", department, web, email, street, house_number, addition" .
		    ", postal_code, city " .

		    "FROM `" . self::$tablename . "` ";

		$connection = self::getConnection();
		$datahamsters = array();

		if ( !is_null( $connection ) ) {
			$stmt = $connection->prepare( $prep_query );

			$result = $stmt->execute();

			if ( $result === true ) {

				if ( $stmt->bind_result( $id, $sector_id,
				    $parent_id, $name, $department, $web,
				    $email, $street, $house_number, $addition ,
				    $postal_code, $city) ) {

					while ( $stmt->fetch() ) {
						$datahamster = new Model_Datahamster( $id );
						$datahamster->setSector( $sector_id );
						$datahamster->setDepartment( $department_id );
						$datahamster->setEmail( $email );
						$datahamster->setName( $name );
						$datahamster->setParent( $parent_id );
						$datahamster->setWeb( $web );
						$datahamster->setStreet( $street );
						$datahamster->setHouseNumber( $house_number );
						$datahamster->setAddition( $addition );
						$datahamster->setPostalCode( $postal_code );
						$datahamster->setCity( $city );
						$datahamsters[] = $datahamster;
					}
				}

			}
			$stmt->close();
		}

		return $datahamsters;
	}

	/**
	* Search for hamster within one or more sectors with a specified
	* string
	*/
	public static function hamsterSearch($sectors, $name)
	{
		foreach ($sectors as $id) {
			if (!is_int($id)) {
				throw new Exception('Ilegal data');
			}
		}

		$clist = implode(", ", $sectors);

		if (!ctype_alnum($name))
			throw new Exception('Ilegal data');

		$prep_query = "SELECT id, sector_id, parent_id," .
		    "name , department, web, email, street, house_number" .
		    "addition , postal_code, city " .  "FROM `" .
		    self::$tablename . "` WHERE sector_id IN (" . $clist . ")" .
		    " AND name LIKE \"%" .  $name . "%\"";

		$connection = self::getConnection();
		$datahamsters = array();

		if (is_null($connection))
			throw new Exception('No database connection');

		$stmt = $connection->prepare( $prep_query );

		$result = $stmt->execute();

		if ( $result === true ) {
			if ( $stmt->bind_result( $id, $sector_id, $parent_id,
			    $name, $department, $web, $email, $street,
			    $house_number, $addition ,$postal_code, $city ) ) {

				while ( $stmt->fetch() ) {
					$datahamster = new Model_Datahamster( $id );
					$datahamster->setSector( $sector_id );
					$datahamster->setDepartment( $department );
					$datahamster->setEmail( $email );
					$datahamster->setName( $name );
					$datahamster->setParent( $parent_id );
					$datahamster->setWeb( $web );
					$datahamster->setStreet( $street );
					$datahamster->setHouseNumber( $house_number );
					$datahamster->setAddition( $addition );
					$datahamster->setPostalCode( $postal_code );
					$datahamster->setCity( $city );
					$datahamsters[] = $datahamster;
				}

			}
		}
		$stmt->close();

		return $datahamsters;
	}

	public static function sectorSearch($sectors)
	{
		if (empty($sectors))
			return Array();

		foreach ($sectors as $id) {
			if (!is_int($id)) {
				throw new Exception('Ilegal data');
			}
		}

		$clist = implode(", ", $sectors);

		$prep_query = "SELECT id, sector_id, parent_id," .
		    "name , department, web, email, street, house_number, " .
		    " addition , postal_code, city FROM `" .
		    self::$tablename . "` WHERE sector_id IN (" . $clist . ")";

		$connection = self::getConnection();
		$datahamsters = array();

		if (is_null($connection))
			throw new Exception('No database connection');

		$stmt = $connection->prepare( $prep_query );

		$result = $stmt->execute();

		if ( $result === true ) {
			if ( $stmt->bind_result( $id, $sector_id, $parent_id,
			    $name, $department, $web, $email, $street,
			    $house_number, $addition ,$postal_code, $city) ) {

				while ( $stmt->fetch() ) {
					$datahamster = new Model_Datahamster( $id );
					$datahamster->setSector( $sector_id );
					$datahamster->setDepartment( $department );
					$datahamster->setEmail( $email );
					$datahamster->setName( $name );
					$datahamster->setParent( $parent_id );
					$datahamster->setWeb( $web );
					$datahamster->setStreet( $street );
					$datahamster->setHouseNumber( $house_number );
					$datahamster->setAddition( $addition );
					$datahamster->setPostalCode( $postal_code );
					$datahamster->setCity( $city );
					$datahamsters[] = $datahamster;
				}

			}
		}
		$stmt->close();

		return $datahamsters;
	}

	/* Returns a list of Datahamster objects
	 * An array of integers should be given as input If this list is empty,
	 * an empty array is returned. If this list does not contain integers
	 * an Exception is thrown.
	 */
	public static function findByIdList($id_list)
	{
		if (empty($id_list))
			return Array();

		foreach ($id_list as $id) {
			if (!is_int($id)) {
				throw new Exception('Ilegal data');
			}
		}

		$list = implode(", ", $id_list);

		$prep_query = "SELECT id, sector_id, parent_id," .
		    "name , department, web, email, street, house_number, " .
		    " addition , postal_code, city FROM `" .
		    self::$tablename . "` WHERE id IN (" . $list . ")";

		$connection = self::getConnection();
		$datahamsters = array();

		if (is_null($connection))
			throw new Exception('No database connection');

		$stmt = $connection->prepare( $prep_query );

		$result = $stmt->execute();

		if ( $result === true ) {
			if ( $stmt->bind_result( $id,
			    $sector_id, $parent_id, $name, $department, $web,
			    $email, $street, $house_number, $addition
			    , $postal_code, $city ) ) {

				while ( $stmt->fetch() ) {
					$datahamster = new Model_Datahamster( $id );
					$datahamster->setSector( $sector_id );
					$datahamster->setDepartment( $department );
					$datahamster->setEmail( $email );
					$datahamster->setName( $name );
					$datahamster->setParent( $parent_id );
					$datahamster->setWeb( $web );
					$datahamster->setStreet( $street );
					$datahamster->setHouseNumber( $house_number );
					$datahamster->setAddition( $addition );
					$datahamster->setPostalCode( $postal_code );
					$datahamster->setCity( $city );
					$datahamsters[] = $datahamster;
				}

			}
		}
		$stmt->close();

		return $datahamsters;
	}
}
