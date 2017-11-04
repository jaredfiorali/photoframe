<?php
include_once 'db_connect.php';
session_start();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Weather {
    public $clock;
    public $summary;
    public $icon;
    public $precipProbability;
    public $temperatureMin;
    public $temperatureMax;
    public $apparentTemperatureMin;
    public $apparentTemperatureMax;
    public $windSpeed;
    public $sunrise;
    public $sunset;

    public function __construct(array $weatherData) {
        $this->clock = $weatherData["time"];
        $this->summary = strtolower($weatherData["summary"]);
        $this->icon = setIcon($weatherData["icon"], 1);
        $this->precipProbability = ($weatherData["precipProbability"])*100;
        $this->temperatureMin = round($weatherData["temperatureMin"]);
        $this->temperatureMax = round($weatherData["temperatureMax"]);
        $this->apparentTemperatureMin = round($weatherData["apparentTemperatureMin"]);
        $this->apparentTemperatureMax = round($weatherData["apparentTemperatureMax"]);
        $this->windSpeed = round($weatherData["windSpeed"]);
        $this->sunrise = date('g:iA', $weatherData["sunriseTime"]);
        $this->sunset = date('g:iA', $weatherData["sunsetTime"]);
    }
}

function setIcon($iconText, $night) {
    switch($iconText) {
        case 'clear-day':
            $iconReturned = 'wi-day-sunny';
            break;
        case 'clear-night':
            $iconReturned = 'wi-night-clear';
            break;
        case 'rain':
            $iconReturned = 'wi-day-rain';
            break;
        case 'snow':
            $iconReturned = 'wi-day-snow';
            break;
        case 'sleet':
            $iconReturned = 'wi-day-sleet';
            break;
        case 'wind':
            $iconReturned = 'wi-day-windy';
            break;
        case 'fog':
            $iconReturned = 'wi-day-fog';
            break;
        case 'cloudy':
            $iconReturned = 'wi-cloudy';
            break;
        case 'partly-cloudy-day':
            $iconReturned = 'wi-day-cloudy';
            break;
        case 'partly-cloudy-night':
            $iconReturned = 'wi-night-alt-cloudy';
            break;
        default:
            $iconReturned = 'wi-na';
            break;
    }

    return $iconReturned;
}

//Convert the string of data to an array
if ($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET')
    parse_str(file_get_contents("php://input"), $data);

if (isset($data['command'])) {
    if (substr($data['command'],  0, 4 ) == "/add") {
        $item = filter_var($data['text'], FILTER_SANITIZE_STRING);
        $addType = filter_var(substr($data['command'],  4, strlen($data['command'])), FILTER_SANITIZE_STRING);

        // Insert the new prescription into the database
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

        // Insert the new prescription into the database
        if ($insert_stmt = $mysqli->prepare("UPDATE " . $addType . " SET dateCompleted = UNIX_TIMESTAMP() WHERE dateCompleted IS NULL")) {

            // Execute the prepared query.
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

        $prep_stmt = "SELECT item, dateAdded FROM " . $addType . " WHERE dateCompleted IS NULL ORDER BY dateAdded";
        $stmt = $mysqli->prepare($prep_stmt);

        if ($stmt) {
            $stmt->execute();
            $stmt->store_result();

            // get variables from result.
            $stmt->bind_result($item, $dateAdded);

            if ($stmt->num_rows > 0) {
                $response = '|Item|Date Added|\n|:------------|:------------|\n';

                //Found the count!
                while ($stmt->fetch()) {
                    $response = $response . '|' . $item . '|' . date('F jS', $dateAdded) . '|\n';
                }

                echo '{"response_type": "in_channel", "text": "' . $response . '"}';
            } else if ($stmt->num_rows == 0) {
                //echo '{"response_type": "in_channel", "text": "Looks like there are no items!"}';
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
        $addType = filter_var(substr($data['command'],  5, strlen($data['command'])), FILTER_SANITIZE_STRING);

        //Update the patient notes
        if ($update_stmt = $mysqli->prepare("UPDATE ".$addType." SET dateCompleted = UNIX_TIMESTAMP() WHERE id = ?")) {
            $update_stmt->bind_param('i', $data['id']);

            // Execute the prepared query.
            if (!$update_stmt->execute()) {
                echo "Error: ".$mysqli->error;
            } else {
                print_r($data);
            }
        } else {
            echo "Error: ".$mysqli->error;
        }
    }
    else if ($data['command'] == "toggleLights") {
        $curlData = '{"on":'.$data['on'].'}';

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "http://192.168.20.152/api/IKyFaCiLS7o2fIXwTi1nt12mJfN7FxiJD48J6oRS/groups/0/action");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);

        //Convert the string of data to an array
        $hueResult = curl_exec($ch);

        //close connection
        curl_close($ch);

        $returnedResult = 0;

        if ($data['on'] == "true")
            $returnedResult = 1;

        echo $returnedResult;
    }
    else if ($data['command'] == 'lightsState') {
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "http://192.168.20.152/api/IKyFaCiLS7o2fIXwTi1nt12mJfN7FxiJD48J6oRS/lights");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $hueResult = curl_exec($ch);

        //Convert the string of data to an array
        $hueData = json_decode($hueResult, true);

        //close connection
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

        // Set the text if we have a previous photo
        if ($_SESSION['lastLocation']) {
            $lastPhoto = ' WHERE id NOT IN '.implode($_SESSION['lastLocation'], ", ");
        } else {
            $_SESSION['lastLocation'] = [];
            $lastPhoto = "";
        }

        // Prepare the MySQL statement
        //$prep_stmt = "SELECT path, location, date_taken FROM images".$lastPhoto." ORDER BY RAND() LIMIT 1";
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

            // Remember the last location
            array_push($_SESSION['lastLocation'], $location);

            // Check if this is the 10th entry
            if (count($_SESSION['lastLocation']) > 10) {
                array_shift($_SESSION['lastLocation']);
            }

            // Convert the DB date to a readable format
            $photoDate = new DateTime($date_taken);

            // We should have EXACTLY 1 value returned
            if ($stmt->num_rows == 1) {
                $output = array(
                    $location." - ".$photoDate->format('F Y'),
                    "/images/photos/".$path,
                    $_SESSION['lastLocation'],
                    $console_error
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
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "https://api.darksky.net/forecast/ced23c715fea437145b3182bf1065f0c/43.588145,-79.648063?units=ca&exclude=minutely,flags");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADEROPT, "Accept-Encoding: gzip");

        //execute post
        $weatherResult = curl_exec($ch);

        //close connection
        curl_close($ch);

        //Convert the string of data to an array
        $weatherData = json_decode($weatherResult, true);

        //Set today's weather
        $todayWeather = new Weather($weatherData["daily"]["data"][0]);

        //Set tomorrow's weather
        $tomorrowWeather = new Weather($weatherData["daily"]["data"][1]);

        //Set current weather
        $currently = $weatherData["currently"];
        $icon = setIcon($currently["icon"], 1);
        $apparentTemperature = round($currently["apparentTemperature"]);

        if ($todayWeather->precipProbability >= 35) {
            $clothing = "you should bring an umbrella";
        } else if ($apparentTemperature > 25){
            $clothing = "you won't need a coat";
        } else if ($apparentTemperature <= 25 and $apparentTemperature > 10) {
            $clothing = "you'll need a light coat";
        } else if ($apparentTemperature <= 10) {
            $clothing = "you'll need a winter coat";
        }

        $weatherArray = array(
            "weatherIcon" => $icon,
            "apparentTemperature" => $apparentTemperature,
            "todaySunrise" => $todayWeather->sunrise,
            "todaySunset" => $todayWeather->sunset,
            "todayTime" => $todayWeather->clock,
            "todaySummary" => $todayWeather->summary,
            "todayIcon" => $todayWeather->icon,
            "todayPrecipProbability" => $todayWeather->precipProbability,
            "todayTemperatureMin" => $todayWeather->temperatureMin,
            "todayTemperatureMax" => $todayWeather->temperatureMax,
            "todayApparentTemperatureMin" => $todayWeather->apparentTemperatureMin,
            "todayApparentTemperatureMax" => $todayWeather->apparentTemperatureMax,
            "todayWindSpeed" => $todayWeather->windSpeed,
            "tomorrowTime" => $tomorrowWeather->clock,
            "tomorrowSummary" => $tomorrowWeather->summary,
            "tomorrowIcon" => $tomorrowWeather->icon,
            "tomorrowPrecipProbability" => $tomorrowWeather->precipProbability,
            "tomorrowTemperatureMin" => $tomorrowWeather->temperatureMin,
            "tomorrowTemperatureMax" => $tomorrowWeather->temperatureMax,
            "tomorrowApparentTemperatureMin" => $tomorrowWeather->apparentTemperatureMin,
            "tomorrowApparentTemperatureMax" => $tomorrowWeather->apparentTemperatureMax,
            "tomorrowWindSpeed" => $tomorrowWeather->windSpeed,
            "clothing" => $clothing,
            "bigDate" => date('l, F jS', time())
        );

        echo json_encode($weatherArray);
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
        $accessTokenJared = '"f1ee6595-6a11-198c-356c-0acf23"';
        $accessTokenJacqueline = '"6bdcd289-7c70-43bb-05c7-47252b"';
        $consumerKey = '"69195-1cc1c210fb7e3db337ca2b78"';
        $accessTokens = array($accessTokenJared, $accessTokenJacqueline);

        foreach ($accessTokens as $accessT) {
            //Build cURL JSON submission
            $curlData = '{"url":"'.$data['pocketAddress'].'","consumer_key":'.$consumerKey.',"access_token":'.$accessT.'}';

            //Initiate cURL Object
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://getpocket.com/v3/add');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($curlData)));

            //Execute and save result
            $pocketResult = curl_exec($ch);

            //Close cURL connection
            curl_close($ch);
        }

        //Return Pocket response
        echo json_encode($pocketResult);
    }
    else {
        echo "Sorry, that command does not exist, or an error has occurred. Here's the command I recieved: " . $data['command'];
    }
}
else {
    echo "Sorry, that command does not exist, or an error has occurred. Here's the command I recieved: " . $data['command'];
}
?>
