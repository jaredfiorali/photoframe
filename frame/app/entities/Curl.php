<?php

namespace App\Entities;

use App\Entities\Base\BaseEntity;

class Curl extends BaseEntity {

	/**
	 * The cURL object we will be using to submit our request
	 * @var curl
	 */
	private $ch;

	/**
	 * Constructor for cURL object. Doesn't do anything (for now)
	 * @param array $args
	 */
	public function __construct($args = []) {

		parent::__construct($args);

		// Check to see if we already have a cURL connection
		if (!$this->ch) {

			// Create the cURL connection
			$this->ch = curl_init();
		}
	}

	/**
	 * Uses the cURL connection initiated by the constructor, and executes a cURL command
	 * @param string[] $options Set of cURL options
	 * @return string
	 */
	public function execute(array $options) {

		// Set this to nothing
		$curl_result = false;

		// Check to make sure that the incoming options were set
		if ($options) {

			// Apply the incoming options to the cURL object
			curl_setopt_array($this->ch, $options);

			// Let's do what we intended and execute this cURL request
			$curl_result = curl_exec($this->ch);

			// Check to see if there was an error
			if (!$curl_result) {

				// Dump it
				var_dump([
					'curl_error' => curl_error($this->ch),
				]);
			}
		}
		else {
			// TODO: Throw some sort of error here
		}

		// Return result!
		return $curl_result;
	}
}
