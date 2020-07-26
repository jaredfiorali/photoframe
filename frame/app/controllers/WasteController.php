<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Waste;

class WasteController extends BaseController {

	const RECYCLING = 'Recycling';
	const GARBAGE = 'Garbage';

	/** @inheritdoc	 */
	public function getAction($param = null) {

		// Get today's date in the correct format
		$date = date("Y-m-d");

		// Prepare and execute the MySQL statement
		$db_results = $this->db->fetchOne("CALL getWastePickup('$date')");

		// Create a new waste object from our DB data
		$waste = new Waste($db_results);

		// Return our data
		return $waste->data
	}

	/** @inheritdoc	 */
	public function updateAction() {

		// Get today's date in the correct format
		$date = date("Y-m-d");

		// Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
		$html = file_get_contents("https://www.peelregion.ca/waste-scripts/when-does-it-go/nextCollectionHTML.asp?service=bm-bg-thu-b&days=10&date=$date");

		// Confirm that the cURL was successful
		if ($html) {

			// Generate a new DOMDocument out of our incoming HTML
			$dom = new \DOMDocument();
			$dom->loadHTML($html);

			// Get the collection of DIV's in this HTML
			$myDivs =  $dom->getElementsByTagName('div');

			// Loop through each DIV we found in this HTML
			foreach($myDivs as $key => $value) {

				// Trust must be earned
				$is_collection_day = false;

				/** @var \DOMNamedNodeMap $attributes */
				// Get the attributes of this element
				$attributes = $value->attributes;

				// Confirm that we have at least one attribute to check
				if ($count = $attributes->length) {

					// Loop through the list of attributes
					for ($i = 0; $i < $count; $i++) {

						/** @var \DOMAttr $att */
						// Extract the attribute, and confirm it's what we are looking for
						if ($att = $attributes->item($i) and $att->value == 'collectionDay') {

							// Success! Mark this as found
							$is_collection_day = true;

							// Parachute
							break;
						}
					}
				}

				// Confirm that we determined that this is a collection day DIV
				if ($is_collection_day) {

					// Get the date that's contained in the "Latest Date" value
					preg_match('/latestDate\s[=]\s["](.*)["]/', $value->nodeValue, $output_date);

					// Take the matching group that we found
					$waste_date = $output_date[1] ?? '';

					// Check to see if we found recycling in this collection day
					if (strpos($value->nodeValue, WasteController::RECYCLING)) {

						$waste_type = WasteController::RECYCLING;
					}
					// Check to see if we found garbage in this collection day
					else if (strpos($value->nodeValue, WasteController::GARBAGE)) {

						$waste_type = WasteController::GARBAGE;
					}
					// Set it as blank
					else {

						$waste_type = '';
					}

					// Confirm that we have something to send to the database
					if ($waste_date and $waste_type) {

						// Prepare and execute the MySQL statement
						$result = $this->db->execute("CALL setWastePickup('$waste_date', '$waste_type')");
					}
				}
			}

			// Let the FE know that it worked
			echo "Success";
		}
		else {

			// Let the FE know that it failed...
			echo "Failed";
		}
	}
}
