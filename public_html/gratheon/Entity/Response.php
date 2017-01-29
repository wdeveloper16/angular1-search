<?php
namespace Gratheon\Entity;

class Response {
	public function __construct($data = [], $success = true) {
		$this->data    = $data;
		$this->success = $success;
	}

	public function __toString() {
		return json_encode([
			'success' => $this->success,
			'data'    => $this->data
		], JSON_UNESCAPED_UNICODE);
	}
}