<?php

/**
 * Since we don't store the different Letters in the database, it doesn't
 * need to extend Model_Persistable
 */
class Model_Letter {

    /**
     *
     * @var string
     */
    private $content;

    /**
     * Constructor
     */
    public function __construct() {
        $this->content = null;
    }
}