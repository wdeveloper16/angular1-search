<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialSearch extends BingSearch {
	public function get($term) {
		$this->set_constants();
//        $this->queryApiV1( 'web', array(
//            'web.q' => $term,
//            'web.count' => 50, # max, because twitter / facebook may become later
//            //'web.start' => 0,
//            //'web.abstract' => 'long',
//            //'web.style' => 'raw',
//            'web.sites' => opt('first_social_urls_str'),
//            'format' => 'json',
//        ) );

		$sites = 'facebook.com/,twitter.com/,plus.google.com/photos/,plus.google.com/communities/,plus.google.com/explore/,plus.google.com/events/,pinterest.com/explore/,pinterest.com/search/,instagram.com/explore/,instagram.com/,tumblr.com/search/,tumblr.com/tagged/,quora.com/topic/,quora.com/profile/,flickr.com/people/,flickr.com/groups/,flickr.com/places/,flickr.com/photos/tags/,linkedin.com/pulse/,linkedin.com/topic/,last.fm/tag/,reddit.com/,stumbleupon.com/,delicious.com/,digg.com/foursquare.com/top-places/,vine.co/';

		return $this->curlGetApi5($term, 'fd8520aaab764ce5b0e52fc6bd711da1',
		'"https://api.cognitive.microsoft.com/bing/v5.0/search?responseFilter=webPages"',
			$sites);


//	    return $this->results;

//        $this->list = $this->results;
//        if(count($this->results > 0)){
//            $this->removeDuplicateResults();
//            foreach( $this->list as $key => $item ) {
//                $this->list[ $key ]->id = 's' . substr( md5( $item->url ), 0, 7 );
//            }
//        }
	}
}