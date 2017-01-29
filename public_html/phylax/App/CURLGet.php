<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class CURLGet {

    public $H;
    public $R;
    public $I;

    public function __construct( $url ) {
        $this->H = curl_init();
        curl_setopt_array(
            $this->H,
            array(
                CURLOPT_URL            => $url,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)',
                CURLOPT_COOKIEJAR      => BASE_DIR . 'cookie.gbwertywtrfz.txt',
                CURLOPT_COOKIEFILE     => BASE_DIR . 'cookie.gbwertywtrfz.txt',
            )
        );
        $this->R = curl_exec( $this->H );
        if ( $this->R === false ) { $this->R = null; } else {
            $this->I = curl_getinfo( $this->H );
        }
        curl_close( $this->H );
    }

}

# EOF