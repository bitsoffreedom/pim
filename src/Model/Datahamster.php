<?php
namespace PIM;

class Model_Datahamster extends Model_Persistable {

    /**
     *
     * @var string
     */
    private static $tablename = 'datahamster';

    /**
     *
     * @var Model_Address|int
     */
    private $address;

    /**
     *
     * @var Model_Category|int
     */
    private $category;

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
    public function __construct( $id = null ) {
        parent::__construct( $id );
        $this->datahamster_extra = null;
    }

    /**
     * Destructor
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     *
     * @return Model_Address
     */
    public function getAddress() {
        if (\is_numeric( $this->address ) ) {
            $this->address = Model_Address::findById( $this->address );
        }
        return $this->address;
    }

    /**
     *
     * @return Model_Category
     */
    public function getCategory() {
        if ( \is_numeric( $this->category ) ) {
            $this->category = Model_Category::findById( $this->category );
        }
        return $this->category;
    }

    /**
     *
     * @return Model_Datahamster
     */
    public function getParent() {
        if ( \is_numeric( $this->parent ) ) {
            $this->parent = Model_Datahamster::findById( $this->parent );
        }
        return $this->parent;
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
    public function getDepartment() {
        return $this->department;
    }

    /**
     *
     * @return string
     */
    public function getWeb() {
        return $this->web;
    }

    /**
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getExtras() {
        if ( \is_null( $this->datahamster_extra ) ) {
            $this->datahamster_extra =
                    Model_DatahamsterExra::findAllByDatahamster( $this );
        }
        return $this->datahamster_extra;
    }

    /**
     *
     * @param Model_Address|int $address
     */
    public function setAddress( $address ) {
        $this->address = $address;
    }

    /**
     *
     * @param Model_Category|int $category
     */
    public function setCategory( $category ) {
        $this->category = $category;
    }

    /**
     *
     * @param Model_Datahamster|int $parent
     */
    public function setParent( $parent ) {
        $this->parent = $parent;
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
     * @param string $department
     */
    public function setDepartment( $department ) {
        $this->department = $department;
    }

    /**
     *
     * @param string $web
     */
    public function setWeb( $web ) {
        $this->web = $web;
    }

    /**
     *
     * @param string $email
     */
    public function setEmail( $email ) {
        $this->email = $email;
    }

    /**
     * Adds the given Model_DatahamsterExtra to the list of extra data.
     * Note that, if the Model_DatahamsterExtra is not yet persisted, it will
     * be after using this function.
     *
     * @param Model_DatahamsterExra &$extra
     * @return bool
     */
    public function addExtra( Model_DatahamsterExra &$extra ) {
        if ( \is_null( $extra->getId() ) ) {
            if ( !$extra->insert() ) {
                return false;
            }
        }

        if ( !\in_array($extra, $this->datahamster_extra ) ) {
            $this->datahamster_extra[] = $extra;
        }
        return true;
    }

    /**
     *
     * @param array $extras An array with instances of Model_DatahamsterExtra
     * @throws Exception if persisting a Model_DatahamsterExtra fails
     */
    public function addAllExtras( array &$extras ) {
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
    public function removeExtra( Model_DatahamsterExtra &$extra ) {
        if ( \in_array( $extra, $this->datahamster_extra ) ) {
            if ( !$extra->delete() ) {
                return false;
            }

            for( $i = 0; $i < \count( $this->datahamster_extra ); $i++ ) {
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
    public function insert() {
        if ( !\is_null( $this->id ) ) {
            return false;
        }

        $prep_query = "INSERT INTO `" . self::$tablename . "` " .
            "(address_id, category_id, parent_id, name, department, web, email) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?)";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            $address_id = ( $this->address instanceof Model_Address )
                    ? $this->address->getId() : $this->address;
            $category_id = ($this->category instanceof Model_Category )
                    ? $this->category->getId() : $this->category;
            $parent_id = ( $this->parent instanceof Model_Datahamster )
                    ? $this->parent->getId() : $this->parent;

            if ( $stmt->bind_param( "iiissss", $address_id, $category_id
                    , $parent_id, $this->name, $this->department, $this->web
                    , $this->email ) ) {

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
            "SET address_id = ?, categpry_id = ? , parent_id = ?" .
            ", name = ?, department = ?, web = ?, email = ?" .
            "WHERE id = ?";

        $connection = self::getConnection();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            $address_id = ( $this->address instanceof Model_Address )
                    ? $this->address->getId() : $this->address;
            $category_id = ($this->category instanceof Model_Category )
                    ? $this->category->getId() : $this->category;
            $parent_id = ( $this->parent instanceof Model_Datahamster )
                    ? $this->parent->getId() : $this->parent;

            if ( $stmt->bind_param( "iiissssi", $address_id, $category_id
                    , $parent_id, $this->name, $this->department, $this->web
                    , $this->email, $this->id ) ) {

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
     * @return Model_Datahamster
     */
    public static function findById( $id ) {
        $prep_query = "SELECT address_id, category_id, parent_id, name " .
            ", department, web, email " .
            "FROM `" . self::$tablename . "` " .
            "WHERE id = ?";

        $connection = self::getConnection();
        $datahamster = null;

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            if ( $stmt->bind_param( "i", $id ) ) {

                $result = $stmt->execute();

                if ( $result === true ) {

                    if ( $stmt->bind_result( $address_id, $category_id,
                            $parent_id, $name, $department, $web, $email ) ) {

                        if ( $stmt->fetch() ) {
                            $datahamster = new Model_Datahamster( $id );
                            $datahamster->setAddress( $address_id );
                            $datahamster->setCategory( $category_id );
                            $datahamster->setDepartment( $department_id );
                            $datahamster->setEmail( $email );
                            $datahamster->setName( $name );
                            $datahamster->setParent( $parent_id );
                            $datahamster->setWeb( $web );
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
    public static function getAll() {
        $prep_query = "SELECT id, address_id, category_id, parent_id, name " .
            ", department, web, email " .
            "FROM `" . self::$tablename . "` ";

        $connection = self::getConnection();
        $datahamsters = array();

        if ( !\is_null( $connection ) ) {
            $stmt = $connection->prepare( $prep_query );

            $result = $stmt->execute();

            if ( $result === true ) {

                if ( $stmt->bind_result( $id, $address_id, $category_id,
                        $parent_id, $name, $department, $web, $email ) ) {

                    while ( $stmt->fetch() ) {
                        $datahamster = new Model_Datahamster( $id );
                        $datahamster->setAddress( $address_id );
                        $datahamster->setCategory( $category_id );
                        $datahamster->setDepartment( $department_id );
                        $datahamster->setEmail( $email );
                        $datahamster->setName( $name );
                        $datahamster->setParent( $parent_id );
                        $datahamster->setWeb( $web );
                        $datahamsters[] = $datahamster;
                    }

                }

            }
            $stmt->close();
        }

        return $datahamsters;
    }
}