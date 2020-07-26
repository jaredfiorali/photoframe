<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Weather;
use App\Entities\Curl;
use App\Services\ConfigService;

class WeatherController extends BaseController {

	/** @inheritdoc	 */
	public function getAction($param = null) {

		// Prepare and execute the MySQL statement
		$db_results = $this->db->fetchOne("CALL getWeather()");

		// Create a new weather object from our DB data
		$weather = new Weather($db_results);

		// Return our data
		return $weather->data
	}

	/** @inheritdoc	 */
	public function updateAction() {

		// Create our cURL object for communicating with our third party service
		$ch = new Curl();

		// Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
		$result = $ch->execute(array (
			CURLOPT_URL => "https://api.darksky.net/forecast/". ConfigService::get_value('darksky_api_key')."/". ConfigService::get_value('weather_latitude').",". ConfigService::get_value('weather_longitude')."?units=ca&exclude=minutely,flags",
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADEROPT => "Accept-Encoding: gzip"
		));

		// Confirm that the cURL was successful
		if ($result) {

			$data = addslashes($result);

			// Prepare and execute the MySQL statement
			$this->db->execute("CALL setWeather('$data')");

			// Let the FE know that it worked
			echo "Success";
		}
		else {

			// Let the FE know that it failed...
			echo "Failed";
		}
	}
}
