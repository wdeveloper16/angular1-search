<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconTwitter {

    public $I = null;
    protected $item;

    public function process( $content ) {
        $dom = new \DOMDocument();
        @$dom->loadHTML( $content );
        $dom->preserveWhiteSpace = false;
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            if (
                ( strpos( $src, 'profile_images' ) > 0 ) &&
                ( strpos( $src, '_normal' ) > 0 )
            ) {
                return $src;
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