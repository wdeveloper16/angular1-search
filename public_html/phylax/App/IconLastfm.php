<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconLastfm {

    public $I = null;
    protected $item;

    public function process( $content ) {
        $get_content = str_get_html( $content );
        $title = '';
        $image = null;
        $w = array();
        foreach( $get_content->find('img') as $element ) {
            if ( strpos( $element->class, 'album-cover' ) !== false ) {
                $w[ 10 ] = $element->src;
            }
            if ( strpos( $element->class, 'featured-album' ) !== false ) {
                $w[ 20 ] = $element->src;
            }
            if ( strpos( $element->class, 'crumb-image' ) !== false ) {
                $w[ 40 ] = $element->src;
            }
            if ( strpos( $element->class, 'cover-image-image' ) !== false ) {
                $w[ 50 ] = $element->src;
            }
            if ( strpos( $element->src, '.last.fm/serve/_/' ) > 0 ) {
                $w[ 30 ] = $element->src;
            }
        }
        if ( count( $w ) > 0 ) {
            $e = reset( $w );
            return $e;
        }
        return null;
    }

    public function add_item( $p ) {
        $this->I = array(
            'id' => $this->item->I,
            'src' => $p,
        );
    }

    public function __construct( $item ) {
        $this->item = $item;
        $c = new CURLGet( $this->item->U );
        if ( !is_null( $c->R ) ) {
            $p = $this->process( $c->R );
            if ( !is_null( $p ) && ( $p != '' ) ) {
                $this->add_item( $p );
            }
        }
    }

}

# EOF