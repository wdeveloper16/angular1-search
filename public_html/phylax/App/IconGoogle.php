<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconGoogle {

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
        $u = parse_url( $this->item->U );
        if (
            isset( $u['scheme'] ) &&
            isset( $u['host'] ) &&
            isset( $u['path'] ) &&
            ( $u['scheme'] == 'https' ) &&
            ( $u['host'] == 'plus.google.com' ) &&
            ( $u['path'] != '' ) &&
            ( substr( $u['path'], 0, 1 ) == '/' )
        ) {
            $p = explode( '/', substr( $u['path'], 1 ) )[0];
            $id = '';
            if ( preg_match( '/^[0-9]*$/', $p ) ) {
                $id = $p;
            } else {
                if ( substr( $p, 0, 1 ) == '+' ) { $p = substr( $p, 1 ); }
                if ( strlen( $p ) > 0 ) {
                    $id = $p;
                }
            }
            if ( $id != '' ) {
                $url = 'http://picasaweb.google.com/data/entry/api/user/' . $id . '?alt=json';
                $c = new CURLGet( $url );
                if ( !is_null( $c->R ) && ( $c->R != '' ) && ( substr( $c->R, 0, 1 ) == '{' ) ) {
                    $src = null;
                    $d = json_decode( $content, true );
                    if (
                        isset( $d['entry']['title']['$t'] ) &&
                        isset( $d['entry']['gphoto$thumbnail']['$t'] ) &&
                        ( $d['entry']['title']['$t'] == $p )
                    ) {
                        $src = $d['entry']['gphoto$thumbnail']['$t'];
                    }
                    if ( !is_null( $src ) && ( $src != '' ) ) {
                        $this->add_item( $src );
                    }
                }
            }
        }
    }

}

# EOF