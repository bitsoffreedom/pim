<?php
namespace PIM;

class Model_User extends Model_Persistable {

    const PASSWORD_LENGTH = 50;

    const SALT_LENGTH = 10;

    /**
     *
     * @var string
     */
    private static $tablename = 'user';

    /**
     *
     * @var string
     */
    private $name = null;

    /**
     *
     * @var string
     */
    private $realname = null;

    /**
     *
     * @var string
     */
    private $password = null;

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
     * @return string
     */
    public function getRealname() {
        return $this->realname;
    }

    /**
     *
     * @param string $plaintext The plaintext password
     * @return bool
     */
    public function verify( $plaintext ) {
        if ( \is_null( $this->password ) || \strlen( $this->password ) < self::PASSWORD_LENGTH ) {
            return false;
        }

        $salt = \substr( $this->password, 0, self::SALT_LENGTH );
        return \sha1( $salt . $plaintext ) === \substr( $this->password, self::SALT_LENGTH );
    }

    /**
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     *
     * @param string $realname
     */
    public function setRealname( $realname ) {
        $this->realname = $realname;
    }

    /**
     *
     * @param string $password The password
     * @param bool $plaintext Whether the password is in plaintext
     */
    public function setPassword( $password, $plaintext = true ) {
        if ( true === $plaintext ) {
            $salt = round( ( 1 + \lcg_value() ) * 1000000000 );
            $this->password = $salt . \sha1( $salt . $password );
        }
        else {
            $this->password = $password;
        }
    }

    /**
     * @return bool
     */
    public function insert() {
        if ( !\is_null( $this->id ) ) {
            return false;
        }

        $prep_query = "INSERT INTO `" . self::$tablename . "` " .
            "(name, realname, password) " .
            "VALUES (?, ?, ?)";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "sss", $this->name, $this->realname
                    , $this->password ) ) {

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
            "SET name = ?, realname = ?, password = ? " .
            "WHERE id = ?";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "sssi", $this->name, $this->description
                    , $this->password, $this->id ) ) {

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
     * @return Model_User
     */
    public static function findById( $id ) {
        $prep_query = "SELECT name, realname, password " .
            "FROM `" . self::$tablename . "` " .
            "WHERE id = ?";

        $connection = self::getConnection();
        $user = null;

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "i", $id ) ) {

                $result = $stmt->execute();

                if ( $result === true ) {

                    if ( $stmt->bind_result( $name, $realname, $password ) ) {

                        if ( $stmt->fetch() ) {
                            $user = new Model_User( $id );
                            $user->setName( $name );
                            $user->setRealname( $realname );
                            $user->setPassword( $password, false );
                        }

                    }

                }
                $stmt->close();
            }
        }

        return $user;
    }

    /**
     * Returns all Users currently available.
     * @return array
     */
    public static function getAll() {
        $prep_query = "SELECT id, name, realname, password " .
            "FROM `" . self::$tablename . "` ";

        $connection = self::getConnection();
        $result = array();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            $users = $stmt->execute();

            if ( $result === true ) {

                if ( $stmt->bind_result( $id, $name, $realname, $password ) ) {

                    while ( $stmt->fetch() ) {
                        $user = new Model_User( $id );
                        $user->setName( $name );
                        $user->setRealname( $realname );
                        $user->setPassword( $password, false );
                        $users[] = $user;
                    }

                }

            }
            $stmt->close();
        }

        return $users;
    }
}