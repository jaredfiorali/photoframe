<?php

namespace App\Entities;

use \DateTime;
use App\Entities\Base\BaseEntity;

class Photo extends BaseEntity {

	/**
	 * The photo that we retrieved from the DB
	 */
	private $photo;

	/**
	 * The location of the photo, in a format designed for the FE ([location] - [date])
	 */
	private $display_location;

	/**
	 * This creates a photo object
	 * @param array $args
	 */
	public function __construct($args = []) {

		parent::__construct($args);

		// Get our variables from the incoming args
		$this->photo = $args['@photo'] ?? null;
		$photo_location = $args['@photo_location'] ?? null;
		$photo_date = $args['@photo_date'] ?? null;

		// Check to see if we have all of the required data
		if ($this->photo and $photo_location and $photo_date) {

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
				$this->display_location = $photo_location.' - '.$date_taken->format('F Y');
			}
			else {

				// TODO: Throw some sort of error here
			}
		}
		else {

			// TODO: Throw some sort of error here
		}
	}

	/**
	 * Converts this object to a json format for returning to FE
	 * @return string
	 */
	public function to_json() {

		// Build an array out of the data we gathered when constructed
		$json_array = [
			'location' => $this->display_location,
			'photo' => $this->photo,
		];

		// Convert our array to json
		$this->json_data = json_encode($json_array);

		// Let our parent finish the work, as we have saved our json data
		return $this->json_data;
	}
}
