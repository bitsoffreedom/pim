<?php

abstract class Model_Persistable {


    /**
     *
     * @var array
     */
    private static $config = null;

    /**
     *
     * @var mysqli
     */
    private static $connection = null;

    /**
     *
     * @var int
     */
    protected static $counter = 0;

    /**
     *
     * @var int
     */
    protected $id;

    /**
     * Constructor
     * @var int $id
     */
    public function __construct( $id = null ) {
        self::$counter++;

        if(!is_null( $id ) && is_numeric( $id ) ) {
            $this->id = (int)$id;
        }
    }

    /**
     * Destructor
     */
    public function __destruct() {
        self::$counter--;

        if ( 0 === self::$counter ) {
            if ( !is_null( self::$connection ) ) {
                self::$connection->close();
                self::$connection = null;
            }
        }
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Inserts this Parsistable into the database.
     *
     * @return bool
     */
    public abstract function insert();

    /**
     * Updates the current state of this Persistable to the database
     *
     * @return bool
     */
    public abstract function update();

    /**
     * Removes this Persistable from the database
     *
     * @return bool
     */
    public abstract function delete();

    /**
     *
     * @return array
     */
    protected static function getConfig() {
        if ( is_null( self::$config ) ) {
            self::$config = parse_ini_file( PIM_CONFIG_FILE );
        }

        return self::$config;
    }

    /**
     * @return mysqli
     */
    protected static function getConnection() {
        if ( !is_null( self::$connection ) ) {
            return self::$connection;
        }

        $config = self::getConfig();
        $db_config = isset( $config[ 'database' ] ) ? $config[ 'database' ] : null;

        if ( !is_null( $db_config ) ) {
            self::$connection = new mysqli(
                    $config[ 'host' ]
                    , $config[ 'username' ]
                    , $config[ 'password' ]
                    , $config[ 'database' ]
            );
            return self::$connection;
        }

        return null;
    }

    /**
     * Finds the Persistable that has the given Id.
     * @return Model_Persistable
     */
    public static abstract function findById( $id );

    /**
     * Returns all Persistables currently available.
     * @return array
     */
    public static abstract function getAll();
    
}
