<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconYelp {

    public $I = null;
    protected $item;

    public function process( $content ) {
        $get_content = str_get_html( $content );
        $title = '';
        $image = null;
        foreach( $get_content->find('img') as $element ) {
            if ( !preg_match( '/blank.(.*)/i', $element->src ) && ( $element->class == 'photo-box-img' ) && filter_var( $element->src, FILTER_VALIDATE_URL ) ) {
                $image = $element->src;
                break;
            }
        }
        return $image;
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