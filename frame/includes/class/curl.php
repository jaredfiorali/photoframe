<?php

class Curl {

	private $ch;

	/**
	* Constructor for cURL object. Doesn't do anything (for now)
	*/
	public function __construct() {
		// Silence is golden
	}

	/**
	* Opens our cURL connection
	*/
	private function create_curl_connection() {
		return $this->ch = curl_init();
	}

	/**
	* Closes out cURL connection
	*/
	private function close_curl_connection() {
		curl_close($this->ch);
	}

	/**
	* @param string[] $options Set of cURL options
	* @return string
	*/
	public function execute(array $options) {

		// Set this to nothing
		$curl_result = null;

		// Check to make sure that the incoming options were set
		if ($options) {

			// Open the cURL connection
			$this->create_curl_connection();

			// Apply the incoming options to the cURL object
			curl_setopt_array($this->ch, $options);

			// Let's do what we intended and execute this cURL request
			$curl_result = curl_exec($this->ch);

			// Be smart, and close this connection
			$this->close_curl_connection();
		}

		// Return result!
		return $curl_result;
	}

}
