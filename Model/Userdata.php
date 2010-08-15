<?php

class Model_Userdata extends Model_Persistable {

    /**
     *
     * @var string
     */
    private static $tablename = 'userdata';

    /**
     *
     * @var string
     */
    private $name;

    /**
     * Constructor
     * @param int $id
     */
    public function __construct( $id = null ) {
        parent::__construct( $id );
    }

    /**
     * Destructor
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function insert() {
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
    public function update() {
        if ( is_null( $this->id ) ) {
            return false;
        }

        $prep_query = "UPDATE `" . self::$tablename . "` " .
            "SET name = ? " .
            "WHERE id = ?";

        $connection = self::getConnection();

        if ( !is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "si", $this->name, $this->id ) ) {

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
     * @return Model_Userdata
     */
    public static function findById( $id ) {
        $prep_query = "SELECT name " .
            "FROM `" . self::$tablename . "` " .
            "WHERE id = ?";

        $connection = self::getConnection();
        $userdata = null;

        if ( !is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "i", $id ) ) {

                $result = $stmt->execute();

                if ( $result === true ) {

                    if ( $stmt->bind_result( $name ) ) {

                        if ( $stmt->fetch() ) {
                            $userdata = new Model_Userdada( $id );
                            $userdata->setName( $name );

                        }

                    }

                }
                $stmt->close();
            }
        }

        return $userdata;
    }

    /**
     * Returns all Userdata currently available.
     * @return array
     */
    public static abstract function getAll() {
        $prep_query = "SELECT id, name " .
            "FROM `" . self::$tablename . "` ";

        $connection = self::getConnection();
        $userdata = array();

        if ( !is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            $result = $stmt->execute();

            if ( $result === true ) {

                if ( $stmt->bind_result( $id, $name ) ) {

                    while ( $stmt->fetch() ) {
                        $ud = new Model_Userdada( $id );
                        $ud->setName( $name );
                        $userdata[] = $ud;
                    }

                }

            }
            $stmt->close();
        }

        return $userdata;
    }
}
