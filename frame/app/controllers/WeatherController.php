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
		return $weather->data;
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

	public function sseAction() {
		// SSE needs to be set to 'text/event-stream'
		$this->response->setHeader('Content-Type', 'text/event-stream');
		$this->response->setHeader('Cache-Control', 'no-cache');
		$this->response->sendHeaders();

		// We know nothing
		$weather = '';

		// Loop...forever
		while (true) {

			// Stops PHP from checking for user disconnect
			ignore_user_abort(true);

			// Check if the client has disconnected from this session
			if (connection_aborted()) {

				print "---Client SSE session is closed---\n\n";

				exit();
			}

			// Start with nothing
			$json_results = [];

			// Get the weather and photo from the database
			$weather_result = $this->db->fetchOne("CALL getWeather()")['weather'] ?? null;
			$photo_result = $this->db->fetchOne("CALL randomPhoto()");

			// Confirm that something has changed from what we knew before
			if ($weather != $weather_result) {

				// Update the saved weather result with the new one
				$weather = $weather_result;

				// Echo it so the client will pick up the latest data
				$results['weather'] = $weather;
			}

			// Prepare and execute the MySQL statement
			$db_results = $this->db->fetchOne("CALL randomPhoto()");

			// Create a new photo object from our DB data
			$photo = new Photo($db_results);

			// Add photos to the result
			$results['photo'] = $photo->data;

			echo 'data: ' . json_encode($results), "\n\n";

			// Flush the output buffer
			@ob_end_flush();
			@flush();

			// Wait 10 seconds, and then do it all again
			sleep(10);
		}
	}
}
