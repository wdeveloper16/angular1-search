<?php
/**
 * To główny plik, który zbiera główne wywołania stron. Nie obsługuje wywołań
 * poprzez ajax, do tego służą kolejne pliki - call-*.php.
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

date_default_timezone_set('America/New_York');

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

define(__NAMESPACE__ . '\BASE_DIR', '');
define(__NAMESPACE__ . '\PHYLAX', BASE_DIR . 'phylax' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\APP', PHYLAX . 'App' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\TEMPLATE', PHYLAX . 'Template' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\HTML', PHYLAX . 'HTML' . DIRECTORY_SEPARATOR);

define(__NAMESPACE__ . '\PAGE_FRONT', 1);
define(__NAMESPACE__ . '\PAGE_SERP', 2);
define(__NAMESPACE__ . '\PAGE_STATIC', 3);


require_once("phylax/App/" . 'Autoloader.php');
$config = require_once('config.php');
$loader = new Autoloader();
$loader->register();
$loader->add_path("phylax/App/");
$loader->add_path("phylax/Template/");
$loader->add_route('Mobile_Detect', PHYLAX . 'Mobile' . DIRECTORY_SEPARATOR . 'Mobile_Detect.php');
function opt($key) {
	if (isset($_SESSION['Options'][$key])) {
		return $_SESSION['Options'][$key];
	}

	return null;
}

new Server();
$db = new Database($config);
new SearchTerm();
new Options($db);

if (isset($_SESSION['SearchTerm'])) {
	new Menu();
}

require_once 'views/index.php';