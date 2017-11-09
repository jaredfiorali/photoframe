<?php
include_once '../includes/config/config.php';
include_once '../includes/class/db.php';
include_once '../includes/class/weather.php';
include_once '../includes/class/curl.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

//Convert the string of data to an array
if ($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET')
    parse_str(file_get_contents("php://input"), $data);

if ($data['command']) {
	// TODO: Convert this to an object
    if ($data['command'] == "toggleLights") {

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
    else if ($data['command'] == 'lightsState') {

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
    else if ($data['command'] == 'calendar') {
        // Get the API client and construct the service object.
        //echo getCalendarEntries();
        echo "not working yet";
    }
    else if ($data['command'] == 'getPhoto') {

        // Prepare and execute the MySQL statement
		$db = new DB();
		$db_results = $db->execute("CALL randomPhoto()");

		// Just to keep track, this returns: Photo Path, Location, Date Taken
		$image = $db_results[0];
		$location = $db_results[1];
		$date_taken = $db_results[2];

        // Convert the DB date to a readable format
        $photo_date = new DateTime($date_taken);

		// Compile the results from the query for the FE
		$output = array(
			$image,
			$location." - ".$photo_date->format('F Y')
		);

		//Send the output in JSON
		echo json_encode($output);
    }
    else if ($data['command'] == 'getWeather') {

		// Create new cURL object
		$ch = new Curl();

        // Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
		$weather_result = $ch->execute(array (
			CURLOPT_URL => "https://api.darksky.net/forecast/".Config::darksky_api_key."/".Config::weather_latitude.",".Config::weather_longitude."?units=ca&exclude=minutely,flags",
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADEROPT => "Accept-Encoding: gzip"
		));

        //Convert the string of data to an array
        $weather_data = json_decode($weather_result, true);

        //Set today's weather
        $today_weather = new Weather($weather_data["daily"]["data"][0]);

        //Set tomorrow's weather
        $tomorrow_weather = new Weather($weather_data["daily"]["data"][1]);

        //Set current weather
        $currently = $weather_data["currently"];
        $icon = Weather::setIcon($currently["icon"], 1);
        $apparent_temperature = round($currently["apparentTemperature"]);

        if ($today_weather->precip_probability >= 35) {
            $clothing = "you should bring an umbrella";
        } else if ($apparent_temperature > 25){
            $clothing = "you won't need a coat";
        } else if ($apparent_temperature <= 25 and $apparent_temperature > 10) {
            $clothing = "you'll need a light coat";
        } else if ($apparent_temperature <= 10) {
            $clothing = "you'll need a winter coat";
        }

        $weather_array = array(
            "weatherIcon" => $icon,
            "apparentTemperature" => $apparent_temperature,
            "todaySunrise" => $today_weather->sunrise,
            "todaySunset" => $today_weather->sunset,
            "todayTime" => $today_weather->clock,
            "todaySummary" => $today_weather->summary,
            "todayIcon" => $today_weather->icon,
            "todayPrecipProbability" => $today_weather->precip_probability,
            "todayTemperatureMin" => $today_weather->temperature_min,
            "todayTemperatureMax" => $today_weather->temperature_max,
            "todayApparentTemperatureMin" => $today_weather->apparent_temperature_min,
            "todayApparentTemperatureMax" => $today_weather->apparent_temperature_max,
            "todayWindSpeed" => $today_weather->wind_speed,
            "tomorrowTime" => $tomorrow_weather->clock,
            "tomorrowSummary" => $tomorrow_weather->summary,
            "tomorrowIcon" => $tomorrow_weather->icon,
            "tomorrowPrecipProbability" => $tomorrow_weather->precip_probability,
            "tomorrowTemperatureMin" => $tomorrow_weather->temperature_min,
            "tomorrowTemperatureMax" => $tomorrow_weather->temperature_max,
            "tomorrowApparentTemperatureMin" => $tomorrow_weather->apparent_temperature_min,
            "tomorrowApparentTemperatureMax" => $tomorrow_weather->apparent_temperature_max,
            "tomorrowWindSpeed" => $tomorrow_weather->wind_speed,
            "clothing" => $clothing,
            "bigDate" => date('l, F jS', time())
        );

        echo json_encode($weather_array);
    }
    else if ($data['command'] == 'getNews') {
        $xmlNews = new SimpleXmlElement(Config::news_url, NULL, TRUE);

        $articleTitle = array();
        $articleURL = array();

        foreach($xmlNews->channel->item as $newsItem){
            //Get Article Title
            array_push($articleTitle, $newsItem->title);
            //Get Article URL
            array_push($articleURL, $newsItem->link);
        }

        $newsOutput = array($articleTitle, $articleURL);

        echo json_encode($newsOutput, JSON_FORCE_OBJECT);
    }
    else if ($data['command'] == 'sendToPocket') {
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
    else {
        echo "Sorry, that command does not exist, or an error has occurred. Here's the command I recieved: " . $data['command'];
    }
}
else if ($check = getimagesize($_FILES["image"]["tmp_name"])) {
	 $image = file_get_contents($_FILES['image']['tmp_name']);
	 $image_base64 = base64_encode($image);

	 $location = $_POST['location'];
	 $date_taken = $_POST['date_taken'];

	 // Prepare and execute the MySQL statement
	 $db = new DB();
	 $db_results = $db->execute("INSERT into photos (photo, location, date_taken) VALUES ('$image_base64', '$location', '$date_taken')");

	 //Send the output in JSON
	 echo "SUCCESS";
}
else {
    echo "Sorry, that endpoint does not exist, or an error has occurred";
}
?>
