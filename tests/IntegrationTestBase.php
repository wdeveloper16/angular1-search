<?php

class IntegrationTestBase extends \PHPUnit_Framework_TestCase {
	protected $phpErrorDetection = 'error';
	protected $baseURL = 'http://104.236.151.16/';

	protected $cookieFile = '';
	protected $debug = 0;


	private $argumentTokens = [
		'null',
		'',
		'longstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstringlongstring',
		0,
		123,
		12.30,
		'0b10100111001',
		'+0123.45e50',
		'true',
		'false',
		'[]',//json array
		'{}',//json array
	];

	/**
	 * @var bool|array if true, will execute with all users
	 */
	private $runUsers = false;
	private $checkPerformance = true;
	private $checkForResponseErrors = true;
	private $checkInjections;
	private $expectingHtmlOutput = false;
	private $checkArguments;
	private $curlURL = '';
	private $curlMethod = 'GET';
	private $curlParams = [];


	public function tearDown() {
		$this->checkForResponseErrors = true;
		$this->runUsers               = false;
		$this->checkPerformance       = false;
		$this->curlParams             = [];
		$this->curlURL                = '';
		$this->curlMethod             = 'GET';
	}


	protected function setUp() {
		$this->cookieFile =  'integration_tests_cookie.txt';
		touch($this->cookieFile);
	}

	protected function assertNoPHPErrors($result) {
		$this->assertNotContains('Call Stack', $result);
		$this->assertNotContains('Notice', $result);
		$this->assertNotContains('notice', $result);
		$this->assertNotContains('SQLSTATE', $result);
		$this->assertNotContains('<pre>', $result);
		$this->assertNotContains('xdebug-var-dump', $result);
		$this->assertNotContains('xdebug-error', $result);
		$this->assertNotContains('error', $result);
	}


	protected function resetCookie() {
		if (file_exists($this->cookieFile)) {
			unlink($this->cookieFile);
			touch($this->cookieFile);
		}
		else {
			touch($this->cookieFile);
		}
	}

	/**
	 * @param      $url
	 * @param      $data
	 * @param bool $useCookie
	 *
	 * @return mixed
	 */
	protected function curlPost($url, $data, $useCookie = true) {
		if ($this->debug) {
			echo "\n" . 'POST';
			echo "\n" . $url . "\n";
			print_r($data);
		}
		$ch      = curl_init();
		$verbose = fopen('php://temp', 'rw+');
		curl_setopt($ch, CURLOPT_STDERR, $verbose);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);

		if ($useCookie) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		}

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$file      = file_get_contents($this->cookieFile);
		$xsrfToken = substr($file, strpos($file, 'XSRF-TOKEN') + 11, 32);

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
//			'XSRF-TOKEN: ' . $xsrfToken,
'X-XSRF-TOKEN:' . $xsrfToken
		]);

		$result = curl_exec($ch);

		rewind($verbose);
		$verboseLog = stream_get_contents($verbose);
		if($this->debug){
			echo $verboseLog;

			var_dump($result);
		}

		curl_close($ch);

		return $result;
	}

	protected function curlPostRawJson($url, $data) {
		if ($this->debug) {
			echo "\n" . 'POST';
			echo "\n" . $url . "\n";
			print_r($data);
		}

		$ch      = curl_init();
		$verbose = fopen('php://temp', 'rw+');
		curl_setopt($ch, CURLOPT_STDERR, $verbose);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$file      = file_get_contents($this->cookieFile);
		$xsrfToken = substr($file, strpos($file, 'XSRF-TOKEN') + 11, 32);
//		echo $url . ' token:' . $xsrfToken;
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json;charset=UTF-8',
			//			'XSRF-TOKEN: ' . $xsrfToken,
			'X-XSRF-TOKEN: ' . $xsrfToken
		]);

		$result = curl_exec($ch);

		if($this->debug){
			var_dump($result);
		}

		rewind($verbose);
//		$verboseLog = stream_get_contents($verbose);
//		echo $verboseLog;

		curl_close($ch);

		return $result;
	}

	/**
	 * @param string $url
	 * @param string $filePath
	 * @param string $postFilename
	 * @param array  $post
	 *
	 * @return mixed|string
	 */
	protected function curlFileUpload($url, $filePath, $postFilename, $post = [], $useCookie = true) {
		if ($this->debug) {
			echo "\n" . 'POST';
			echo "\n" . $url . "\n";
			print_r($filePath);
			print_r($postFilename);
			print_r($post);
		}

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type  = finfo_file($finfo, $filePath);
		finfo_close($finfo);

		$aPath               = explode('/', $filePath);
		$ch                  = curl_init();
		$post[$postFilename] = new CurlFile($filePath, $type, end($aPath));


//		$verbose = fopen('php://temp', 'rw+');
//		curl_setopt($ch, CURLOPT_STDERR, $verbose);

		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
//		curl_setopt($ch, CURLOPT_UPLOAD, true);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($useCookie) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		}


		$file      = file_get_contents($this->cookieFile);
		$xsrfToken = substr($file, strpos($file, 'XSRF-TOKEN') + 11, 32);

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-type: multipart/form-data",
			//			'XSRF-TOKEN: ' . $xsrfToken,
			'X-XSRF-TOKEN:' . $xsrfToken
		]);

		$result = curl_exec($ch);


//		rewind($verbose);
//		$verboseLog = stream_get_contents($verbose);


		if (!$result) {
			$result = 'Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch);
		}

		curl_close($ch);

		return $result;
	}

	/**
	 * @param      $url
	 * @param bool $useCookie
	 *
	 * @return mixed
	 */
	protected function curlGET($url, $useCookie = true) {
		if ($this->debug) {
			echo "\n" . 'GET';
			echo "\n" . $url . "\n";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if ($useCookie) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('json'=>'{}')));
		$res = curl_exec($ch);

		if($this->debug){
			var_dump($res);
		}
		curl_close($ch);

		return $res;
	}
}