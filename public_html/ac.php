<?php
/**
 * Ten plik pozwala na uzyskanie mechanizmu autouzupełniania.
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

/**
 * Teraz ustalimy sobie parę stałych, aby łatwiej można było operować na
 * katalogach, plikach i tym podobnych.
 */
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

define(__NAMESPACE__ . '\PAGE_FRONT', 1);
define(__NAMESPACE__ . '\PAGE_SERP', 2);
define(__NAMESPACE__ . '\PAGE_STATIC', 3);

/**
 * Teraz załadujemy autoloader, który będzie za nas zajmował się klasami,
 * co jest niezwykle wygodne a bywa też przydatne, tak.
 */
require_once(APP . 'Autoloader.php');

$loader = new Autoloader();
$loader->register();
$loader->add_path(APP);

/**
 * Sprawdźmy, czy jest to mechanizm sesji...
 */
$ajax_test = new AjaxTest();
//if (!$ajax_test->test()) {
//	die('Internal error #060001');
//}
if (!isset($_GET['term']) || ($_GET['term'] == '')) {
	die('Internal error #060002');
}

function opt($key) {
	if (isset($_SESSION['Options'][$key])) {
		return $_SESSION['Options'][$key];
	}

	return null;
}

$server = new Server();
//$config = new Config();
$config = require_once('config.php');
$db     = new Database($config);
$opt    = new Options($db);
$c      = new ClearTerm($_GET['term']);

$a = new Autocomplete($db, $c->lower);


//$start = microtime(true);
//$url = "https://api.cognitive.microsoft.com/bing/v5.0/search?q=" . $_GET['term'];
$url = "https://api.cognitive.microsoft.com/bing/v5.0/suggestions/?q=" . $_GET['term'];
//				$url=str_replace('{keyword}', urlencode($_POST["searchText"]), $url);
$ch = curl_init();

//				$pass = 'keLrIGfWnlLLhfGCkgAG8/4ttnUtM4c2sFuF/BA8sM0';
//				$pass = 'c4cf5d75-a756-4c25-9c93-1ab0950f3cd4';
//$pass    = '87220c78c869460c8bda176fb7b75d18';
$pass    = 'ee2f8d5e08dd41939bfda956fbf3c00f';
$headers = [
	// Request headers
	'Ocp-Apim-Subscription-Key:'. $pass,
];

//				'count' => '10',
//    'offset' => '0',
//    'mkt' => 'en-us',
//    'safesearch' => 'Moderate',

//				$headers = array(
//					"Authorization: Basic " . base64_encode($credentials)
//				);

$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

//curl_setopt($ch, CURLOPT_FAILONERROR, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_AUTOREFERER, true);
//curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//				curl_setopt($ch, CURLOPT_USERPWD,  "$pass:$pass");

$rs = curl_exec($ch);


if (curl_exec($ch) === false) {
	echo 'Ошибка curl: ' . curl_error($ch);
}

//var_dump($rs);
curl_close($ch);

//echo microtime(true)-$start;
//exit();
$list = [];
$rs = json_decode($rs, true);
foreach($rs['suggestionGroups'] as $gr){
	foreach($gr['searchSuggestions'] as $row){
		$list[]=$row['displayText'];
	}
}
//echo $rs;


$ajax = new AjaxCall();
$ajax->set_status('1');
$ajax->set_data($list /*$a->list*/);
$ajax->response();