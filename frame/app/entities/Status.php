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
			'message' => 'OK',
			'data' => '{"status": "All services are operational"}'
		],
		'error' => [
			'code' => 500,
			'message' => 'Internal Server Error',
			'data' => '{"status": "Some services are unavailable"}'
		],
	];

	/**
	 * The HTTP status code
	 * @var integer $code
	 */
	public $code = '';

	/**
	 * The friendly message for our HTTP status code
	 * @var string $message
	 */
	public $message = '';

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
			$this->message = Status::STATUS_TYPES['success']['message'];
			$this->data = Status::STATUS_TYPES['success']['data'];
		}
		else {

			$this->code = Status::STATUS_TYPES['error']['code'];
			$this->message = Status::STATUS_TYPES['error']['message'];
			$this->data = Status::STATUS_TYPES['error']['data'];
		}
	}
}
