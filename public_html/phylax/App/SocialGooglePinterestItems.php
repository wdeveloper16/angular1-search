<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialGooglePinterestItems {

    use SocialPlatforms;

    public $str;

    public function resolve_item( $item, $term ) {
        if ( count( $item ) != 1 ) { return ''; }
        foreach( $item as $key => $d ) {
            if ( strpos( $d['clickurl'], 'plus.google.com' ) > 0 ) {
                $r = new \stdClass;
                $r->id = 's' . $key;
                $r->url = 'http://plus.google.com/s/' . urlencode( $term );
                $r->title = 'Current on Google+';
                $r->abstract = $d['body'];
                $r->class = 'social_google';
                #echo '<pre>'.print_r($r,true).'</pre>';
            }
            if ( strpos( $d['clickurl'], 'www.pinterest.com' ) > 0 ) {
                $r = new \stdClass;
                $r->id = 's' . $key;
                $r->url = 'https://pinterest.com/search/pins/?q=' . urlencode( $term );
                $r->title = 'Current on Pinterest';
                $r->abstract = $d['body'];
                $r->class = 'social_pinterest';
                #echo '<pre>'.print_r($r,true).'</pre>';
            }
        }
        return $r;
    }

    public function __construct( $items, $term ) {
        $this->str = '';
        foreach( $items as $item ) {
            #echo '<pre>'.print_r($item,true).'</pre>';
            $this->str.= $this->get_social_item( $this->resolve_item( $item, $term ) );
        }
    }

}

# EOF