<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Autoloader {

    protected $paths = array();
    protected $namespace;
    protected $namespace_len;

    public function load_class( $class ) {

        if ( isset( $_SESSION['ClassRoute'][ $class ] ) ) {
            require_once( $_SESSION['ClassRoute'][ $class ] );
            return;
        }

        if ( substr( $class, 0, $this->namespace_len ) == $this->namespace ) {
            $p = substr( $class, $this->namespace_len ) . '.php';
            foreach( $this->paths as $path ) {
                if ( is_readable( $path . $p ) ) {
                    $_SESSION['ClassRoute'][ $class ] = $path . $p;
                    require_once( $path . $p );
                    return;
                    break;
                }
            }
        }
        if ( substr( $class, 0, $this->google_len ) == $this->google ) {
            $p = implode( DIRECTORY_SEPARATOR, explode( '_', substr( $class, $this->google_len ) ) ) . '.php';
            if ( is_readable( GOOGLE . $p ) ) {
                    require_once( GOOGLE . $p );
                    return;
//                    break;
            }
        }
    }

    public function register() {
        spl_autoload_register( array( $this, 'load_class' ) );
    }

    public function add_route( $class, $path ) {
        $_SESSION['ClassRoute'][ $class ] = $path;
    }

    public function add_path( $path, $prepend = false ) {
        if ( $prepend ) {
            array_unshift( $this->paths, $path );
        } else {
            array_push( $this->paths, $path );
        }
    }

    public function __construct() {
        $this->namespace = __NAMESPACE__ . '\\';
        $this->namespace_len = strlen( $this->namespace );
        $this->google = 'Google_';
        $this->google_len = strlen( $this->google );


        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }
        if ( !isset( $_SESSION['UserIP'] ) ) {
            session_regenerate_id( true );
            $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
        }
        if ( !isset( $_SESSION['ClassRoute'] ) ) {
            $_SESSION['ClassRoute'] = array();
        }
    }

}

# EOF