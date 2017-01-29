<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class BingSearch {

	public $results = [];
	public $dmtoken = '';
	public $list = [];
	public $wiki_info = null;

	public function set_constants() {
		define('YAHOO_BOSS', 'http://yboss.yahooapis.com/ysearch/');
		define('CONSUMER_KEY', 'dj0yJmk9NFBUOE42engwSWE2JmQ9WVdrOVdVMXliblZJTlRBbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD03Zg--');
		define('CONSUMER_SECRET', '087f383d88d906e0cb2c1d84331d4c3aebd023e3');
	}

	public function removeDuplicateResults() {
		$nl = [];
		foreach ($this->list as $key => $item) {
			# na początku stwórz pole unique_url, które będzie służyć do porównywania
			# duplikatów i jednocześnie zmień wszystki https na http, bo są różne.
			$this->list[$key]->unique_url = str_replace('https://', 'http://', $item->url);
			# teraz usuń /events, jeżeli jest na końcu
			if (substr($this->list[$key]->unique_url, -7) == '/events') {
				$this->list[$key]->unique_url = substr($this->list[$key]->unique_url, 0, -7);
			}
			# usuń /likes, jeżeli jest na końcu
			if (substr($this->list[$key]->unique_url, -6) == '/likes') {
				$this->list[$key]->unique_url = substr($this->list[$key]->unique_url, 0, -6);
			}
			# usuń /timeline/ (zostaw /), jeżeli jest na końcu
			if (substr($this->list[$key]->unique_url, -10) == '/timeline/') {
				$this->list[$key]->unique_url = substr($this->list[$key]->unique_url, 0, -9);
			}
			# sprawdź, czy w adresie jest ? a jeśli tak, to usuń wszystko co dalej
			if (strpos($this->list[$key]->unique_url, '?') !== false) {
				$this->list[$key]->unique_url = explode('?', $this->list[$key]->unique_url)[0];
			}
			# teraz wpisz item do nowej listy
			$nl[$this->list[$key]->unique_url] = $this->list[$key];
			unset($nl[$this->list[$key]->unique_url]->unique_url);
		}
		# przekaż z powrotem na listę
		$this->list = array_values($nl);
	}

	/**
	 * Gets the information from the server API.
	 * Notes: we will use <b>Bing Search API</b> only for images. For web and social column
	 * we will use <b>Web Results Only</b>
	 *
	 * @param string $service comma separated values with the search types (eg. web,ads)
	 * @param array $args array with search values and options(eg. web.q, web.count, etc. see WebSearch.php)
	 *
	 * @link https://datamarket.azure.com/dataset/explore/bing/searchweb
	 * @link https://datamarket.azure.com/dataset/explore/bing/search
	 *
	 * @link http://stackoverflow.com/questions/10844463/bing-search-api-and-azure
	 * @link http://stackoverflow.com/questions/11451178/new-bing-api-php-example-doesnt-work
	 * @link https://msdn.microsoft.com/en-us/library/ff795613.aspx
	 * @link http://vlaurie.com/computers2/Articles/bing_advanced_search.htm
	 * @link https://xyang.me/using-bing-search-api-in-python/
	 * @link https://github.com/deboorn/BingSearchAPI/blob/master/BingSearch.php
	 */
	public function queryApiV1($service, $args) {

		// Sets the results as null
		$this->results = null;

		// Gets the service search types
		$search_types = explode(",", $service);

		// Primary Account Key from Bing Search API
		// url information: https://datamarket.azure.com/dataset/explore/bing/searchweb
		$accountKey = 'keLrIGfWnlLLhfGCkgAG8/4ttnUtM4c2sFuF/BA8sM0';

		if (in_array("images", $search_types)) {

			// url of the API (Bing Search API)
			$serviceRootURL = 'https://api.datamarket.azure.com/Bing/Search/v1/';

			// url and params
			$webSearchURL = $serviceRootURL . 'Image?$format=' . $args['format'] . '&$top=' . $args['images.count'] . '&Query=';

			// url and query
			$request = $webSearchURL . "%27" . urlencode($args['images.q']) . "%27";

			$response = $this->curlGetApi1($request, $accountKey);

			// Loops through all the results in order to set the required format of results
			foreach ($response->d->results as $result) {

				// Creates an empty object
				$object = (object)[];

				//Sets required values
				$object->title           = $result->Title;
				$object->clickurl        = $result->MediaUrl;
				$object->refererclickurl = $result->SourceUrl;
				$object->refererurl      = $result->MediaUrl;
				$object->url             = $result->MediaUrl;
				$object->format          = str_replace('image/', '', $result->ContentType);
				$object->size            = number_format($result->FileSize / 1024, 1) . ' KB';
				$object->height          = $result->Height;
				$object->width           = $result->Width;
				$object->thumbnailheight = $result->Thumbnail->Height;
				$object->thumbnailwidth  = $result->Thumbnail->Width;
				$object->thumbnailurl    = $result->Thumbnail->MediaUrl;

				//file_put_contents('/var/www/html/test-errors.log', serialize($object), FILE_APPEND);

				// Adds the temporary object to the results
				$this->results[] = $object;
			}
		}

		if (in_array("web", $search_types)) {

			// url of the API (Web Results Only)
			$serviceRootURL = 'https://api.datamarket.azure.com/Bing/SearchWeb/v1/';

			// url and params
			$webSearchURL = $serviceRootURL . 'Web?$format=' . $args['format'] . '&$top=' . $args['web.count'] . '&Query=';

			$sites = "";
			// Checks if theres a white list of available sites to search
			// Ref: https://msdn.microsoft.com/en-us/library/ff795613.aspx
			if (array_key_exists('web.sites', $args)) {

				// Gets the sites separated by comma
				$sites = explode(",", $args['web.sites']);


				$sites = array_map(function ($val) {
					return 'site:' . $val;
				}, $sites);

				$sites = '(' . implode(" OR ", $sites) . ')';

				$sites = " " . $sites;

			}

			// url and query
			$request = $webSearchURL . "%27" . urlencode($args['web.q']) . urlencode($sites) . "%27";

			$response = $this->curlGetApi1($request, $accountKey);

			//file_put_contents('/var/www/html/test-errors.log', $response, FILE_APPEND);

			// Loops through all the results in order to set the required format of results
			foreach ($response->d->results as $result) {

				// Creates an empty object
				$object = (object)[];

				//Sets required values
				$object->clickurl = $result->Url;
				$object->url      = $result->Url;
				$object->title    = $result->Title;
				$object->dispurl  = $result->DisplayUrl;
				$object->abstract = $result->Description;

				// Adds the temporary object to the results
				$this->results[] = $object;
			}
		}
	}


	public function curlGetApi1($request, $accountKey) {
		// Curl call to the API
		$process = curl_init($request);
		curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($process, CURLOPT_USERPWD, "$accountKey:$accountKey");
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($process);

		//file_put_contents('/var/www/html/test-errors.log', $response, FILE_APPEND);

		// JSON encoded string to PHP variable
		$response = json_decode($response);

		return $response;
	}

	public function curlGetApi5(
		$term,
//		$accountKey = '691bea0ee910474dae5310545a9d0ebc',
		$accountKey = '084679e7200f46cb9a900c3e870baceb',
		$url = "https://api.cognitive.microsoft.com/bing/v5.0/search?responseFilter=webPages,images",
		$sites = false) {


		// Checks if theres a white list of available sites to search
		// Ref: https://msdn.microsoft.com/en-us/library/ff795613.aspx
		if ($sites) {
//
//			// Gets the sites separated by comma
			$sites = explode(",", $sites);
			$sites = array_map(function ($val) {
				return 'intitle:' . $val;
			}, $sites);
			$sites = '(' . implode(" OR ", $sites) . ')';
//			$sites = " site:" . $sites;
			$term = $term . " " . $sites;
		}


		$url .= '&count=50&q=' . urlencode($term) . '';
		// url and query

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Ocp-Apim-Subscription-Key:' . $accountKey,
		]);

		$response = curl_exec($ch);
		if (curl_exec($ch) === false) {
			echo 'Ошибка curl: ' . curl_error($ch);
		}

		curl_close($ch);

		return $response;
	}
}