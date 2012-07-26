<?php

class Validators {
	public $containsContent;
	public $isValidEmail;
	public function __construct() {
		$this->containsContent = function ($input) {
			return strlen($input) > 0;
		};

		$this->isValidEmail = function ($input) {
			return preg_match('/^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/', $input);
		};
	}
}