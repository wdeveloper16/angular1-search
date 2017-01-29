<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialSecondItems {

    use SocialPlatforms;
    use SocialSort;
    use CompareSocials;

    public $str;

    public function scan_for_ft_items( &$items, $ile ) {
        $r_items = array();
        $r_facebook = 0;
        $r_twitter = 0;
        foreach( $items as $key => $item ) {
            if ( ( $r_facebook < $ile ) && $this->compare( $item->url, S_FACEBOOK ) ) {
                $item->type = S_FACEBOOK;
                $this->update_item( $item );
                $r_items[] = $item;
                $r_facebook++;
                unset( $items[ $key ] );
            }
            if ( ( $r_twitter < $ile ) && $this->compare( $item->url, S_TWITTER ) ) {
                $item->type = S_TWITTER;
                $this->update_item( $item );
                $r_items[] = $item;
                $r_twitter++;
                unset( $items[ $key ] );
            }
            if ( ( $r_facebook == $ile ) && ( $r_twitter == $ile ) ) {
                break;
            }
        }
        return $r_items;
    }

    public function __construct( $items ) {
        $this->str = '';
        $this->define_socials();
        foreach( $items as &$item ) {
            $p = $this->determine( $item->clickurl );
            $item->class = $p['View']['Class'];
            $item->type = $p['Key'];
        }
        $items = $this->sort_matrix( $items );
        #echo '<pre>'.print_r($items,true).'</pre>';
        $f_last = count( $items ) - 1;
        $f_count = -1;
        foreach( $items as $item ) {
            $f_count++;
            $add_first = $add_last = false;
            if ( $f_count == $f_last ) { $add_last = true; }
            if ( $f_count == 0 ) { $add_first = true; }
            #$p = $this->determine( $item->clickurl );
            #$item->class = $p['View']['Class'];
            #echo 'ITEM:<pre>'.print_r($item,true).'</pre>';
            $this->str.= $this->get_social_item( $item, $add_first, $add_last );
        }
        #$items = $this->sort_second( $items );
    }

}

# EOF