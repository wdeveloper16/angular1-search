<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Server {

    public $url;

    protected $server_names = null;

    public function __construct() {
        if ( !isset( $_SESSION['Server'] ) ) {
            $this->server_names = array(
//                'sourcemoz.com' => array(
//                    'Config' => PHYLAX . 'Config' . DIRECTORY_SEPARATOR . 'production.config.php',
//                ),
//                'sourcemoz.lc'  => array(
//                    'Config' => PHYLAX . 'Config' . DIRECTORY_SEPARATOR . 'local.config.php',
//                ),
                '45.55.100.187'  => array(
                    'Config' => PHYLAX . 'Config' . DIRECTORY_SEPARATOR . 'local.config.php',
                ),
//                '107.170.83.155'  => array(
//                    'Config' => PHYLAX . 'Config' . DIRECTORY_SEPARATOR . 'local.config.php',
//                ),
//                'sourcemoz.geoarea.com'  => array(
//                    'Config' => PHYLAX . 'Config' . DIRECTORY_SEPARATOR . 'geoarea.config.php',
//                ),
            );
            foreach( $this->server_names as $server_name => $server_data ) {
                if ( $server_name == $_SERVER['SERVER_NAME'] ) {
                    $_SESSION['Server'] = $server_data;
                }
            }
            $hash = new IPHash();
            $_SESSION['Server']['IPHash'] = $hash->ip;
        }
    }

}

# EOF