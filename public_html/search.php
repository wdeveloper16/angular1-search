<?php
/**
 * Ten plik pozwala na uzyskanie mechanizmu autouzupełniania.
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

$start = microtime(true);

$b = trim(dirname(__FILE__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
if (substr($b, 0, 5) == 'home/') {
	$b = '/' . $b;
}
$b = "";
define(__NAMESPACE__ . '\BASE_DIR', $b);
define(__NAMESPACE__ . '\PHYLAX', BASE_DIR . 'phylax' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\APP', PHYLAX . 'App' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\TEMPLATE', PHYLAX . 'Template' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\HTML', PHYLAX . 'HTML' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\GOOGLE', PHYLAX . 'Google' . DIRECTORY_SEPARATOR);
define(__NAMESPACE__ . '\PAGE_FRONT', 1);
define(__NAMESPACE__ . '\PAGE_SERP', 2);
define(__NAMESPACE__ . '\PAGE_STATIC', 3);

require_once(APP . 'Autoloader.php');
$config = require_once('config.php');

$loader = new Autoloader();
$loader->register();
$loader->add_path(APP);
$loader->add_route('OAuthConsumer', PHYLAX . 'OAuth' . DIRECTORY_SEPARATOR . 'oauth.php');

$ajax_test = new AjaxTest();
if (!isset($_GET['term']) || ($_GET['term'] == '')) {
	die('Internal error #060005');
}
if (!isset($_GET['type']) || ($_GET['type'] == '')) {
	die('Internal error #060006');
}
$callType = strtolower($_GET['type']);
if (!in_array($callType, ['web', 'wikipedia', 'videos', 'news', 'images', 'social','wikiimages'])) {
	die('Internal error #060007');
}

function opt($key) {
	if (isset($_SESSION['Options'][$key])) {
		return $_SESSION['Options'][$key];
	}

	return null;
}

$server       = new Server();
$db           = new Database($config);
$opt          = new Options($db);
$term         = new ClearTerm($_GET['term']);
$search       = new Search($term->term, $callType);