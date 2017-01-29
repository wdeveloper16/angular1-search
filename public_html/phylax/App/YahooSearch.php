<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

trait YahooSearch {

    public $results = array();
    public $dmtoken = '';
    public $list = array();
    public $wiki_info = null;

    # chwilowe reklamy, które są ale ich nie ma ;)
    public function ad_temp() {
        # return array();
        return array(
            (object)array(
                'date' => '',
                'clickurl' => 'http://click.url.one',
                'url' => 'http://click.url.one',
                'dispurl' => 'http://click.url.one',
                'title' => 'Example Ad one',
                'abstract' => 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula.',
                'ad' => true,
            ),
            (object)array(
                'date' => '',
                'clickurl' => 'http://click.url.two',
                'url' => 'http://click.url.two',
                'dispurl' => 'http://click.url.two',
                'title' => 'Example Ad two',
                'abstract' => 'Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus.',
                'ad' => true,
            ),
        );
    }

    public function set_constants() {
        define( 'YAHOO_BOSS', 'http://yboss.yahooapis.com/ysearch/' );
        define( 'CONSUMER_KEY', 'dj0yJmk9NFBUOE42engwSWE2JmQ9WVdrOVdVMXliblZJTlRBbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD03Zg--' );
        define( 'CONSUMER_SECRET', '087f383d88d906e0cb2c1d84331d4c3aebd023e3' );
    }

    public function is_wiki_link( $url ) {
        $r = '/^(http[s]{0,1}:\/\/)([a-z]+)(\.wikipedia\.org\/wiki\/)(.+)$/';
        preg_match( $r, $url, $m );
        if ( count( $m ) == 0 ) {
            $r = '/^(http[s]{0,1}:\/\/)([a-z]+)(\.m.wikipedia\.org\/wiki\/)(.+)$/';
            preg_match( $r, $url, $m );
        }
        if ( count( $m ) == 5 ) {
            #echo 'URL: ' . $this->to_utf8( $url ) . '<br />';
            $this->wiki_info = array(
                'url' => $url,
                'lang' => $m[2],
                'title' => $m[4],
                'wiki_title' => str_replace( '_', ' ', $m[4] ),
            );
            #echo '<pre>'.print_r($this->wiki_info,true).'</pre>';
            return true;
        }
        return false;
    }

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

    public function search_wiki_link() {
        $wmp = opt('wiki_max_position');
        $cx = 0;
        foreach( $this->list as $item ) {
            #echo '<pre>'.print_r($item,true).'</pre>';
            $cx++;
            $item->clickurl = rawurldecode( $item->clickurl );
            if ( $this->is_wiki_link( rawurldecode( $item->clickurl ) ) ) { return; }
            if ( $cx == $wmp ) { return; }
        }
    }

    public function remove_duplicates() {
        $nl = array();
        foreach( $this->list as $key => $item ) {
            # na początku stwórz pole unique_url, które będzie służyć do porównywania
            # duplikatów i jednocześnie zmień wszystki https na http, bo są różne.
            $this->list[ $key ]->unique_url = str_replace( 'https://', 'http://', $item->url );
            # teraz usuń /events, jeżeli jest na końcu
            if ( substr( $this->list[ $key ]->unique_url, -7 ) == '/events' ) {
                $this->list[ $key ]->unique_url = substr( $this->list[ $key ]->unique_url, 0, -7 );
            }
            # usuń /likes, jeżeli jest na końcu
            if ( substr( $this->list[ $key ]->unique_url, -6 ) == '/likes' ) {
                $this->list[ $key ]->unique_url = substr( $this->list[ $key ]->unique_url, 0, -6 );
            }
            # usuń /timeline/ (zostaw /), jeżeli jest na końcu
            if ( substr( $this->list[ $key ]->unique_url, -10 ) == '/timeline/' ) {
                $this->list[ $key ]->unique_url = substr( $this->list[ $key ]->unique_url, 0, -9 );
            }
            # sprawdź, czy w adresie jest ? a jeśli tak, to usuń wszystko co dalej
            if ( strpos( $this->list[ $key ]->unique_url, '?' ) !== false ) {
                $this->list[ $key ]->unique_url = explode( '?', $this->list[ $key ]->unique_url )[ 0 ];
            }
            # teraz wpisz item do nowej listy
            $nl[ $this->list[ $key ]->unique_url ] = $this->list[ $key ];
            unset( $nl[ $this->list[ $key ]->unique_url ]->unique_url );
        }
        # przekaż z powrotem na listę
        $this->list = array_values( $nl );
    }

    public function query_boss( $service, $args ) {
        $r = array();
        $url = 'http://yboss.yahooapis.com/ysearch/' . $service;
        $consumer = new \OAuthConsumer( CONSUMER_KEY, CONSUMER_SECRET );
        $request = \OAuthRequest::from_consumer_and_token( $consumer, NULL, 'GET', $url, $args );
        $request->sign_request( new \OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL );
        $url = sprintf("%s?%s", $url, \OAuthUtil::build_http_query( $args ) );
        $ch = curl_init();
        $headers = array( $request->to_header() );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        $rsp = curl_exec( $ch );

        file_put_contents('/var/www/html/test-errors.log', print_r($rsp ,true), FILE_APPEND);

        $results = json_decode( $rsp );

        $this->results = null;
        if ( isset( $results->bossresponse ) && ( $results->bossresponse->responsecode == 200 ) ) {
            $this->results = $results->bossresponse;
            if ( isset( $this->results->ads->dmtoken ) && ( $this->results->ads->dmtoken != '' ) ) {
                $this->dmtoken = $this->results->ads->dmtoken;
            }
        }
    }

}

# EOF