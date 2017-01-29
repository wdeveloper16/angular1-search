<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconReddit {

    public $I = null;
    protected $item;

    public function process( $content ) {
        $get_content = str_get_html( $content );
        $title = '';
        $image = null;
        $w = array();
        foreach( $get_content->find('img') as $element ) {
            if ( $element->id == 'header-img' ) {
                return $element->src;
            }
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