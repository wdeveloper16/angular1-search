<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class WebItems {

    public $str;
    public $pos;

    public function __construct( $items ) {
        $this->str = '';
        $this->pos = 0;

        foreach( $items as $item ) {
            //$this->str.= print_r($item,true);
            $ad = '';
            if ( isset( $item->ad ) && $item->ad ) {
                $ad = ' webAd';
            }
            $this->str.= '<div id="webItem-' . $this->pos . '" class="searchItem webItem' . $ad . '">' . PHP_EOL;
            $this->str.= '    <h1 class="itemTitle"><a href="' . $item->clickurl . '" class="itemClick">' . $item->title . '</a></h1>' . PHP_EOL;
            $this->str.= '    <a href="' . $item->clickurl . '" class="itemDispUrl">' . $item->dispurl . '</a>' . PHP_EOL;
            $this->str.= '    <div class="itemBody">' . $item->abstract . '</div>' . PHP_EOL;
            $this->str.= '</div><!-- .searchItem .webItem -->' . PHP_EOL;

            $this->pos++;
        }
    }
}

# EOF