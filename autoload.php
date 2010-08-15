<?php

if ( !defined( 'PIM_BASE_PATH' ) ) {
    define( 'PIM_BASE_PATH', dirname( __FILE__ ) );
}

function __autoload( $class ) {
    $path = '';

    $full_path = PIM_BASE_PATH
            . DIRECTORY_SEPARATOR
            . str_replace( '_', DIRECTORY_SEPARATOR, $class )
            . '.php';

    if (file_exists( $full_path ) ) {
        require_once( $full_path  );
    }
    else {
        throw new Exception( 'Could not find file for class ' . $class . 'path: ' . $full_path );
    }
}
