<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class IconFacebook {

    public $icon_src = null;
    public $icon_img = null;

    public function into_url( $url, $alt = '' ) {
        return '<img class="sIconView" src="http://graph.facebook.com/' . $url . '/picture?type=square" alt="' . strip_tags( $alt ) . '">';
    }

    public function __construct( $item ) {
        $u = parse_url( $item->clickurl )['path'];
        //error_log(print_R($u,TRUE), 3, "/var/www/sourcemoz/public_html/msg.log");
        if ( substr( $u, 0, 1 ) == '/' ) { $u = substr( $u, 1 ); }
        if ( strlen( $u ) > 0 ) {
            $u = explode( '/', $u );
            switch( count( $u ) ) {
                case 1:
                    $url = trim( $u[0] );
                    if ( $url != '' ) {
                        $this->icon_src = $url;
                        $this->icon_img = $this->into_url( $url, $item->title );
                    }
                    break;
                case 3:
                    if ( $u[0] == 'pages' ) {
                        $url = trim( $u[2] );
                        if ( $url != '' ) {
                            $this->icon_src = $url;
                            $this->icon_img = $this->into_url( $url, $item->title );
                        }
                    }
                    break;
            }
        }
    }

}

# EOF