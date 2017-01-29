<?php

# Full confi stack

if ( !defined('SM_STEAM') ) {
    define( 'SM_STEAM', './fvkrmtrqmywbt-rysiek/' );
}

define( 'SM_TPL', './xgrjckumsoqp-szablony/' );

define( 'SM_PROTOCOL', 'http://' );

if ( strpos( $_SERVER['SERVER_NAME'], 'sourcemoz.lc' ) !== false ) {
    # DEFINICJE LOKALNE
    define( 'SERVER_MOBILE_NAME', 'm.sourcemoz.lc' );
    define( 'SERVER_DESKTOP_NAME', 'sourcemoz.lc' );
    define( 'DBI_HOST', '127.0.0.1' );
    define( 'DBI_USER', 'root' );
    define( 'DBI_PASS', 'root' );
    define( 'DBI_NAME', 'usersmlu_ca' );
} else if ( strpos( $_SERVER['SERVER_NAME'], 'sourcemoz.geoarea.com' ) !== false ) {
        # DEFINICJE LOKALNE
        define( 'SERVER_MOBILE_NAME', 'm.sourcemoz.geoarea.com' );
        define( 'SERVER_DESKTOP_NAME', 'sourcemoz.geoarea.com' );
        define( 'DBI_HOST', 'localhost' );
        define( 'DBI_USER', 'sourcemozdb' );
        define( 'DBI_PASS', 'ay90gB%8' );
        define( 'DBI_NAME', 'admin_smoz' );
} else {
    # DEFINICJE PRODUKCYJNE
    define( 'SERVER_MOBILE_NAME', 'm.sourcemoz.com' );
    define( 'SERVER_DESKTOP_NAME', 'sourcemoz.com' );
    define( 'DBI_HOST', 'localhost' );
    define( 'DBI_USER', 'usersmlu_devln' );
    define( 'DBI_PASS', 'Nxfkr87Kyjfg0khacFN4lXQ7VculGpblc' );
    define( 'DBI_NAME', 'usersmlu_ca' );
}

# EOF