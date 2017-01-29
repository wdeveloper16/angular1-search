<?php

class SearchTest extends IntegrationTestBase {

	/**
	 * @test
	 */
	function get_noTerm() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=web&term=');
		$this->assertEquals('Internal error #060005', $results);
//		$json    = json_decode($results, true);

//		$this->assertEquals('1', $json['S']);
//		$this->assertTrue(count($json['D']) > 0);

	}

	/**
	 * @test
	 */
	function get_noType() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=&term=term');
		$this->assertEquals('Internal error #060006', $results);
//		$json    = json_decode($results, true);

//		$this->assertEquals('1', $json['S']);
//		$this->assertTrue(count($json['D']) > 0);

	}

	/**
	 * @test
	 */
	function get_WebPutin_HasImagesAndWebResults() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=web&term=Putin');
		$json    = json_decode($results, true);
		$this->assertTrue(count($json['webPages']['value']) > 0);
		$this->assertTrue(count($json['images']['value']) > 0);

	}

	/**
	 * @test
	 */
	function get_SocialGaga_HasNoDuplicateFBlinks() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=social&term=Lady%20Gaga');
		$json    = json_decode($results, true);

		$displayLinks = array_map(function ($r) {
			return $r['displayUrl'];
		}, $json['webPages']['value']);

//		print_r($displayLinks);

		$this->assertTrue(!in_array(
			'https://es-la.connect.connect.connect.facebook.com/ladygaga',
			$displayLinks
		));

		$this->assertTrue(count($displayLinks) < 49);

	}

	/**
	 * @test
	 */
	function get_SocialResults_HasFacebookAvatar() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=social&term=Monsters%20Inc');
		$json    = json_decode($results, true);

		$this->assertEquals(
			'http://graph.facebook.com/PixarMonstersInc/picture',
			$json['webPages']['value'][0]['avatarUrl']
		);

	}

	/**
	 * @test
	 */
	function get_SocialResults_HasNoDuplicate_Pinterestlinks() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=social&term=Monsters%20Inc');

		$json = json_decode($results, true);

		$displayLinks = array_map(function ($r) {
			return $r['displayUrl'];
		}, $json['webPages']['value']);

		$this->assertTrue(!in_array('nl.pinterest.com/Avster07/мonѕтerѕ-ιnc', $displayLinks));
		$this->assertTrue(!in_array('dk.pinterest.com/explore/monster-university', $displayLinks));
		$this->assertTrue(!in_array('no.pinterest.com/explore/monsters-inc', $displayLinks));

		$this->assertTrue(count($displayLinks) < 49);

	}

	/**
	 * @test
	 */
	function get_SocialResults_HasNoDuplicate_Linkedinlinks() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=social&term=John%20Kennedy');

		$json = json_decode($results, true);

		$displayLinks = array_map(function ($r) {
			return $r['displayUrl'];
		}, $json['webPages']['value']);

		print_r($displayLinks);

		$this->assertTrue(!in_array('zw.linkedin.com/in/john-kennedy-gore-23129622', $displayLinks));
		$this->assertTrue(!in_array('gh.linkedin.com/in/john-kennedy-15946329', $displayLinks));
		$this->assertTrue(!in_array('ae.linkedin.com/in/john-d-kennedy-8394349', $displayLinks));

		$this->assertTrue(count($displayLinks) < 49);
	}

	/**
	 * @test
	 */
	function get_SocialPutin_HasImagesAndWebResults() {
		$results = $this->curlGET($this->baseURL . '/search.php?type=social&term=Putin');
		$json    = json_decode($results, true);
		$this->assertTrue(count($json['webPages']['value']) > 0);
	}

	/**
	 * @test
	 */
	function get_WikipediaPutin_HasText() {
		$results = $this->curlPostRawJson($this->baseURL . '/search.php?type=wikipedia&term=Putin', json_encode([
			'lang'       => 'en',
			'title'      => 'Vladimir_Putin',
			'wiki_title' => 'Vladimir Putin',
		]));
		$json    = json_decode($results, true);

		$this->assertContains('Russian Federation', $json['extract']);

	}

	/**
	 * @test
	 */
	function get_WikipediaPutin_HasImages() {
		$results = $this->curlPostRawJson($this->baseURL . '/search.php?type=wikiimages&term=Putin', json_encode([
			'lang'   => 'en',
			'pageid' => 32817
		]));
		$json    = json_decode($results, true);
		$this->assertTrue(count($json) > 10);
	}

	/**
	 * @test
	 */
	function get_WikipediaLohan_HasImages() {
		$results = $this->curlPostRawJson($this->baseURL . '/search.php?type=wikiimages&term=Lohan', json_encode([
			'lang'   => 'en',
			'pageid' => 8490390
		]));
		$json    = json_decode($results, true);
		print_r($json);
//		$this->assertTrue(count($json)>10);

	}
}