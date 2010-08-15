<?php
namespace PIM;

class Model_Address extends Model_Persistable {

    /**
     *
     * @var string
     */
    private static $tablename = 'address';

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
     * Constructor.
     *
     * @var int $id
     */
    public function __construct( $id = null ) {
        parent::__construct( $id );
    }

    /**
     * Destructor.
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * @return string
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * @return int
     */
    public function getHouseNumber() {
        return $this->house_number;
    }

    /**
     * @return string
     */
    public function getAddition() {
        return $this->addition;
    }

    /**
     * @return string
     */
    public function getPostalCode() {
        return $this->postal_code;
    }

    /**
     * @return string
     */
    public function getCity() {
    return $this->city;
    }

    /**
     *
     * @param string $street
     */
    public function setStreet( $street ) {
        $this->street = $street;
    }

    /**
     *
     * @param int $house_number
     */
    public function setHouseNumber( $house_number ) {
        $this->house_number = $house_number;
    }

    /**
     *
     * @param string $addition
     */
    public function setAddition( $addition ) {
        $this->addition = $addition;
    }

    /**
     *
     * @param string $postal_code
     */
    public function setPostalCode( $postal_code ) {
        $this->postal_code = $postal_code;
    }

    /**
     *
     * @param string $city
     */
    public function setCity( $city ) {
        $this->city = $city;
    }

    /**
     * @return bool
     */
    public function insert() {
        if ( !\is_null( $this->id ) ) {
            return false;
        }

        $prep_query = "INSERT INTO `" . self::$tablename . "` " .
            "(street, house_number, addition, postal_code, city) " .
            "VALUES (?, ?, ?, ?, ?)";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "sisss", $this->street, $this->house_number
                    , $this->addition, $this->postal_code, $this->city ) ) {

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
    public function update() {
        if ( \is_null( $this->id ) ) {
            return false;
        }

        $prep_query = "UPDATE `" . self::$tablename . "` " .
            "SET street = ?, house_number = ?, addition = ?" .
            ", postal_code = ?, city = ? " .
            "WHERE id = ?";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "sisssi", $this->street, $this->house_number
                    , $this->addition, $this->postal_code, $this->city
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
    public function delete() {
        if ( \is_null( $this->id ) ) {
            return false;
        }

        $prep_query = "DELETE FROM `" . self::$tablename . "` " .
            "WHERE id = ?";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
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
     * @return Model_Address
     */
    public static function findById( $id ) {
        $prep_query = "SELECT street, house_number, addition " .
            ", postal_code, city " .
            "FROM `" . self::$tablename . "` " .
            "WHERE id = ?";

        $connection = self::getConnection();
        $address = null;

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "i", $id ) ) {

                $result = $stmt->execute();

                if ( $result === true ) {
                    
                    if ( $stmt->bind_result( $street, $house_number, $addition
                            , $postal_code, $city ) ) {

                        if ( $stmt->fetch() ) {
                            $address = new Model_Address( $id );
                            $address->setStreet( $street );
                            $address->setHouseNumber( $house_number );
                            $address->setAddition( $addition );
                            $address->setPostalCode( $postal_code );
                            $address->setCity( $city );
                        }

                    }

                }
                $stmt->close();
            }
        }

        return $address;
    }

    /**
     * Returns all Users currently available.
     * @return array
     */
    public static function getAll() {
        $prep_query = "SELECT id, street, house_number, addition " .
            ", postal_code, city " .
            "FROM `" . self::$tablename . "` " .
            "WHERE id = ?";

        $connection = self::getConnection();
        $addresses = array();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            $result = $stmt->execute();

            if ( $result === true ) {

                if ( $stmt->bind_result( $id, $street, $house_number, $addition
                        , $postal_code, $city ) ) {

                    while ( $stmt->fetch() ) {
                        $address = new Model_Address( $id );
                        $address->setStreet( $street );
                        $address->setHouseNumber( $house_number );
                        $address->setAddition( $addition );
                        $address->setPostalCode( $postal_code );
                        $address->setCity( $city );
                        $addresses[] = $address;
                    }

                }

            }
            $stmt->close();
        }

        return $addresses;
    }
}