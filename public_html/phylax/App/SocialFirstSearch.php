<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialFirstSearch {

    //use YahooSearch;
    use BingSearch;

    public function get() {
        return $this->list;
    }

    public function __construct( Database $db, $term, $start = 0 ) {
        $this->set_constants();
        $this->query_boss( 'web', array(
            'web.q' => $term,
            'web.count' => 50, # max, because twitter / facebook may become later
            //'web.start' => 0,
            //'web.abstract' => 'long',
            //'web.style' => 'raw',
            'web.sites' => opt('first_social_urls_str'),
            'format' => 'json',
        ) );

        //file_put_contents('/var/www/html/test-errors.log', "www" . opt('first_social_urls_str') . "www" , FILE_APPEND); 
        
        $this->list = $this->results;        
        if(count($this->results > 0)){
            $this->remove_duplicates();
            foreach( $this->list as $key => $item ) {
                $this->list[ $key ]->id = 's' . substr( md5( $item->url ), 0, 7 );
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
        

        //file_put_contents('/var/www/html/test-errors.log', print_r($this->list ,true), FILE_APPEND); 
        
    }

}

# EOF