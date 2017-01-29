<?php defined('SM_DIR') or die;

/**
 * Dzięki tej klasie, zarządzamy opcjami i ustawieniami systemu. Klasa zapewnia
 * dodawanie, usuwanie, ustawianie opcji. Klasa przy pierwszym uruchomieniu czyta
 * wszystkie opcje z bazy i zapisuje je w zmiennej - potem zapewnia dostęp do tych
 * opcji poprzez zmienną. Jeżeli od klasy zażądamy usunięcia, dodania lub zmiany
 * wartości opcji, dane zostaną zapisane do bazy. Samo pobranie zmiennych nie
 * powoduje komunikacji z bazą.
 */
class Options extends Singleton {

    public function Get( $slug ) {
    }

    public function run() {
        #DB()->GetTable( DB_PREFIX . 'options' );
    }


}