<?php
namespace Phylax\SourceMoz;

class Search {

	public $html;
	public $anything;
	public $dmtoken;
	public $wiki_info;

	function cleanPagesURLsFromBing($response) {
		//cleanup target URL from bing's server
		$response = json_decode($response, true);
		if (isset($response['webPages']) && isset($response['webPages']['value'])) {
			foreach ($response['webPages']['value'] as &$r) {
//				$r['url_original'] = $r['url'];

				$r['url'] = explode('&r=', $r['url'])[1];
				$r['url'] = explode('&p=', $r['url'])[0];
				$r['url'] = urldecode($r['url']);
			}
		}
		$response = json_encode($response);

		return $response;
	}

	function cleanObjectsURLsFromBing($response, $key) {
		$response = json_decode($response, true);

		if (isset($response['value'])) {
			foreach ($response['value'] as &$r) {
//				$r['' + $key + '_original'] = $r[$key];

				$r[$key] = explode('&r=', $r[$key])[1];
				$r[$key] = explode('&p=', $r[$key])[0];
				$r[$key] = urldecode($r[$key]);
			}
		}
		$response = json_encode($response);

		return $response;
	}

	public function __construct($term, $where) {
		$this->anything  = false;
		$this->wiki_info = null;

		switch ($where) {
			case 'web':
				$s        = new BingSearch();
				$response = $s->curlGetApi5($term, '084679e7200f46cb9a900c3e870baceb');
				echo $this->cleanPagesURLsFromBing($response);
				break;

			case 'images':
				$s        = new BingSearch();
				$response = $s->curlGetApi5($term, '084679e7200f46cb9a900c3e870baceb', 'https://api.cognitive.microsoft.com/bing/v5.0/images/search?');
				$response = $this->cleanObjectsURLsFromBing($response, 'contentUrl');
				$response = $this->cleanObjectsURLsFromBing($response, 'hostPageUrl');
				echo $response;
				break;

			case 'videos':
				$s        = new BingSearch();
				$response = $s->curlGetApi5($term, '084679e7200f46cb9a900c3e870baceb', 'https://api.cognitive.microsoft.com/bing/v5.0/videos/search?');
				echo $this->cleanObjectsURLsFromBing($response, 'hostPageUrl');
				break;

			case 'news':
				$s        = new BingSearch();
				$response = $s->curlGetApi5($term, '084679e7200f46cb9a900c3e870baceb', 'https://api.cognitive.microsoft.com/bing/v5.0/news/search?');
				echo $this->cleanObjectsURLsFromBing($response, 'url');
				break;

			case 'social':
				$s      = new BingSearch();
				$titles = 'Facebook,Twitter,Google+,Pinterest,Instagram,Tumblr,Quora,Flickr,Linkedin,Last.fm,Reddit,Stumbleupon,Delicious,Digg,Foursquare,Vine';

				$result = $s->curlGetApi5($term, 'fd8520aaab764ce5b0e52fc6bd711da1', 'https://api.cognitive.microsoft.com/bing/v5.0/search?', $titles);

				$result = $this->cleanPagesURLsFromBing($result);

				//eliminate duplicates
				$json      = json_decode($result, true);
				$addedURLs = [];
				foreach ($json['webPages']['value'] as $k => &$row) {
					$row['displayUrl'] = str_replace('https://', '', $row['displayUrl']);

					$matchesLinkedinSubdomains  = preg_match('/^(.*).linkedin.com(.*)/i', $row['displayUrl']);
					$matchesFacebookSubdomains  = preg_match('/^(.*).facebook.com(.*)/i', $row['displayUrl']);
					$matchesPinterestSubdomains = preg_match('/^(.*).pinterest.com(.*)/i', $row['displayUrl']);

					$matchesLinkedin  = preg_match('/^(www\.)?linkedin.com(.*)/i', $row['displayUrl']);
					$matchesFacebook  = preg_match('/^(www\.)?facebook.com(.*)/i', $row['displayUrl']);
					$matchesPinterest = preg_match('/^(www\.)?pinterest.com(.*)/i', $row['displayUrl']);

					if ($matchesLinkedinSubdomains && !$matchesLinkedin ||
						$matchesFacebookSubdomains && !$matchesFacebook ||
						$matchesPinterestSubdomains && !$matchesPinterest
					) {
						unset($json['webPages']['value'][$k]);
						continue;
					}

					$this->detectAvatars($row);


					if (in_array($row['displayUrl'], $addedURLs)) {
						unset($json['webPages']['value'][$k]);
					}
					else {
						$addedURLs[] = $row['displayUrl'];
					}
				}

				echo json_encode($json);

				break;

			case 'wikipedia':
				$s = new WikiSearch();
				echo json_encode($s->get(), true);

				break;

			case 'wikiimages':
				$s = new WikiSearch();
				echo json_encode($s->getWikiImages(), true);

				break;
		}

		exit();
	}

	/**
	 * @param array $row
	 *
	 * @return mixed
	 */
	private function detectAvatars(&$row) {
		preg_match('/(.*)facebook.com\/([a-z0-9-_\.]*)/i', $row['displayUrl'], $avatarMatchFB);
		preg_match('/(.*)twitter.com\/([a-z0-9-_\.]*)/i', $row['displayUrl'], $avatarMatchTwitter);

		if ($avatarMatchFB && isset($avatarMatchFB[2]) && $avatarMatchFB[2] != 'people') {
			$row['avatarUrl'] = 'http://graph.facebook.com/' . $avatarMatchFB[2] . '/picture';
		}
		else if ($avatarMatchTwitter && isset($avatarMatchTwitter[2])) {
			$row['avatarUrl'] = 'https://twitter.com/' . $avatarMatchTwitter[2] . '/profile_image?size=bigger';
		}

		return $row;
	}
}