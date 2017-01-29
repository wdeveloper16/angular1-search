<?php

class AutocompleteTest extends IntegrationTestBase {

	/**
	 * @test
	 */
	function get_noTerm() {
		$results = $this->curlGET($this->baseURL . '/ac.php?term=');
		$this->assertEquals('Internal error #060002', $results);
//		$json    = json_decode($results, true);

//		$this->assertEquals('1', $json['S']);
//		$this->assertTrue(count($json['D']) > 0);

	}

	/**
	 * Testing autocomplete for Obama
	 * @test
	 */
	function get() {
		$results = $this->curlGET($this->baseURL . '/ac.php?term=Oba');
		$json    = json_decode($results, true);

		$this->assertEquals('1', $json['S']);
		$this->assertTrue(count($json['D']) > 0);

	}

	/**
	 * @test
	 */
	function get_noResults() {
		$results = $this->curlGET($this->baseURL . '/ac.php?term=kwefpefwkpofwekopewfkopewfkopwefkopfewkopfwe');
		$json    = json_decode($results, true);

		$this->assertEquals('1', $json['S']);
		$this->assertTrue(count($json['D']) == 0);

	}
}