<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class ImagesItems {

    public $str;

    public function __construct( $items ) {
        $this->str = $styl = $s = '';
        $tmh = 0;
        foreach( $items as $item ) {
            $id = 'i' . substr( md5( $item->refererclickurl ), 0, 15 );
            #echo '<pre>'.print_r($item,true).'</pre>';
            if ( ( $item->thumbnailheight ) > $tmh ) { $tmh = $item->thumbnailheight; }
            $styl.= '    #' . $id . '{background-image:url(' . $item->thumbnailurl . ');}' . PHP_EOL;
            
            $s.= '<div class="searchItem imagesItem col-xs-6 col-sm-4 col-md-4 col-lg-4">' . PHP_EOL;
            $s.= '    <div class="thumbItem">' . PHP_EOL;
            $s.= '        <a id="' . $id . '" class="thumbView" href="' . $item->clickurl . '"></a>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="descItem">' . PHP_EOL;
            $s.= '        <h1 class="itemTitle"><a href="' . $item->clickurl . '" class="itemClick">' . $item->title . '</a></h1>' . PHP_EOL;
            $s.= '        <a class="itemDispUrl" href="' . $item->refererclickurl . '">' . $item->refererurl . '</a>' . PHP_EOL;
            $s.= '        <div class="img_info"><span class="info_format">' . $item->format . '</span><span class="info_size">' . $item->size . '</span><span class="info_dim">' . $item->width . 'x' . $item->height . '</span></div>' . PHP_EOL;
            $s.= '    </div><!-- .descItem -->' . PHP_EOL;
            $s.= '</div>' . PHP_EOL;




            /*
            $s.= '<div class="searchItem imagesItem">' . PHP_EOL;
            $s.= '    <div class="thumbItem">' . PHP_EOL;
            $s.= '        <a id="' . $id . '" class="thumbView" href="' . $item->clickurl . '"></a>' . PHP_EOL;
            $s.= '    </div>' . PHP_EOL;
            $s.= '    <div class="descItem">' . PHP_EOL;
            $s.= '        <h1 class="itemTitle"><a href="' . $item->clickurl . '" class="itemClick">' . $item->title . '</a></h1>' . PHP_EOL;
            $s.= '        <a class="itemDispUrl" href="' . $item->refererclickurl . '">' . $item->refererurl . '</a>' . PHP_EOL;
            $s.= '        <div class="img_info"><span class="info_format">' . $item->format . '</span><span class="info_size">' . $item->size . '</span><span class="info_dim">' . $item->width . 'x' . $item->height . '</span></div>' . PHP_EOL;
            $s.= '    </div><!-- .descItem -->' . PHP_EOL;
            $s.= '</div><!-- .searchItem .imagesItem -->' . PHP_EOL;
            */

        }
        $this->str = '<style>' . PHP_EOL . $styl . '</style>' . PHP_EOL . $s;
    }

}

# EOF