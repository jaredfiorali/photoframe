<?php

namespace App\Entities;

use App\Entities\Base\BaseEntity;

class Git extends BaseEntity {

	/**
	 * Constructor for git object
	 * @param array $args
	 */
	public function __construct($args = []) {

		parent::__construct($args);

		// Start with an empty commit ID
		$commit = [];

		// Check our directory git files for the commit ID
		if ($commit[0] = rtrim(file_get_contents('/var/www/html/git_revision'))) {

			// Compile our array
			$this->data = [
				'version' => $commit[0]
			];
		}
		else {

			// TODO: Throw some sort of error here
		}
	}
}
