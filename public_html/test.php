<?php

//print $_SERVER['SERVER_NAME'];

namespace Phylax\SourceMoz;


$test  =trim( dirname( __FILE__ ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

$b = trim( dirname( __FILE__ ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
if ( substr( $b, 0, 5 ) == 'home/' ) { $b = '/' . $b; }

define( __NAMESPACE__ . '\BASE_DIR', $b );
define( __NAMESPACE__ . '\PHYLAX', BASE_DIR . 'phylax' . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\APP' , PHYLAX . 'App' . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\TEMPLATE' , PHYLAX . 'Template' . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\HTML' , PHYLAX . 'HTML' . DIRECTORY_SEPARATOR );

var_dump($b);
var_dump(BASE_DIR);
var_dump(PHYLAX);
var_dump(APP);
var_dump(TEMPLATE);
var_dump(HTML);


?>

