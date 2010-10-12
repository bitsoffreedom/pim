<?php
define('PHP_ACTIVERECORD_VERSION_ID','1.0');

require 'lib/Singleton.php';
require 'lib/Utils.php';
require 'lib/DateTime.php';
require 'lib/Model.php';
require 'lib/Table.php';
require 'lib/ConnectionManager.php';
require 'lib/Connection.php';
require 'lib/SQLBuilder.php';
require 'lib/Reflections.php';
require 'lib/Inflector.php';
require 'lib/CallBack.php';
require 'lib/Exceptions.php';

class Model_Sector extends Model
{
	static $table_name = 'sector';
	static $primary_key = 'id';
	static $db = 'pim';

	static $has_many = array(
	    array('datahamster', 'class_name' => 'Model_Datahamster', 'foreign_key' => 'id')
	);
}

class Model_Location extends Model
{
	static $table_name = 'location';
	static $primary_key = 'id';
	static $db = 'pim';
}

class Model_Datahamster extends Model
{
	static $table_name = 'datahamster';
	static $primary_key = 'id';
	static $db = 'pim';

	static $belongs_to = array(
	    array('sector', 'class_name' => 'Model_Sector', 'foreign_key' => 'id')
	);

	static $has_many = array(
	    array('datahamsterextra', 'class_name' => 'Model_DatahamsterExtra', 'foreign_key' => 'id')
	);

	static public function hamsterSearch($sectors, $searchphrase)
	{
		return Model_Datahamster::find('all', array('conditions' => array("sector_id in (?) and name LIKE '%?%'", $sectors, $searchphrase)));
	}
}

class Model_DatahamsterExtra extends Model
{
	static $table_name = 'datahamster_extra';
	static $primary_key = 'id';
	static $db = 'pim';

	static $belongs_to = array(
	    array('datahamster', 'class_name' => 'Model_Datahamster', 'foreign_key' => 'id')
	);

}

class Model_User extends Model
{
	const PASSWORD_LENGTH = 50;
	const SALT_LENGTH = 10;

	static $table_name = 'user';
	static $primary_key = 'id';
	static $db = 'pim';

	/**
	*
	* @param string $plaintext The plaintext password
	* @return bool
	*/
	public function verify( $plaintext ) {
		if ( is_null( $this->password ) || strlen( $this->password ) < self::PASSWORD_LENGTH ) {
			return false;
		}

		$salt = substr( $this->password, 0, self::SALT_LENGTH );
		return sha1( $salt . $plaintext ) === substr( $this->password, self::SALT_LENGTH );
	}

	/**
	*
	* @param string $password The password
	* @param bool $plaintext Whether the password is in plaintext
	*/
	public function setPassword( $password, $plaintext = true ) {
		if ( true === $plaintext ) {
			$salt = round( ( 1 + lcg_value() ) * 1000000000 );
			$this->password = $salt . sha1( $salt . $password );
		}
		else {
			$this->password = $password;
		}
	}
}


?>
