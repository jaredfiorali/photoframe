<?php
include_once '../includes/config/config.php';
include_once '../includes/class/db.php';
include_once '../includes/class/curl.php';
include_once '../includes/class/git.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

//Convert the string of data to an array
if ($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET') {

	// Create new DB object for retrieving our data
	$db = new DB();

	// Retrieve the query string from the URL
	if ($query_string = $_SERVER['QUERY_STRING']) {

		// TODO: Convert this to an object
		if ($query_string == 'toggleLights') {

			// Create new cURL object
			$ch = new Curl();

			// Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
			$hue_result = $ch->execute(array (
				CURLOPT_URL => "http://192.168.20.152/api/IKyFaCiLS7o2fIXwTi1nt12mJfN7FxiJD48J6oRS/groups/0/action",
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_CUSTOMREQUEST => "PUT",
				CURLOPT_POSTFIELDS => '{"on":'.$data['on'].'}'
			));

			// TODO: Check to make sure that the cURL request was a success. Otherwise we are just trusting the FE...
			// Check the state of the lights
			if ($data['on'] == "true") {
				$returned_result = 1;
			} else {
				$returned_result = 0;
			}

			// Return the state of the lights
			echo $returned_result;
		}
		// TODO: Convert this to an object
		else if ($query_string == 'lightsState') {

			// Create new cURL object
			$ch = new Curl();

			// Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
			$hue_result = $ch->execute(array (
				CURLOPT_URL => "http://192.168.20.152/api/IKyFaCiLS7o2fIXwTi1nt12mJfN7FxiJD48J6oRS/lights",
				CURLOPT_RETURNTRANSFER => TRUE
			));

			// Convert the string of data to an array
			$hue_data = json_decode($hue_result, true);

			// Initalize the lights to off
			$light_on = 0;

			// Loop through all lights to see if any of them are on
			for ($i = 1; $i <= 5; $i++) {
				if ($hue_data[$i]['state']['on'] == 1)
				$light_on = 1;
			}

			// Return the lights state
			echo $light_on;
		}
		else if ($query_string == 'getPhoto') {

			// Prepare and execute the MySQL statement
			$db_results = $db->execute("CALL randomPhoto()");

			// Just to keep track, this returns: Base64 Photo, Location, Date Taken
			$image = $db_results[0];
			$location = $db_results[1];
			$date_taken = $db_results[2];

			// Convert the DB date to a readable format
			$photo_date = new DateTime($date_taken);

			// Compile the results from the query for the FE
			$output = array(
				'photo' => $image,
				'location' => $location." - ".$photo_date->format('F Y')
			);

			//Send the output in JSON
			echo json_encode($output);
		}
		else if ($query_string == 'getWeather') {

			// Retreive the cached weather from the server
			$db_results = $db->execute("CALL getWeather()");

			// Send DB cache result to FE
			echo $db_results[0];
		}
		else if ($query_string == 'getNews') {

			// Retreive the cached news from the server
			$db_results = $db->execute("CALL getNews()");

			// Send DB cache result to FE
			echo $db_results[0];
		}
		else if ($query_string == 'getVersion') {

			// Create new DB object for retreiving our data
			$git = new Git();

			// Send the photo frame version to the FE
			echo json_encode($git->get_latest_git_commit());
		}
		else if ($query_string == 'sendToPocket') {
			//Set Pocket static variables
			$access_tokens = Config::pocket_access_tokens;

			foreach ($access_tokens as $access_token) {
				//Build cURL JSON submission
				$curl_data = '{"url":"'.$data['pocketAddress'].'","consumer_key":"'.Config::pocket_consumer_key.'","access_token":"'.$access_token.'"}';

				// Create new cURL object
				$ch = new Curl();

				// Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
				$pocket_result = $ch->execute(array (
					CURLOPT_URL => "https://getpocket.com/v3/add",
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $curl_data,
					CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: ' . strlen($curl_data))
				));
			}

			//Return Pocket response
			echo json_encode($pocket_result);
		}
	}
  else if ($check = getimagesize($_FILES["image"]["tmp_name"])) {
		$image = file_get_contents($_FILES['image']['tmp_name']);
		$image_base64 = base64_encode($image);

		$location = $_POST['location'];
		$date_taken = $_POST['date_taken'];

		// Prepare and execute the MySQL statement
		$db_results = $db->execute("INSERT into photos (photo, location, date_taken) VALUES ('$image_base64', '$location', '$date_taken')");

		//Send the output in JSON
		echo "UPLOAD SUCCESS";
	}
	else {
		echo "Sorry, that endpoint does not exist, or an error has occurred";
	}
}
