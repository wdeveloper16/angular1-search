<?php defined('SM_DIR') or die;

/**
 * Deklaracja interfejsu komunikacji z bazą danych
 */
interface DBInterface {

    public function Connect( $params = null );
    public function Query( $q );

}