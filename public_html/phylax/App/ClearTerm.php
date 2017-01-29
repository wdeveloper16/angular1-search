<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class ClearTerm {

    public $original = null;
    public $term = null;
    public $lower = null;

    function to_utf8( $string ) {
        if ( preg_match('%^(?:
              [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $string) ) {
            return $string;
        } else {
            return iconv( 'CP1252', 'UTF-8', $string);
        }
    }

    public function __construct( $t ) {
        $q = $this->to_utf8( $t );
        $this->original = $q;
        $q = str_replace( array( '\'', '\\', '/' ), ' ', $q );
        $q = trim( preg_replace( array('/\s{2,}/', '/[\t\n]/'), ' ', $q ) );
        #$q = htmlentities( $q, ENT_QUOTES, 'utf-8' );
        $q = mb_substr( $q, 0, 160, 'utf-8' );
        $this->term = $q;
        $this->lower = strtolower( $q );
    }

}

# EOF