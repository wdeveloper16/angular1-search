<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

trait SocialSort {

    public function sort_matrix( $items ) {
        $new_list = array();
        $in_there = array();
        foreach( $items as $key => $item ) {
            if ( !isset( $in_there[ $item->class ] ) ) {
                $new_list[] = $item;
                $in_there[ $item->class ] = true;
                unset( $items[ $key ] );
            }
        }
        $ready_list = array_merge( $new_list, $items );
        return $ready_list;
    }

    public function sort_second( $items ) {
        $agg = array();
        $max_step = 0;
        foreach( $items as $key => $item ) {
            #echo 'Key: ' . $key . '<pre>'.print_r($item,true).'</pre>';
            if ( !isset( $agg[ $item->class ] ) ) {
                $agg[ $item->class ] = array( $item );
            } else {
                $agg[ $item->class ][] = $item;
            }
            $c = count( $agg[ $item->class ] );
            if ( $c > $max_step ) { $max_step = $c; }
        }
        $step = 0;
        $new_list = array();
        while( $step < $max_step ) {
            foreach( $agg as $key => $item ) {
                if ( isset( $item[ $step ] ) ) {
                    $new_list[] = $item[ $step ];
                }
            }
            #echo 'Step: ' . $step . '<br />';
            $step++;
        }
        return $new_list;
    }

}

# EOF