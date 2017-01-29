<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialSecondSearch {

    //use YahooSearch;
    use BingSearch;

    public function get() {
        return $this->list;
    }

    public function __construct( Database $db, $term, $start = 0 ) {
        $this->set_constants();
        $include_sites = opt('social_urls_str');
        $exclude_sites = opt('exclude_social_urls_str');
        $sites = $include_sites;
        if ( $exclude_sites != '' ) {
            $sites.= ',' . $exclude_sites;
        }

        $sites = 'plus.google.com/photos/,plus.google.com/communities/,plus.google.com/explore/,plus.google.com/events/,pinterest.com/explore/,pinterest.com/search/,instagram.com/explore/,instagram.com/,tumblr.com/search/,tumblr.com/tagged/,quora.com/topic/,quora.com/profile/,flickr.com/people/,flickr.com/groups/,flickr.com/places/,flickr.com/photos/tags/,linkedin.com/pulse/,linkedin.com/topic/,last.fm/tag/,reddit.com/,stumbleupon.com/,delicious.com/,digg.com/foursquare.com/top-places/,vine.co/';
        $this->query_boss( 'web', array(
            'web.q' => $term,
            'web.count' => 50, # max, because twitter / facebook may become later
            //'web.start' => 0,
            //'web.abstract' => 'long',
            //'web.style' => 'raw',
            'web.sites' => $sites,
            'format' => 'json',
        ) );

        $this->list = $this->results;

        if(count($this->results > 0)){
            $this->remove_duplicates();
            foreach( $this->list as $key => $item ) {
                $this->list[ $key ]->id = 's' . substr( md5( $item->url ), 0, 7 );
                #echo $key . '<br />' . '<pre>'.print_r($item,true).'</pre>';
            }            
        }

        /*
        if ( !is_null( $this->results ) && ( $this->results->web->totalresults == 0 ) ) {
            $this->results = null;
        }
        if ( !is_null( $this->results ) && ( $this->results->web->totalresults > 0 ) ) {
            $this->list = $this->results->web->results;
            $this->remove_duplicates();
            foreach( $this->list as $key => $item ) {
                $this->list[ $key ]->id = 's' . substr( md5( $item->url ), 0, 7 );
                #echo $key . '<br />' . '<pre>'.print_r($item,true).'</pre>';
            }
        }
        */
    }

}

# EOF