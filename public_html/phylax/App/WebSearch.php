<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class WebSearch {

    //use YahooSearch;
    use BingSearch;

    public function get() {
        return $this->list;
    }

    public function __construct( Database $db, $term, $start = 0 ) {
        $this->set_constants();
        $this->query_boss( 'web,ads', array(
                        
            'web.q'     => $term,
            'web.count' => opt('max_results_web'),
            'format'    => 'json',

            /*
            'web.start' => $start,
            'web.abstract' => 'long',
            'web.style' => 'raw',
            'ads.url' => 'www.sourcemoz.com',
            'ads.Partner' => 'domaindev_syn_boss186_ss_search',
            'ads.TYPE' => 'ddc_sourcemoz_com',
            'ads.count' => 3, # opt('max_results_ad'),
            */
            
        ) );

        $this->list = $this->results;
        $this->search_wiki_link();
        
        /*
        if ( !is_null( $this->results ) && ( $this->results->web->totalresults == 0 ) ) {
            $this->results = null;
        }
        if ( !is_null( $this->results ) && ( $this->results->web->totalresults > 0 ) ) {
            $this->list = $this->results->web->results;
            $this->search_wiki_link();
        }
        */
 
    }
}

# EOF