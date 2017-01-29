<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SearchTerm {

    public function __construct() {
        if ( isset( $_GET['q'] ) && ( $_GET['q'] != '' ) ) {
            $d = array();
            $c = new ClearTerm( $_GET['q'] );
            $d['Original'] = $c->original;
            $d['Clear'] = $c->term;
            $d['Term'] = $c->lower;
            $_SESSION['SearchTerm'] = $d;
        } else {
            unset( $_SESSION['SearchTerm'] );
        }
    }

}

# EOF