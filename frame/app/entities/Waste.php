<?php

namespace App\Entities;

use App\Entities\Base\BaseEntity;

class Waste extends BaseEntity {

	/**
	 * This creates a weather object
	 * @param array $args
	 */
	public function __construct($args = []) {

		parent::__construct($args);

		$waste_date = $args['date'] ?? null;

		// Get our variables from the incoming args
		$this->data = [
			'date' => $date = date("Y-m-d", strtotime($waste_date)),
			'type' => $args['type'] ?? null,
		];
	}
}
