<?php
include_once '../includes/db_connect.php';
include_once '../includes/class/weather.php';
include_once '../includes/class/curl.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

//Convert the string of data to an array
if ($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET')
    parse_str(file_get_contents("php://input"), $data);

if (isset($data['command'])) {
    if (substr($data['command'],  0, 4 ) == "/add") {
        $item = filter_var($data['text'], FILTER_SANITIZE_STRING);
        $addType = filter_var(substr($data['command'],  4, strlen($data['command'])), FILTER_SANITIZE_STRING);

        // Add a new item to the database
        if ($insert_stmt = $mysqli->prepare("INSERT INTO " . $addType . " (item, dateAdded) VALUES(?, UNIX_TIMESTAMP())")) {
            $insert_stmt->bind_param('s', $data['text']);

            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                echo '{"response_type": "in_channel", "text": "There was an error adding \'**' . $data['text'] . '**\' to ' . $addType . '. Error: ' . $mysqli->error . '"}';
            } else {
                echo '{"response_type": "in_channel", "text": "\'**' . $data['text'] . '**\' was successfully added to ' . ucfirst($addType) . '."}';
            }
        } else {
            echo '{"response_type": "in_channel", "text": "There was an error adding \'**' . $data['text'] . '**\' to  ' . $addType . '. Error: ' . $mysqli->error . '"}';
        }
    }
    else if (substr($data['command'],  0, 9 ) == "/complete") {
        $addType = filter_var(substr($data['command'],  9, strlen($data['command'])), FILTER_SANITIZE_STRING);

        // Mark all items as completed
        if ($insert_stmt = $mysqli->prepare("UPDATE " . $addType . " SET dateCompleted = UNIX_TIMESTAMP() WHERE dateCompleted IS NULL")) {

            // Execute the prepared query
            if (!$insert_stmt->execute()) {
                echo '{"response_type": "in_channel", "text": "There was an error clearing the ' . $addType . '. Error: ' . $mysqli->error . '"}';
            } else {
                echo '{"response_type": "in_channel", "text": "All ' . $addType . ' marked as completed!"}';
            }
        } else {
            echo '{"response_type": "in_channel", "text": "There was an error clearing the ' . $addType . '. Error: ' . $mysqli->error . '"}';
        }
    }
    else if (substr($data['command'],  0, 4 ) == "/get") {
        $addType = filter_var(substr($data['command'],  4, strlen($data['command'])), FILTER_SANITIZE_STRING);

        // Find all items that are not completed (have a completed date)
        $prep_stmt = "SELECT item, dateAdded FROM " . $addType . " WHERE dateCompleted IS NULL ORDER BY dateAdded";
        $stmt = $mysqli->prepare($prep_stmt);

        // Execute the prepared query
        if ($stmt) {
            $stmt->execute();
            $stmt->store_result();

            // Get variables from result
            $stmt->bind_result($item, $dateAdded);

            // Make sure we have more than 1 returned result
            if ($stmt->num_rows > 0) {
                $response = '|Item|Date Added|\n|:------------|:------------|\n';

                // Retreive the items from the query
                while ($stmt->fetch()) {
                    $response = $response . '|' . $item . '|' . date('F jS', $dateAdded) . '|\n';
                }

                echo '{"response_type": "in_channel", "text": "' . $response . '"}';
            } else if ($stmt->num_rows == 0) {
                echo '{"response_type": "in_channel", "text": "Looks like there are no items!"}';
            } else {
                echo '{"response_type": "in_channel", "text": "There was an error getting the list of ' . $addType . ' from the server.\nError: ' . $mysqli->error . '\nResponse: ' . $response . '"}';
            }
        } else {
            echo '{"response_type": "in_channel", "text": "There was an error getting the list of ' . $addType . ' from the server.\nError: ' . $mysqli->error . '"}';
        }
        $stmt->close();

    }
    else if (substr($data['command'],  0, 5 ) == "clear") {
		// Clean the input from FE
		$addType = filter_var(substr($data['command'],  5, strlen($data['command'])), FILTER_SANITIZE_STRING);

		// Mark a specific item as completed
		if ($update_stmt = $mysqli->prepare("UPDATE ".$addType." SET dateCompleted = UNIX_TIMESTAMP() WHERE id = ?")) {
			$update_stmt->bind_param('i', $data['id']);

			// Execute the prepared query
			if (!$update_stmt->execute()) {
				// If there's nothing to execute, looks like we had a problem
				echo "Error: ".$mysqli->error;
			} else {
				// Success! Return this data
				print_r($data);
			}
		} else {
			// If there's nothing to execute, looks like we had a problem
			echo "Error: ".$mysqli->error;
		}
    }
	// TODO: Convert this to an object
    else if ($data['command'] == "toggleLights") {
        $curlData = '{"on":'.$data['on'].'}';

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "http://192.168.20.152/api/IKyFaCiLS7o2fIXwTi1nt12mJfN7FxiJD48J6oRS/groups/0/action");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);

        // Convert the string of data to an array
        $hueResult = curl_exec($ch);

        // Close cURL connection
        curl_close($ch);

        $returnedResult = 0;

        if ($data['on'] == "true")
            $returnedResult = 1;

        echo $returnedResult;
    }
	// TODO: Convert this to an object
    else if ($data['command'] == 'lightsState') {
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "http://192.168.20.152/api/IKyFaCiLS7o2fIXwTi1nt12mJfN7FxiJD48J6oRS/lights");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $hueResult = curl_exec($ch);

        // Convert the string of data to an array
        $hueData = json_decode($hueResult, true);

        // Close cURL connection
        curl_close($ch);

        $lightOn = 0;

        for ($i = 1; $i <= 5; $i++) {
            if ($hueData[$i]['state']['on'] == 1)
                $lightOn = 1;
        }

        echo $lightOn;
    }
    else if (substr($data['command'], 0, 8 ) == "retrieve") {
        $addType = strtolower(filter_var(substr($data['command'], 8, strlen($data['command'])), FILTER_SANITIZE_STRING));

        $prep_stmt = "SELECT id, item FROM ".$addType." WHERE dateCompleted IS NULL ORDER BY dateAdded";
        $stmt = $mysqli->prepare($prep_stmt);

        if ($stmt) {
            $stmt->execute();
            $stmt->store_result();

            // get variables from result.
            $stmt->bind_result($id, $item);

            $response = "[";

            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    $response = $response . '{"id":'.$id.',"item":"'.$item.'"},';
                }

                echo substr($response, 0, strlen($response)-1) . ']';

            } else if ($stmt->num_rows == 0) {
                echo '{"response_type": "in_channel", "text": ""}';
            } else {
                echo '{"response_type": "in_channel", "text": "There was an error getting the list of ' . $addType . ' from the server.\nError: ' . $mysqli->error . '\nResponse: ' . $response . '"}';
            }
        } else {
            echo '{"response_type": "in_channel", "text": "There was an error getting the list of ' . $addType . ' from the server.\nError: ' . $mysqli->error . '"}';
        }
        $stmt->close();
    }
    else if ($data['command'] == 'calendar') {
        // Get the API client and construct the service object.
        //echo getCalendarEntries();
        echo "not working yet";
    }
    else if ($data['command'] == 'getPhoto') {

        // Prepare the MySQL statement
        $prep_stmt = "CALL getPhoto()";
        $stmt = $mysqli->prepare($prep_stmt);

        // Check if something was returned
        if ($stmt) {
            // Execute the DB call
            $stmt->execute();
            $stmt->store_result();

            // Get variables from result.
            $stmt->bind_result($path, $location, $date_taken);
            $stmt->fetch();

            // Convert the DB date to a readable format
            $photoDate = new DateTime($date_taken);

            // We should have EXACTLY 1 value returned
            if ($stmt->num_rows == 1) {
                $output = array(
                    $location." - ".$photoDate->format('F Y'),
                    "/images/photos/".$path
                );

                //Send the output in JSON
                echo json_encode($output);
            } else {
                // Looks like we got more that 1 value
                echo 'Failed to get photo - '.$mysqli->error;
            }
        } else {
            // Look like the MySQL statement failed to return something
            echo 'Failed to get photo - '.$mysqli->error;
        }

        // Delete the MySQL object
        $stmt->close();
    }
    else if ($data['command'] == 'getWeather') {

		// Create new cURL object
		$ch = new Curl();

        // Execute the cURL request by setting the cURL options: url, # of POST vars, POST data
		$weather_result = $ch->execute(array (
			CURLOPT_URL => "https://api.darksky.net/forecast/ced23c715fea437145b3182bf1065f0c/43.588145,-79.648063?units=ca&exclude=minutely,flags",
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
        $xmlNews = new SimpleXmlElement($data['newsAddress'], NULL, TRUE);

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
        $access_token_jared = '"f1ee6595-6a11-198c-356c-0acf23"';
        $access_token_jacqueline = '"6bdcd289-7c70-43bb-05c7-47252b"';
        $consumer_key = '"69195-1cc1c210fb7e3db337ca2b78"';
        $access_tokens = array($access_token_jared, $access_token_jacqueline);

        foreach ($access_tokens as $access_token) {
            //Build cURL JSON submission
            $curl_data = '{"url":"'.$data['pocketAddress'].'","consumer_key":'.$consumer_key.',"access_token":'.$access_token.'}';

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
else {
    echo "Sorry, that command does not exist, or an error has occurred. Here's the command I recieved: " . $data['command'];
}
?>
