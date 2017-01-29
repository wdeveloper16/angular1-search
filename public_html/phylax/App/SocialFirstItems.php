<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class SocialFirstItems {

    use SocialPlatforms;
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
        $first = $this->scan_for_ft_items( $items, 1 );
        
        $reveal = $this->scan_for_ft_items( $items, 4 );
        $s = $s_f = $s_r = '';
        if ( count( $first ) > 0 ) {
            $f_last = count( $first ) - 1;
            $f_count = -1;
            foreach( $first as $item ) {
                $f_count++;
                $add_first = $add_last = false;
                if ( $f_count == $f_last ) { $add_last = true; }
                if ( $f_count == 0 ) { $add_first = true; }
                $s_f.= $this->get_social_item( $item, $add_first, $add_last );
            }
        }
        if ( count( $reveal ) > 0 ) {
            $s_r.= $this->get_reveal_open();
            $f_last = count( $reveal ) - 1;
            $f_count = -1;
            foreach( $reveal as $item ) {
                $f_count++;
                $add_first = $add_last = false;
                if ( $f_count == $f_last ) { $add_last = true; }
                if ( $f_count == 0 ) { $add_first = true; }
                $s_r.= $this->get_social_item( $item, $add_first, $add_last );
            }
            $s_r.= $this->get_reveal_close();
        }
        $this->str = $s_f . $s_r;
    }

}

# EOF