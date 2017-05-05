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

if (!isset($_GET['term']) || ($_GET['term'] == '')) {
	die('Internal error #060002');
}

$term = $_GET['term'];

$config = require_once('config.php');
$db     = new Database($config);

$data = Array();
$result = $db->query("SELECT ac_word FROM `sm_autocomplete` WHERE `ac_word` LIKE '{$term}%' ORDER BY `ac_count` DESC LIMIT 10");
while($row = $result->fetch_assoc()) {
    $data[] = $row["ac_word"];
}
echo json_encode($data);
