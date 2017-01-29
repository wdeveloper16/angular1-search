<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class VideoItems {

    public $str;

    public function __construct( $items ) {
        $this->str = $c = $s = '';
        foreach( $items as $item ) {
            #echo '<pre>'.print_r($item,true).'</pre>';
            $id = 'i' . substr( md5( $item['url'] ), 0, 7 );
            $c.= '    #' . $id . '{background-image:url(' . $item['thumbnail_url'] . ');}' . PHP_EOL;
           
            $s.= '<div class="row">' . PHP_EOL;
            $s.= '  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' . PHP_EOL;
            $s.= '    <div class="col-video-image">' . PHP_EOL;
            $s.= '        <a class="thumb_view" id="' . $id . '" href="' . $item['url'] . '"></a>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="col-video-content">' . PHP_EOL;
            //$s.= '        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua' . PHP_EOL;
            $s.= '        <h1><a href="' . $item['url'] . '" class="itemClick">' . $item['title'] . '</a></h1>' . PHP_EOL;
            $s.= '        <a class="itemDispUrl" href="' . $item['url'] . '">' . $item['url'] . '</a>' . PHP_EOL;
            $s.= '        <div class="itemBody">' . $item['abstract'] . '</div>' . PHP_EOL;            
            $s.= '    </div>' . PHP_EOL;
            $s.= '  </div>' . PHP_EOL;
            $s.= '</div>' . PHP_EOL;

            /*
            $s.= '<div class="row">' . PHP_EOL;
            $s.= '  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' . PHP_EOL;
            $s.= '    <div class="col-video">' . PHP_EOL;
            $s.= '        <a class="thumb_view" id="' . $id . '" href="' . $item['url'] . '"></a>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="">' . PHP_EOL;
            $s.= '        <h1><a href="' . $item['url'] . '" class="itemClick">' . $item['title'] . '</a></h1>' . PHP_EOL;
            $s.= '        <a class="itemDispUrl" href="' . $item['url'] . '">' . $item['url'] . '</a>' . PHP_EOL;
            $s.= '        <div class="itemBody">' . $item['abstract'] . '</div>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '  </div>' . PHP_EOL;
            $s.= '</div>' . PHP_EOL;
            */

            /*
            $s.= '<div class="row">' . PHP_EOL;
            $s.= '    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">' . PHP_EOL;
            $s.= '        <a class="thumb_view" id="' . $id . '" href="' . $item['url'] . '"></a>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">' . PHP_EOL;
            $s.= '        <h1><a href="' . $item['url'] . '" class="itemClick">' . $item['title'] . '</a></h1>' . PHP_EOL;
            $s.= '        <a class="itemDispUrl" href="' . $item['url'] . '">' . $item['url'] . '</a>' . PHP_EOL;
            $s.= '        <div class="itemBody">' . $item['abstract'] . '</div>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '</div>' . PHP_EOL;
            */

            /*
            $s.= '<div class="searchItem videoItem">' . PHP_EOL;
            $s.= '    <div class="thumbItem">' . PHP_EOL;
            $s.= '        <a class="thumb_view" id="' . $id . '" href="' . $item['url'] . '"></a>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="descItem">' . PHP_EOL;
            $s.= '        <h1 class="itemTitle"><a href="' . $item['url'] . '" class="itemClick">' . $item['title'] . '</a></h1>' . PHP_EOL;
            $s.= '        <a class="itemDispUrl" href="' . $item['url'] . '">' . $item['url'] . '</a>' . PHP_EOL;
            $s.= '        <div class="itemBody">' . $item['abstract'] . '</div>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="c"></div>' . PHP_EOL;
            $s.= '</div>' . PHP_EOL;
            */

           #$this->str.= '<div class="searchItem newsItem" lang="' . $lang_short . '">' . PHP_EOL;
           #$this->str.= '    <h2 class="newsMagazine"><span class="nm_lang" title="' . $lang_long . '">' . $lang_short . '</span> <a href="' . $item->sourceurl . '" class="nm_click">' . $item->source . '</a></h2>';
           #$this->str.= '    <h1 class="itemTitle"><a href="' . $item->url . '" class="itemClick">' . $item->title . '</a></h1>' . PHP_EOL;
           #$this->str.= '    <a href="' . $item->url . '" class="itemDispUrl">' . $item->url . '</a>' . PHP_EOL;
           #$this->str.= '    <div class="itemBody">' . $item->abstract . '</div>' . PHP_EOL;
           #$this->str.= '</div><!-- .searchItem .newsItem -->' . PHP_EOL;
        }
        $this->str = '<style>' . PHP_EOL . $c . '</style>' . PHP_EOL . $s;
    }

}

# EOF