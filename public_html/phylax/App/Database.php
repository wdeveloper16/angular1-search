<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Database {

    public $H = null;

    public function append_parameter( $param, $value ) {
        if ( $value == '' ) { return ''; }
        return ' ' . $param . ' ' . $value;
    }

    public function append_where( $s ) {
        return $this->append_parameter( 'WHERE', $s );
    }

    public function append_orderby( $s ) {
        return $this->append_parameter( 'ORDERBY', $s );
    }

    public function append_limit( $s ) {
        return $this->append_parameter( 'LIMIT', $s );
    }

    public function assoc_read_table( $table, $fields = array(), $where = '', $orderby = '', $limit = '' ) {
        $ret = array();
        $all_fields = implode( ', ', array_map( function( $item ) {
            return '`' . $item . '`';
        }, array_values( $fields ) ) );
        $q = 'SELECT ' . $all_fields .
             ' FROM `' . PREFIX . $table . '`' .
             $this->append_where( $where ) .
             $this->append_orderby( $orderby ) .
             $this->append_limit( $limit );
        $r = $this->query( $q );
        if ( $r->num_rows > 0 ) {
            while( $item = $r->fetch_assoc() ) {
                $ret[] = $item;
            }
        }
        return $ret;
    }

    public function assoc_read_line( $table, $fields = array(), $where = '', $orderby = '' ) {
        $ret = null;
        $all_fields = implode( ', ', array_map( function( $item ) {
            return '`' . $item . '`';
        }, array_values( $fields ) ) );
        $q = 'SELECT ' . $all_fields .
             ' FROM `' . PREFIX . $table . '`' .
             $this->append_where( $where ) .
             $this->append_orderby( $orderby ) .
             ' LIMIT 1';
        $r = $this->query( $q );
        if ( $r->num_rows == 1 ) {
            $ret = $r->fetch_assoc();
        }
        return $ret;
    }

    public function query( $q ) {
        $r = $this->H->query( $q );
        return $r;
    }

    public function __construct($config) {

        if ( !defined( __NAMESPACE__ . '\PREFIX' ) ) {
            define( __NAMESPACE__ . '\PREFIX', $config['DB']['Prefix'] );
        }
        if ( is_null( $this->H ) ) {
            $this->H = mysqli_connect(
	            $config['DB']['Host'],
	            $config['DB']['User'],
	            $config['DB']['Password'],
	            $config['DB']['Name']
            );
            if ( $this->H == false ) { die('Internal error #010005'); }
            $this->H->set_charset( $config['DB']['Charset'] );
        }
    }

}

# EOF