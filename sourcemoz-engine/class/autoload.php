<?php defined('SM_DIR') or die;

class Autoload {

    public function __construct() {
        spl_autoload_register( array( $this, 'autoload' ) );
    }

    /**
     * Próbuje załadować klasę
     * @param   string  $cls    Nazwa klasy
     * @return  bool    Zwraca true, gdy znajdzie odpowiedni plik, albo false jeżeli nie. Nie oznacza to oczywiście załadowania klasy a tylko odnalezienie pliku.
     */
    public function autoload( $cls ) {
        # zamieńmy nazwę klasy na małe litery
        $cls_lc = strtolower( $cls );
        # teraz sprawdźmy, czy klasa nie jest na liście najczęściej wywoływanych
        switch( $cls_lc ) {
            case 'singleton':
                $f = SM_DIR_PATTERN . $cls_lc . '.php';
                break;
            default:
                if ( substr( $cls_lc, -9 ) == 'interface' ) {
                    $f = SM_DIR_INTERFACE . str_replace( '_', '-', $cls_lc ) . '.php';
                } else {
                    $f = SM_DIR_CLASS . str_replace( '_', '-', $cls_lc ) . '.php';
                }
                break;
        }
        if ( is_readable( $f ) ) {
            require_once( $f );
            return true;
        }
        return false;
    }

}

new Autoload();