<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class FrontImages {

    protected $db;
    public $d;

    public function scan_for_active_image() {
        $sql = 'SELECT `' . PREFIX . 'frontimages`.*, `logo_hash` FROM `' . PREFIX . 'frontimages` LEFT JOIN `' . PREFIX . 'logostyles` ON `img_logo_style` = `logo_id` WHERE `img_active` = "1" ORDER BY `img_name` ASC LIMIT 1';
        $r = $this->db->H->query( $sql );
        if ( $r->num_rows != 1 ) { return null; }
        $d = $r->fetch_assoc();
        return $this->translate( $d );
    }
    public function scan_for_timeline_image() {
        $d = array();
        $t = date('Y-m-d H:i:s');
        $sql = 'SELECT `' . PREFIX . 'frontimages`.*, TIMESTAMPDIFF( SECOND, img_dt_begin, img_dt_end ) AS `time_diff`, `logo_hash` FROM `' . PREFIX . 'frontimages` LEFT JOIN `' . PREFIX . 'logostyles` ON `img_logo_style` = `logo_id` WHERE ( `img_dt_begin` <= "' . $t . '" ) AND ( `img_dt_end` >= "' . $t . '" ) ORDER BY `time_diff` ASC LIMIT 1';
        $r = $this->db->H->query( $sql );
        if ( $r->num_rows != 1 ) { return null; }
        $d = $r->fetch_assoc();
        return $this->translate( $d );
    }

    public function translate( $d ) {
        return array(
            'img_src' => URL_ASSETS_BGR . $d['img_hash'] . '.jpg',
            'logo_src' => URL_ASSETS_LOGO . $d['logo_hash'] . '.png',
            'color' => $d['img_color_query_color'],
            'bgr_color' => $d['img_color_query_bgr'],
            'border_color' => $d['img_color_query_border'],
            'slogan_color' => $d['img_color_slogan_color'],
            'copy_color' => $d['img_color_copy_color'],
            'menu_color' => $d['img_color_menu_color'],
            'hmenu_color' => $d['img_color_menu_hcolor'],
            'hmenu_bgr_color' => $d['img_color_menu_hbgr'],
        );
    }

    public function create_style() {
        $s = '';
        $s.= '            <style>' . PHP_EOL;
        $s.= '                #front_image{background-image:url(' . $this->d['img_src'] . ');}' . PHP_EOL;
        $s.= '                #front_query{color:' . $this->d['color'] . ';background-color:' . $this->d['bgr_color'] . ';}' . PHP_EOL;
        $s.= '                #front_slogan{color:' . $this->d['slogan_color'] . ';}' . PHP_EOL;
        $s.= '                #front_copyright{color:' . $this->d['copy_color'] . ';}' . PHP_EOL;
        $s.= '                .drop_button{background-color:' . $this->d['menu_color'] . ';}' . PHP_EOL;
        $s.= '                .drop_button:hover{background-color:' . $this->d['hmenu_bgr_color'] . ';color:' . $this->d['hmenu_color'] . ';}' . PHP_EOL;
        $s.= '                #drop_content a{background-color:transparent;color:' . $this->d['menu_color'] . ';}' . PHP_EOL;
        $s.= '                #drop_content a:hover{background-color:' . $this->d['hmenu_bgr_color'] . ';color:' . $this->d['hmenu_color'] . ';}' . PHP_EOL;
        $s.= '            </style>' . PHP_EOL;
        return $s;
    }

    public function __construct( Database $db ) {
        $this->db = $db;
        $this->d = $this->scan_for_timeline_image();
        if ( is_null( $this->d ) ) {
            $this->d = $this->scan_for_active_image();
            if ( is_null( $this->d ) ) {
            }
        }
        #$this->d = $this->scan_for_active_image();
        #if ( is_null( $this->d ) ) {
            # szukamy dalej
        #}
        if ( !is_null( $this->d ) ) {
            $this->d['Style'] = $this->create_style();
        }
    }

}

# EOF