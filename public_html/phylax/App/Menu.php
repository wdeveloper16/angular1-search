<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Menu {

    public $items;
    public $matches;

    public function get_menu_string() {
	    global $config;

        if ( !isset( $_SESSION['MenuString'] ) ) {
            $s = '';
            foreach( $this->items as $item ) {
                $adds = '&m=' . $config['Device']['M'];
                if ( $item['AddParam'] ) {
                    $adds.= '&t=' . $item['Recognize'];
                }
                $s.= '                        <li><a id="' . $item['ID'] . '" class="tab_item%' . $item['ID'] . '%" href="' . URL_HOME . '?q=' . '@query@' . $adds . '">' . $item['Label'] . '</a></li>' . PHP_EOL;
            }
            $_SESSION['MenuString'] = $s;
        }
        #$_SESSION['CurrentMenuString'] = str_replace( '@query@', urlencode( $_SESSION['SearchTerm']['Clear'] ), $_SESSION['MenuString'] );
    }

    public function add_menu( $key, $args ) {
        $args['ID'] = $key;
        $this->items[ $key ] = $args;
        $this->matches[ $args['Recognize'] ] = $key;
    }

    public function __construct() {
        $this->items = array();
        $this->matches = array();
        if ( !isset( $_SESSION['Menu'] ) ) {
            if ( opt('show_tab_web') ) {
                $this->add_menu( 'tab_web', array(
                    'Recognize' => '',
                    'AddParam' => false,
                    'Class' => 'SERPWebPage',
                    'Label' => 'Web',
                ) );
            }
            if ( opt('show_tab_images') ) {
                $this->add_menu( 'tab_images', array(
                    'Recognize' => 'i',
                    'AddParam' => true,
                    'Class' => 'SERPImagesPage',
                    'Label' => 'Images',
                ) );
            }
            if ( opt('show_tab_video') ) {
                $this->add_menu( 'tab_video', array(
                    'Recognize' => 'v',
                    'AddParam' => true,
                    'Class' => 'SERPVideoPage',
                    'Label' => 'Videos',
                ) );
            }
            if ( opt('show_tab_news') ) {
                $this->add_menu( 'tab_news', array(
                    'Recognize' => 'n',
                    'AddParam' => true,
                    'Class' => 'SERPNewsPage',
                    'Label' => 'News',
                ) );
            }
            if ( opt('show_tab_map') ) {
                $this->add_menu( 'tab_map', array(
                    'Recognize' => 'm',
                    'AddParam' => true,
                    'Class' => 'SERPMapPage',
                    'Label' => 'Maps',
                ) );
            }
            if ( opt('show_tab_shopping') ) {
                $this->add_menu( 'tab_shopping', array(
                    'Recognize' => 's',
                    'AddParam' => true,
                    'Class' => 'SERPShoppingPage',
                    'Label' => 'Shopping',
                ) );
            }
            $_SESSION['Menu']['Items'] = $this->items;
            $_SESSION['Menu']['Matches'] = $this->matches;
        } else {
            $this->items = $_SESSION['Menu']['Items'];
            $this->matches = $_SESSION['Menu']['Matches'];
        }
        $this->get_menu_string();
    }

}

# EOF