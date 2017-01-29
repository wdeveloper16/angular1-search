<?php
namespace Gratheon\Entity;

class ErrorResponse extends Response {
	//General messages
	const ENTITY_NOT_FOUND = 1;
	const ENTITY_NOT_UNIQUE = 2;


	public function __construct($message, $code = false) {
		parent::__construct([
			'msg'  => $message,
			'code' => $code
		], true);
	}
}