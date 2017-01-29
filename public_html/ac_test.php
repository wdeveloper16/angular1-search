<?php

$start = microtime(true);
$list = ['Puti', 'Obam', 'Trum', 'Bank', 'Fishb', 'Cand'];
foreach ($list as $term){
//$start = microtime(true);
//$url = "https://api.cognitive.microsoft.com/bing/v5.0/search?q=" . $_GET['term'];
	$url = "https://api.cognitive.microsoft.com/bing/v5.0/suggestions/?q=" . $term;
//				$url=str_replace('{keyword}', urlencode($_POST["searchText"]), $url);
	$ch = curl_init();

//				$pass = 'keLrIGfWnlLLhfGCkgAG8/4ttnUtM4c2sFuF/BA8sM0';
//				$pass = 'c4cf5d75-a756-4c25-9c93-1ab0950f3cd4';
//$pass    = '87220c78c869460c8bda176fb7b75d18';
	$pass    = 'ee2f8d5e08dd41939bfda956fbf3c00f';
	$headers = [
// Request headers
'Ocp-Apim-Subscription-Key:' . $pass,
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
}

echo (microtime(true) - $start) / count($list);