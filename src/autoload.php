<?php

if ( !defined( 'PIM_BASE_PATH' ) ) {
    define( 'PIM_BASE_PATH', dirname( __FILE__ ) );
}

function __autoload( $class ) {
    $path = '';
    $class_name = '';
    $namespace = '\\';
    $bs_pos = strrpos( $class, '\\' );

    // First, see if the classname contains a namespace
    if ( $bs_pos ) {
        $namespace = substr( $class, 0, $bs_pos );
        $class_name = substr( $class, $bs_pos + 1 );
    }

    $full_path = PIM_BASE_PATH
            . DIRECTORY_SEPARATOR
            . str_replace( '_', DIRECTORY_SEPARATOR, $class_name )
            . '.php';

    if (file_exists( $full_path ) ) {
        require_once( $full_path  );
    }
    else {
        throw new Exception( 'Could not find file for class ' . $class . 'path: ' . $full_path );
    }
}
