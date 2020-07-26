<?php

namespace App\Entities\Base;

class BaseEntity {

	/**
	 * The data we received from the DB
	 * @var array
	 */
	public $data;

	/**
	 * The data we intend to send to the FE, in json
	 * @var string
	 */
	protected $json_data;

	/**
	 * Default constructor for our object
	 * @param array $args
	 */
	public function __construct($args = []) {

		// Let the children play
	}

	/**
	 * Converts this object to a json format for returning to FE
	 * @return string
	 */
	public function to_json() {

		return stripslashes(json_encode($this->data));
	}
}
