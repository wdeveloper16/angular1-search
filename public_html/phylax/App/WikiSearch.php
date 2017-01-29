<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class WikiSearch {
	public $db;
	public $term;


	public $wiki_info;
	public $wiki_image_main = null;
	public $wiki_image_rest = null;
	public $img_names;
	public $results;
	public $resultWikiImages;

	protected $wiki_thumb_size = 180;
	protected $wikiImagesHeight = 150;

	public function read($url) {
		$d = @file_get_contents($url);
		if (is_string($d) && (strlen($d) > 0) && ($d{0} == '{')) {
			return json_decode($d, true);
		}

		return null;
	}

	public function wiki_text_url($info) {
		return 'https://' . $info['lang'] . '.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&redirects=true&titles=' . urlencode($info['title']);
	}

	public function wiki_main_image_url($pageID, $lang = 'en', $height = 80) {
		return 'https://' . $lang . '.wikipedia.org/w/api.php?action=query&prop=pageimages&format=json&pithumbsize=' . $height . '&redirects=true&pageids=' . $pageID;
	}

	public function wiki_image_list_url($lang = 'en', $pageID) {
		return 'https://' . $lang . '.wikipedia.org/w/api.php?action=query&prop=images&format=json&imlimit=50&redirects=true&pageids=' . $pageID;
	}

	public function wiki_image_info($info, $title, $height = 90) {
		return 'https://' . $info['lang'] . '.wikipedia.org/w/api.php?action=query&prop=imageinfo&format=json&iiprop=url&iiurlheight=' . $height . '&titles=' . urlencode($title);
	}


	public function get() {
		$postdata = file_get_contents("php://input");
		$wikiInfo = json_decode($postdata, true);

		if (!is_null($wikiInfo)) {
			$this->term = $wikiInfo['wiki_title'];
			$d          = $this->read($this->wiki_text_url($wikiInfo));
			$d          = $d['query']['pages'];

			return array_values($d)[0];
		}
		else {
			return [];
		}
	}

	public function getWikiImages() {
		$postdata = file_get_contents("php://input");
		$wikiInfo = json_decode($postdata, true);


		$mainImage = $this->read($this->wiki_main_image_url($wikiInfo['pageid'], $wikiInfo['lang'], 180));

		$imageTitles = $this->read($this->wiki_image_list_url($wikiInfo['lang'], $wikiInfo['pageid']));
		$titles      = [];
		foreach ($imageTitles['query']['pages'][$wikiInfo['pageid']]['images'] as $row) {
			$titles[] = $row['title'];
		}

		$url = 'https://' . $wikiInfo['lang'] . '.wikipedia.org/w/api.php?action=query&prop=imageinfo&format=json&iiprop=url&iiurlheight=' . 180 . '&titles=' . urlencode(implode('|', $titles));

		$d = $this->read($url);

		$pages = $d['query']['pages'];
		$r     = [];

		$page2 = reset($mainImage['query']['pages']);
		if ($page2 && $page2['thumbnail']['source']) {
			$r[] = $page2['thumbnail']['source'];
		}

		foreach ($pages as $page) {
			$url = $page['imageinfo'][0]['thumburl'];
			if (strpos($url, 'upload.wikimedia.org') > 0 &&
				!strpos($url, 'Wikiquote') === false &&
				!strpos($url, 'Commons-logo') === false &&
				!strpos($url, 'Symbol_support_vote') === false
			) {
				$r[] = $url;
			}
		}

		return $r;
	}
}