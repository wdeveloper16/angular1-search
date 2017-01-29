<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class ImagesSearch {

    //use YahooSearch;
    use BingSearch;

    public function get() {
        return $this->list;
    }

    public function __construct( Database $db, $term, $start = 0 ) {

        $this->set_constants();
        $this->query_boss( 'images', array(

            'images.q'      => $term,
            'images.count'  => opt('max_results_image'),
            'format'        => 'json',
            
            //'images.start' => $start,
            
        ) );

        $this->list = $this->results;

        /*
        if ( !is_null( $this->results ) && ( $this->results->images->totalresults == 0 ) ) {
            $this->results = null;
        }
        if ( !is_null( $this->results ) && ( $this->results->images->totalresults > 0 ) ) {
            $this->list = $this->results->images->results;
        }
        */
        
    }

}

# EOF