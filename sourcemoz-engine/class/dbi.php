<?php defined('SM_DIR') or die;

/**
 * Klasa zapewniająca komunikację ze sterownikiem bazy danych. Obecnie używamy MySQLi, ale
 * w przyszłości to się może zmienić, w zależności od potrzeb, wydajności i tym podobnych.
 * Dzięki tej klasie, nie powinny nam się zmieniać metody w innych miejscach skryptu a tylko
 * tutaj. Ba, nawet nie tylko tutaj - w zasadzie dążymy do tego, aby zadziałał interfejs DBi
 * Przy czym i oznacza tutaj interfejs a nie przypomina o mysqli.
 */
class DBi extends Singleton implements DBInterface {

    public $handler;
    public $params;
    public $ret;

    /**
     * Ta metoda łączy się z bazą danych.
     * @param   array   $params Tablica asocjacyjna, zawierająca Host, User, Pass, Name, Prefix, opcjonalnie
     * @return  bool    Zawsze zwraca true, bo jakby miało zwrócić false - to się zatrzyma skrypt i tak.
     */
    public function Connect( $params = null ) {
        # sprawdź, czy podano parametry, jeżeli nie - użyj stałej SM_DATABASE
        if ( is_null( $params ) ) {
            $this->params = unserialize( SM_DATABASE );
        } else {
            $this->params = $params;
        }
        # ustaw raportowanie wszystkich błędów mysqli oraz wysyłanie ich, jako wyjątków
        # oczywiście nie wolno używać MYSLI_REPORT_ALL bo wtedy np. próba odczytania
        # list tabel, będzie skutkowała ostrzeżeniem o braku indeksu... A więc do bani.
        # No i należy dostosować sposób wyświeltania w zależności od stałej SM_DEBUG.
        if ( defined( 'SM_DEBUG') && SM_DEBUG ) {
            mysqli_report( MYSQLI_REPORT_STRICT );
        } else {
            mysqli_report( MYSQLI_REPORT_OFF );
        }
        # teraz spróbuj się połączyć
        try {
            $this->handler = new mysqli(
                $this->params['Host'],
                $this->params['User'],
                $this->params['Pass'],
                $this->params['Name']
            );
        } catch( Exception $e ) {
            # połączenie się nie udało, wyzeruj bufor, wyświetl błąd i zakończ.
            ob_end_clean();
            die('Something went wrong, error code: #4001011.');
        }
        # ustal stałą DB_PREFIX
        if ( !defined('DB_PREFIX') ) {
            define( 'DB_PREFIX', $this->params['Prefix'] );
        }
        # ustaw parametry połączenia na unicode
        $this->handler->set_charset('utf8');
        return true;
    }

    /**
     * Ta funkcja pobiera całą zawartość tabeli. Uważaj gdy ją stosujesz,
     * to nie jest takie częste, aby była potrzebna cała tabela.
     */
    public function GetTable( $tbl_name, $order_by = '', $assoc_key = '' ) {
        if ( $order_by != '' ) { $order_by = ' ORDER BY ' . $order_by; }
        $q = 'SELECT * FROM `' . $tbl_name . '`' . $order_by .'';
        $this->Query( $q );
        echo $q;
    }

    /**
     * Funkcja wykonuje zapytanie oraz odczytuje wszystkie dane, które zostają
     * zwrócone.
     * @param   string  $q  Zapytanie
     * @return  array  Zwraca tablicę asocjacyjną z danymi, jeżeli ich nie ma, to pustą
     */
    public function QueryGetAssoc( $q ) {
        $w = array();
        $this->Query( $q );
        while( $d = $this->ret->fetch_assoc() ) {
            $w[] = $d;
        }
        return $w;
    }

    /**
     * Najbardziej podstawowa metoda wywołująca zapytanie do bazy danych
     * @param   string  $q  Treść zapytania, jakieś selecty, apdejty i im podobne.
     * @return  void    Jeżeli metoda się powiodła, to wynik jest w $this->ret a jeżeli nie, to skrypt się kończy
     */
    public function Query( $q ) {
        # na wszelki wypadek sprawdźmy, czy nie trzeba gdzieś wymienić prefiksu...
        $q = str_replace( '@PX_', DB_PREFIX, $q );
        try {
            $this->ret = mysqli_query( $this->handler, $q );
        } catch( Exception $e ) {
            # połączenie się nie udało, wyzeruj bufor, wyświetl błąd i zakończ.
            ob_end_clean();
            die('Something went wrong, error code: #4001012.');
        }
    }

    /**
     * Metoda wywoływana w trakcie pierwszej inicjalizacji obiektu singleton, tutaj
     * oczywiście odpala połączenie z bazą danych.
     */
    public function run() {
        $this->Connect();
        return;
    }

}