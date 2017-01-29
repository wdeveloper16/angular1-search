<?php
/**
 * Ten plik pozwala na zapisywanie anonimowych statystyk
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

/**
 * Teraz ustalimy sobie parę stałych, aby łatwiej można było operować na
 * katalogach, plikach i tym podobnych.
 */
$b = trim( dirname( __FILE__ ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
if ( substr( $b, 0, 5 ) == 'home/' ) { $b = '/' . $b; }

define( __NAMESPACE__ . '\BASE_DIR', $b );
define( __NAMESPACE__ . '\PHYLAX', BASE_DIR . 'phylax' . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\APP' , PHYLAX . 'App' . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\TEMPLATE' , PHYLAX . 'Template' . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\HTML' , PHYLAX . 'HTML' . DIRECTORY_SEPARATOR );

define( __NAMESPACE__ . '\PAGE_FRONT',  1 );
define( __NAMESPACE__ . '\PAGE_SERP',   2 );
define( __NAMESPACE__ . '\PAGE_STATIC', 3 );

/**
 * Teraz załadujemy autoloader, który będzie za nas zajmował się klasami,
 * co jest niezwykle wygodne a bywa też przydatne, tak.
 */
require_once( APP . 'Autoloader.php' );

$loader = new Autoloader();
$loader->register();
$loader->add_path( APP );

/**
 * Sprawdźmy, czy jest to mechanizm sesji...
 */
$ajax_test = new AjaxTest();
if ( !$ajax_test->test() ) { die('Internal error #060001'); }
if ( !isset( $_GET['u'] ) || ( $_GET['u'] == '' ) ) { die('Internal error #060008'); }

function opt( $key ) {
    if ( isset( $_SESSION['Options'][ $key ] ) ) {
        return $_SESSION['Options'][ $key ];
    }
    return null;
}

$server = new Server();
$config = new Config();
    $db = new Database();
   $opt = new Options( $db );

$thumb = new Icon( $db, $_GET['u'] );


$ajax = new AjaxCall();
if ( is_array( $thumb->icon_set ) && ( count( $thumb->icon_set ) > 0 ) ) {
    $ajax->set_status( '1' );
    $ajax->set_data( $thumb->icon_set );
} else {
    $ajax->set_status( '0' );
}

$ajax->response();

die();

# EOF