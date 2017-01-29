<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

if ( opt('disable_cache') ) {

    class Cache {
        public function set( $where, $value ) {}
        public function get( $where ) { return null; }
    }

} else {

    class Cache {
        public $term_key;
        public function set( $where, $value ) {
            $_SESSION['Cache'][ $this->term_key ][ $where ] = $value;
        }
        public function get( $where ) {
            if ( isset( $_SESSION['Cache'][ $this->term_key ][ $where ] ) ) {
                return $_SESSION['Cache'][ $this->term_key ][ $where ];
            }
            return null;
        }
        public function __construct( $term_key ) {
            $this->term_key = $term_key;
            if ( !isset( $_SESSION['Cache'] ) ) {
                $_SESSION['Cache'] = array();
            }
            if ( !isset( $_SESSION['Cache'][ $this->term_key ] ) ) {
                $_SESSION['Cache'][ $this->term_key ] = array();
            }
        }
    }

}

# EOF