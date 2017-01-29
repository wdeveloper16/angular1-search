<?php

namespace Phylax\SourceMoz;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = [
	'DB'  => [
		'Host'     => 'localhost',
		'User'     => 'sourcemozdb',
		'Password' => 'ay90gB%8',
		'Name'     => 'admin_smoz',
		'Prefix'   => 'sm_',
		'Charset'  => 'utf8',
	],
	'URI' => [
		'Home' => 'http://sourcemoz.geoarea.com/',
	],
];