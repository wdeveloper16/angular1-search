<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconTumblr {

    public $I = null;
    protected $item;

    public function add_item( $p ) {
        $this->I = array(
            'id' => $this->item->I,
            'src' => $p,
        );

    }

    public function __construct( $item ) {

        $this->item = $item;
        #echo 'Item: ' . $this->item->U . '<br />';
        $r = array_values( array_filter( explode( '/', $this->item->U ) ) );
        $w = null;
        #echo '<pre>'.print_r($r,true).'</pre>';
        if ( ( count( $r ) > 1 ) && ( ( $r[0] == 'http:' ) || ( $r[0] == 'https:' ) ) && ( $r[1] != 'www.tumblr.com' ) && ( substr( $r[1], -11 ) == '.tumblr.com' ) ) {
            $w = 'http://api.tumblr.com/v2/blog/' . $r[1] . '/avatar/40';
        }
        if ( !is_null( $w ) && ( $w != '' ) ) {
            $this->add_item( $w );
        }
    }

}

# EOF