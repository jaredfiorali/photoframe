<?php

namespace App\Entities;

use App\Entities\Base\BaseEntity;

class Status extends BaseEntity {

	/**
	 * A map of status types and their associated codes
	 */
	private const STATUS_TYPES = [
		'success' => [
			'code' => 200,
			'code_message' => 'OK',
			'message' => '{"status": "All services are operational"}'
		],
		'error' => [
			'code' => 500,
			'code_message' => 'Internal Server Error',
			'message' => '{"status": "Some services are unavailable"}'
		],
	];

	/**
	 * The HTTP status code
	 * @var integer $code
	 */
	public $code = '';

	/**
	 * The friendly message for our HTTP status code
	 * @var string $code_message
	 */
	public $code_message = '';

	/**
	 * The response we intend to send to the FE
	 * @var string $data
	 */
	public $data = '';

	/**
	 * Constructor for cURL object. Doesn't do anything (for now)
	 * @param boolean $success When true, we instantiate this object with success messages
	 */
	public function __construct($args = []) {

		// Always call your parents
		parent::__construct($args);

		// Grab the status from our args
		$success = $args['success'] ?? true;

		// Set the appropriate variables based off of our incoming status
		if ($success) {

			$this->code = Status::STATUS_TYPES['success']['code'];
			$this->code_message = Status::STATUS_TYPES['success']['code_message'];
			$this->message = Status::STATUS_TYPES['success']['message'];
		}
		else {

			$this->code = Status::STATUS_TYPES['error']['code'];
			$this->code_message = Status::STATUS_TYPES['error']['code_message'];
			$this->message = Status::STATUS_TYPES['error']['message'];
		}
	}
}
