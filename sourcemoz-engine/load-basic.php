<?php defined('SM_DIR') or die;

/**
 * Zanim nawet będzie po pierwsze, musimy zadecydować, czy działamy
 * w trybie debugowania, czy nie... Jeżeli nie, to zmieniamy to na
 * false a nie usuwamy, rzecz jasna. Od razu też ustawimy ewentualne
 * raportowanie błędów, właśnie na podstawie tej stałej.
 */
define( 'SM_DEBUG', true );

if ( SM_DEBUG ) {
    error_reporting( E_ALL );
    ini_set( 'display_errors', 1 );
} else {
    error_reporting( 0 );
    ini_set( 'display_errors', 0 );
}


/**
 * Teraz, gdy wiemy najwazniejsze i mamy ustawione raportowanie błędów,
 * od razu włączamy:
 * - buforowanie tego, co skrypt wypluwa,
 * - obsługę sesji,
 * oraz ustawiamy domyślną strefę czasową na Amerykę, Nowy Jork. Przy
 * czym warto podkreślić, że potem z bazy możemy sobie wyczytać jakąś
 * inną strefę, czy to na podstawie IP czy danych użytkownika. Taka
 * możliwość, w każdym razie, istnieje.
 */
ob_start();
session_start();
date_default_timezone_set('America/New_York');

/**
 * po pierwsze, definicje najważniejszych stałych, które wskazują nam
 * adresy URL dla głównej wyszukiwarki, dla wywołań API (przyszłego)
 * oraz dla wywołań Ajax - jak najbardziej bieżąych.
 */
define( 'SM_URL', 'http://sourcemoz.com/lukasz-version-index.php' );
define( 'SM_URL_API', 'http://sourcemoz.com/lukasz-version-api.php' );
define( 'SM_URL_AJAX', 'http://sourcemoz.com/lukasz-version-ajax.php' );
define( 'SM_URL_INSTALL', 'http://sourcemoz.com/lukasz-version-install.php' );

/**
 * teraz możemy zdefiniować odwołania do obrazków, styli, skryptów
 */
define(    'SM_ASSETS_CSS', 'http://sourcemoz.com/lukasz-assets/css/' );
define(    'SM_ASSETS_IMG', 'http://sourcemoz.com/lukasz-assets/img/' );
define(    'SM_ASSETS_BGR', 'http://sourcemoz.com/lukasz-assets/bgr/' );
define(   'SM_ASSETS_LOGO', 'http://sourcemoz.com/lukasz-assets/logo/' );
define(     'SM_ASSETS_JS', 'http://sourcemoz.com/lukasz-assets/js/' );
define( 'SM_ASSETS_JQUERY', 'http://sourcemoz.com/lukasz-assets/jq/' );

/**
 * ważne są również ścieżki... ścieżka podstawowa do silnika jest już
 * zdefiniowana - SM_DIR, natomiast należy jeszcze dodać parę ścieżek
 */
define( 'SM_DIR_CLASS', SM_DIR . 'class/' );
define( 'SM_DIR_INTERFACE', SM_DIR . 'interface/' );
define( 'SM_DIR_PATTERN', SM_DIR . 'pattern/' );
define( 'SM_DIR_COOKIES', SM_DIR . 'cookies/' );

/**
 * No i po byku, teraz możemy sobie odpalić autoloadera dla klas... co
 * oszczędzi nam sporo pracy i nie będziemy musieli wszystkiego ciągle
 * includować a wręcz requireować, czy jak to tam nazwać po polszu.
 * Nie ma się co oszukiwać, autoloader to klasa a klasy mają swoje
 * miejsce, tak więc... Przy okazji - ta klasa odpala się sama z siebie.
 */
require_once( SM_DIR_CLASS . 'autoload.php' );

/**
 * No dobra, mamy już autoładowanie klas, teraz zdefiniujmy sobie dostęp
 * do bazy danych, to też jest istotne na tym poziomie. Ostatecznie każde
 * zwykłe wywołanie, każde API i Ajax - będzie wymagało odwołania do bazy
 * - a więc jest to wywołanie basic, co by nie mówić.
 */

define( 'SM_DATABASE', serialize( array(
      'Host' => 'localhost',
      'User' => 'usersmlu_rysio',
      'Pass' => 'ivoSo2stXcT3eqpi9yzmqs0coiowzDqeZ',
      'Name' => 'usersmlu_lvsm',
    'Prefix' => 'sm_',
) ) );

/**
 * Teraz zdefiniujemy sobie dwie podstawowe funkcje...
 * Jedną do obsługi baz danych a drugą - do obsługi opcji.
 */


/**
 * Funkcja zwraca uchwyt do klasy (singleton) zapewniającej komunikację z bazą danych.
 */
function DB() {
    return DBi::instance();
}


/**
 * Funkcja zwraca wartość opcji
 * @param   string  $slug   Nazwa opcji
 * @return  mixed Zwraca wartość opcji. Jeżeli takiej opcji nie ma, to zwraca null.
 */
function opt( $slug ) {
    return Options::instance()->get( $slug );
}

/**
 * To jest funkcja debugująca - pomocna w trakcie sprawdzania jak to
 * cholerstwo ma w ogóle działać...
 * @param   mixed   $v  Zmienna, którą chcemy wyświetlić, w zasadzie - cokolwiek
 * @param   string  $f  Opcjonalnie, plik, który wywołuje funkcję, zalecane: __FILE__
 * @param   string  $n  Opcjonalnie, nazwa zmiennej lub tytuł, który ma się wyświetlić
 * @param   bool    $fr Opcjonalnie, zmusza funkcję do wyświetlenia informacji, nawet gdy debugowanie jest wyłaczone. Domyślnie - false
 * @return  void
 */
function d( $v, $f = '', $n = '', $fr = false ) {
    if ( SM_DEBUG || $fr ) {
        echo '<!-- @@@ -->' . PHP_EOL . '<div style="width:98%;margin:.75em auto;display:block;background-color:#fff;color:#222;padding:.75em;">';
        if ( $f != '' ) { echo '<p style="margin:0;padding:0;border:0;font-size:14px;text-align:left;">In: <b>' . $f . '</b></p>' . PHP_EOL; }
        if ( $n != '' ) { echo '<p style="margin:0;padding:0;border:0;font-size:13px;text-align:left;">' . $n . '</p>' . PHP_EOL; }
        echo '<pre style="margin:.25em;padding:.35em;border:#ccc solid 1px;background-color:#f5f5f5;color:#111;">';
        if ( is_array( $v ) || is_object( $v ) ) { print_r( $v ); } else { var_dump( $v ); }
        echo '</pre>' . PHP_EOL . '</div>' . PHP_EOL;
    }
}

/**
 * I to by było na tyle, jeżeli chodzi o ładowanie podstawowe... To
 * wystarczy dla każdego rodzaju wywołania, nic więcej dodawać nie
 * trzeba...
 */