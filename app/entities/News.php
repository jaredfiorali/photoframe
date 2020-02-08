<?php

namespace App\Entities;

use App\Entities\Base\BaseEntity;

class News extends BaseEntity {

	/**
	 * This creates a news object
	 * @param array $args
	 */
	public function __construct($args = []) {

		parent::__construct($args);

		// Get our variables from the incoming args
		$this->data = $args['news'] ?? null;
	}

	/**
	 * Converts this object to a json format for returning to FE
	 * @return string
	 */
	public function to_json() {

		// FYI, this is stored in JSON format in our DB, so no need to convert
		return $this->data;
	}
}
