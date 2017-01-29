<?php
namespace Gratheon\Entity;

class SuccessResponse extends Response{
	public function __construct($data = []) {
		parent::__construct($data, true);
	}
}