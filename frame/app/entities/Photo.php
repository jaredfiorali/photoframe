<?php

namespace App\Entities;

use \DateTime;
use App\Entities\Base\BaseEntity;

class Photo extends BaseEntity {

	/**
	 * This creates a photo object
	 * @param array $args
	 */
	public function __construct($args = []) {

		parent::__construct($args);

		// Get our variables from the incoming args
		$photo = $args['@photo'] ?? null;
		$photo_location = $args['@photo_location'] ?? null;
		$photo_date = $args['@photo_date'] ?? null;

		// Check to see if we have all of the required data
		if ($photo and $photo_location and $photo_date) {

			// Start without a date
			$date_taken = null;

			try {

				// Convert the DB date to a readable format
				$date_taken = new DateTime($photo_date);
			}
			catch (\Exception $e) {

				// TODO: Throw some sort of error here
			}

			// Confirm we have a date
			if ($date_taken) {

				// Compile a display location for the FE
				$display_location = $photo_location.' - '.$date_taken->format('F Y');

				// Confirm we were able to compile a complete location
				if ($display_location) {

					// Build an array out of the data we gathered when constructed
					$this->data = [
						'location' => $display_location,
						'photo' => $photo,
					];
				}
			}
			else {

				// TODO: Throw some sort of error here
			}
		}
		else {

			// TODO: Throw some sort of error here
		}
	}
}
