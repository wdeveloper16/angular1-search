<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Mobile {

    protected $device_info = array(
        '0' => array(   # komputer stacjonarny
            'M' => 0,
            'C' => 'desktop',
        ),
        '1' => array(   # tablet
            'M' => 1,
            'C' => 'tablet',
        ),
        '2' => array(   # telefon komórkowy
            'M' => 2,
            'C' => 'mobile',
        ),
    );

    public function check_get() {
        if ( isset( $_GET['m'] ) ) {
            $new_m = 0;
            $m = $_GET['m'];
            if ( is_numeric( $m ) && ( $m >= 0 ) && ( $m <= 2 ) ) {
                $new_m = $m;
            }
            $_SESSION['Config']['Device'] = $this->device_info[ $m ];
        }
    }

    public function recognize_device() {
        $r = 0;
        $detect = new \Mobile_Detect();
        if ( $detect->isMobile() ) {
            $r = 2; # komórka
        } elseif ( $detect->isTablet() ) {
            $r = 1; # tablet
        }
        return $r;
    }

    public function get() {
        return $_SESSION['Config']['Device']['M'];
    }

    public function check_session() {
        if ( !isset( $_SESSION['Config']['Device'] ) ) {
            $_SESSION['Config']['Device'] = $this->device_info[ $this->recognize_device() ];
        }
    }

    public function __construct() {
        $this->check_get(); # ustawienie w get ma absolutne pierwszeństwo
        $this->check_session();
    }

}

# EOF