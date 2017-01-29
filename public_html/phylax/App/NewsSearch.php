<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class NewsSearch {

	use YahooSearch;

	public function get() {
		return $this->list;
	}

	public function __construct($term, $start = 0) {
		$this->set_constants();
		$this->query_boss('news', [
			'news.q'     => $term,
			'news.count' => opt('max_results_news'),
			'news.start' => $start,
			'format'     => 'json',
		]);
		if (!is_null($this->results) && ($this->results->news->totalresults == 0)) {
			$this->results = null;
		}
		if (!is_null($this->results) && ($this->results->news->totalresults > 0)) {
			$this->list = $this->results->news->results;
		}
	}
}