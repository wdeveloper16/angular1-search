<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class NewsItems {

    public $str;

    public function __construct( $items ) {
        $this->str = '';
        foreach( $items as $item ) {
            list( $lang_short, $lang_long ) = explode( ' ', $item->language );
            $this->str.= '<div class="searchItem newsItem" lang="' . $lang_short . '">' . PHP_EOL;
            $this->str.= '    <h2 class="newsMagazine"><span class="nm_lang" title="' . $lang_long . '">' . $lang_short . '</span> <a href="' . $item->sourceurl . '" class="nm_click">' . $item->source . '</a></h2>';
            $this->str.= '    <h1 class="itemTitle"><a href="' . $item->url . '" class="itemClick">' . $item->title . '</a></h1>' . PHP_EOL;
            $this->str.= '    <a href="' . $item->url . '" class="itemDispUrl">' . $item->url . '</a>' . PHP_EOL;
            $this->str.= '    <div class="itemBody">' . $item->abstract . '</div>' . PHP_EOL;
            $this->str.= '</div><!-- .searchItem .newsItem -->' . PHP_EOL;
        }
    }

}

# EOF