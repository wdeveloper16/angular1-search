<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Config {

    public function __construct() {
        #echo '<pre>'.print_r($_SESSION,true).'</pre>';
//        if ( !isset( $_SESSION['Config'] ) ) {
//            require_once( $_SESSION['Server']['Config'] );
//        }
        if ( !defined( __NAMESPACE__ . '\URL_HOME' ) ) {
            define( __NAMESPACE__ . '\URL_HOME', '/var/www/html/public_html/');
            define( __NAMESPACE__ . '\URL_ASSETS', URL_HOME . 'assets' . '/' );
            define( __NAMESPACE__ . '\URL_ASSETS_JS', URL_ASSETS . 'js' . '/' );
            define( __NAMESPACE__ . '\URL_ASSETS_CSS', URL_ASSETS . 'css' . '/' );
            define( __NAMESPACE__ . '\URL_ASSETS_IMG', URL_ASSETS . 'img' . '/' );
            define( __NAMESPACE__ . '\URL_ASSETS_LOGO', URL_ASSETS . 'logo' . '/' );
            define( __NAMESPACE__ . '\URL_ASSETS_BGR', URL_ASSETS . 'bgr' . '/' );
        }
    }

}

# EOF