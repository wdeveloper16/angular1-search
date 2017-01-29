<?php defined('SM_DIR') or die;

class Singleton {

    private static $instances;

    private function __construct() {}

    final private function __clone() {}

    public static function instance() {
        $class = get_called_class();
        if ( !isset( self::$instances[ $class ] ) ) {
            self::$instances[ $class ] = new $class;
            if ( method_exists( self::$instances[ $class ], 'run' ) ) {
                self::$instances[ $class ]->run();
            }
        }
        return self::$instances[ $class ];
    }

}