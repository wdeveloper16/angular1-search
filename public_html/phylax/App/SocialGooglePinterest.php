<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialGooglePinterest {

    use CURLCall;

    public function get() {
        return $this->list;
    }

    public function __construct( $term, $start = 0 ) {
        $gp = $this->google_plus_read( $term );
        $ret = null;
        if ( !is_null( $gp ) ) {
            $d = $this->google_plus_fetch_nodes( $gp );
            foreach( $d as $e ) {
                $r = $this->google_plus_node( $e );
                if ( isset( $r['Code'] ) && ( $r['Code'] != '' ) ) {
                    $r['id'] = $r['Code'];
                    $ret[ $r['Code'] ] = $r;
                }
                if ( !is_null( $ret ) ) { break; }
            }
        }
        if ( !is_null( $ret ) ) {
            $this->list[] = $ret;
        }
        $ret = null;
        $gp = $this->pinterest_read( $term );
        #echo '<pre>'.print_r($gp,true).'</pre>';
        if ( !is_null( $gp ) ) {
            $d = $this->pinterest_fetch_nodes( $gp );
            if ( !is_null( $d ) ) {
                foreach( $d as $e ) {
                    $r = $this->pinterest_node( $e );
                    if ( !isset( $r['Code'] ) ) { break; }
                    $r['id'] = $r['Code'];
                    $ret[ $r['Code'] ] = $r;
                    if ( !is_null( $ret ) ) { break; }
                }
            }
        }
        if ( !is_null( $ret ) ) {
            $this->list[] = $ret;
        }
    }

}

# EOF