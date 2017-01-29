<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Autocomplete {

    public $list = array();

    public function __construct( Database $db, $term ) {
        $d = array();
        $q = 'SELECT `ac_word` FROM `' . PREFIX . 'autocomplete` WHERE `ac_word` LIKE "' . $db->H->real_escape_string( $term ) . '%" ORDER BY `ac_count` DESC LIMIT 10';
        if ( $r = $db->H->query( $q ) ) {
            if ( $r->num_rows > 0 ) {
                while( $item = $r->fetch_assoc() ) {
                    $d[] = $item['ac_word'];
                }
            }
        }
        $cd = count( $d );
        if ( $cd < 10 ) {
            $q = 'SELECT `ac_word` FROM `' . PREFIX . 'autocomplete` WHERE `ac_word` LIKE "%' . $db->H->real_escape_string( $term ) . '%" ORDER BY `ac_count` DESC LIMIT ' . ( 10 - $cd );
            if ( $r = $db->H->query( $q ) ) {
                while( $item = $r->fetch_assoc() ) {
                    $d[] = $item['ac_word'];
                }
            }
        }
        if ( is_array( $d ) ) {
            $d = array_unique( $d );
        }
        if ( count( $d ) > 10 ) {
            $d = array_slice( $d, 0, 10 );
        }
        if ( is_array( $d ) ) {
            $this->list = $d;
        }

	    $this->list = array_values($this->list);
    }

}

# EOF