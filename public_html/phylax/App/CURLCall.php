<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

trait CURLCall {

    public $results = array();
    public $list = array();

    public function pinterest_node( $d ) {
        $title = $text = $lnk = $img_src = '';
        foreach( $d->getElementsByTagName('h3') as $e ) {
            if ( ( $e->getAttribute('class') == 'richPinGridTitle' ) && $e->hasChildNodes() ) {
                $title = trim( strip_tags( $e->childNodes->item(0)->nodeValue ) );
                break;
            }
        }
        $ret = array(
            'title' => $title,
        );
        foreach( $d->getElementsByTagName('p') as $e ) {
            if ( ( $e->getAttribute('class') == 'pinDescription' ) && $e->hasChildNodes() ) {
                $text = trim( strip_tags( $e->childNodes->item(0)->nodeValue ) );
                break;
            }
        }
        if ( $text == '' ) { return null; } # a jak nie ma opisu...
        $ret['body'] = $text;
        foreach( $d->getElementsByTagName('a') as $e ) {
            if ( strpos( $e->getAttribute('class'), 'pinImageWrapper' ) !== false ) {
                $lnk = trim( $e->getAttribute('href') );
                break;
            }
        }
        if ( $lnk == '' ) { return null; } # skoro bez linka...
        $ret['clickurl'] = 'https://www.pinterest.com' . $lnk;
        $ret['dispurl'] = 'dispurl';
        foreach( $d->getElementsByTagName('div') as $e ) {
            if ( strpos( $e->getAttribute('class'), 'creditImg' ) !== false ) {
                foreach( $e->getElementsByTagName('img') as $e2 ) {
                    $img_src = trim( $e2->getAttribute('data-src') );
                    $img_alt = trim( $e2->getAttribute('alt') );
                    break;
                }
                break;
            }
        }
        if ( $img_src == '' ) { return null; } # bez obrazka nie ma gadki
        if ( $ret['title'] == '' ) {
            if ( $img_alt == '' ) { return null; }
            $ret['title'] = $img_alt;
        }
        $ret['Thumb'] = null;
        $ret['ThumbSrc'] = $img_src;
        $ret['class'] = 'iPinterest';
        $ret['sym'] = 'PT';
        $ret['Code'] = substr( md5( $ret['title'] ), 0, 8 );
        return $ret;
    }

    public function google_plus_node( $d ) {
        $l = $t = $img_src = $ct = '';
        foreach( $d->getElementsByTagName( 'a' ) as $e ) {
            if ( ( $e->getAttribute('class') == 'ob tv Ub Hf' ) && ( $e->getAttribute('oid') != '' ) ) {
                $l = $e->getAttribute('oid');
                if ( $e->hasChildNodes() ) {
                    $t = trim( strip_tags( $e->childNodes->item(0)->nodeValue ) );
                    break;
                }
            }
        }
        if ( ( $l == '' ) || ( $t == '' ) ) { return null; } # skoro nie ma linku ani tytułu
        $ret = array(
            'title' => $t,
            'clickurl' => 'https://plus.google.com/' . $l,
        );
        $ret['dispurl'] = 'dispurl';
        # ok, no to teraz zostaje nam tylko ikonka wpisu i jakiś tekst, co też nie będzie
        # wcale proste, o nie. Zacznijmy od tego, że taki obrazek... bo od obrazka zaczniemy
        # właśnie, powinien mieć oid zgodny z linkiem i klasę Uk wi hE.
        $oid = $l;
        foreach( $d->getElementsByTagName( 'img' ) as $e ) {
            if ( ( $e->getAttribute('class') == 'Uk wi hE' ) && ( $e->getAttribute('oid') == $oid ) ) {
                $img_src = trim( $e->getAttribute('src') );
                break;
            }
        }
        if ( $img_src == '' ) { return null; } # nie ma obrazka, nie ma obiektu
        $ret['Thumb'] = null;
        $ret['ThumbSrc'] = $img_src;
        # ok, to już brakuje nam tylko tekstu... a tekst jest w elemencie typu div
        # który ma klasę Ct. A że jest tekstem w środku, to musi mieć węzeł dziecka.
        foreach( $d->getElementsByTagName( 'div' ) as $e ) {
            if ( ( $e->getAttribute('class') == 'Ct' ) && $e->hasChildNodes() ) {
                $ct = trim( strip_tags( $e->childNodes->item(0)->nodeValue ) );
                break;
            }
        }
        if ( $ct == '' ) { return null; }
        $ret['body'] = $ct;
        # Teraz tylko sobie kodujemy skrót, aby łatwo usunąć duplikaty. Ponieważ ta sama
        # osoba może pisac rózne treści, powinien to byś skrót urla i tekstu
        $ret['class'] = 'iGoogle';
        $ret['sym'] = 'GL';
        $ret['Code'] = substr( md5( $ret['clickurl'] . $ret['body'] ), 0, 8 );
        return $ret;
    }

    public function pinterest_fetch_nodes( $d ) {
        $nodes = array();
        $dom = new \DOMDocument();
        @$dom->loadHTML( mb_convert_encoding( $d, 'HTML-ENTITIES', 'UTF-8' ) );
        $dom->preserveWhiteSpace = false;
        foreach( $dom->getElementsByTagName('div') as $e ) {
            if ( $e->getAttribute('class') == 'pinWrapper' ) {
                $nodes[] = $e;
            }
        }
        if ( count( $nodes ) > 0 ) { return $nodes; }
        return null;
    }

    public function google_plus_fetch_nodes( $d ) {
        $nodes = array();
        $dom = new \DOMDocument();
        @$dom->loadHTML( mb_convert_encoding( $d, 'HTML-ENTITIES', 'UTF-8' ) );
        $dom->preserveWhiteSpace = false;
        foreach( $dom->getElementsByTagName('div') as $e ) {
            if ( ( $e->getAttribute('role') == 'article' ) && $e->hasAttribute('aria-hidden') ) {
                $nodes[] = $e;
            }
        }
        if ( count( $nodes ) > 0 ) { return $nodes; }
        return null;
    }

    public function pinterest_read( $term ) {
        $r = new CURLGet( 'https://pinterest.com/search/pins/?q=' . urlencode( $term ) );
        if ( is_object( $r ) && isset( $r->H ) && isset( $r->I ) && is_array( $r->I ) && isset( $r->I['http_code'] ) && ( $r->I['http_code'] == '200' ) && isset( $r->R ) && ( $r->R != '' ) ) {
            return $r->R;
        }
        return null;
    }

    public function google_plus_read( $term ) {
        $d = new CURLGet( 'http://plus.google.com/s/' . urlencode( $term ) );
        if ( is_object( $d ) && isset( $d->H ) && isset( $d->I ) && is_array( $d->I ) && isset( $d->I['http_code'] ) && ( $d->I['http_code'] == '200' ) && isset( $d->R ) && ( $d->R != '' ) ) {
            return $d->R;
        } else {
            return null;
        }
    }

}

# EOF