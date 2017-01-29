<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

trait CompareSocials {

    public function determine( $url ) {
        foreach( $_SESSION['AppSocialInfo'] as $platform => $info ) {
            foreach( $info['CmpUrl'] as $sub => $sub_len ) {
                if ( substr( $url, 0, $sub_len ) == $sub ) {
                    return $info;
                }
            }
        }
        foreach( $_SESSION['AppSocialInfo'] as $platform => $info ) {
            if ( isset( $info['AddCmpUrl'] ) ) {
                foreach( $info['AddCmpUrl'] as $sub ) {
                    if ( strpos( $url, $sub ) > 0 ) {
                        return $info;
                    }
                }
            }
        }
        return false;
    }

    public function compare( $url, $social_platform ) {
        if ( !isset( $_SESSION['AppSocialInfo'][ $social_platform ] ) ) { return false; }
        foreach( $_SESSION['AppSocialInfo'][ $social_platform ]['CmpUrl'] as $sub => $sub_len ) {
            if ( substr( $url, 0, $sub_len ) == $sub ) {
                return true;
            }
        }
        if ( isset( $_SESSION['AppSocialInfo'][ $social_platform ]['AddCmpUrl'] ) ) {
            foreach( $_SESSION['AppSocialInfo'][ $social_platform ]['AddCmpUrl'] as $sub ) {
                if ( strpos( $url, $sub ) > 0 ) {
                    return true;
                }
            }
        }
        return false;
    }

}

# EOF