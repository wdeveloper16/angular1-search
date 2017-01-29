<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class VideoSearch {

	use GoogleSearch;

	public function get() {
		return $this->list;
	}

	public function __construct($term, $start = 0) {
		$this->set_constants();
		$this->query_google('youtube', $term);
	}
}