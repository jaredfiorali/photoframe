<?php

header('Access-Control-Allow-Origin: *');

include_once 'weather.php';

define('CURRENT_WEATHER_FILE', 'current.json');
define('FORECAST_WEATHER_FILE', 'forecast.json');


// Determines which endpoint we intend to reach
$endpoint = $_GET['endpoint'] ?? 'No endpoint specified';
switch ($endpoint) {
    case 'healthz':
        healthz();
        break;
    case 'getWeather':
        getWeather();
        break;
    case 'updateWeather':
        updateWeather();
        break;
    default:
        error_log('Invalid Endpoint [' . $endpoint . ']!');
        break;
}


/**
 * Returns a 200 response for Kubernetes liveliness endpoints
 */
function healthz()
{
    echo 'Working';
}

/**
 * Grabs up to date weather information from the Government of Canada
 */
function updateWeather()
{
    // Pull the weather information from the Government of Canada
    $weather = @file_get_contents('https://dd.weather.gc.ca/citypage_weather/xml/ON/s0000786_e.xml');
    // $weather = @file_get_contents('sample_weather.xml');

    // Format the incoming weather
    $weatherXML = simplexml_load_string($weather);
    $weatherJSON = json_encode($weatherXML);
    $weatherParsed = json_decode($weatherJSON);

    // Parse the current weather information and save it in a local file
    $currentConditions = new CurrentWeather($weatherParsed->currentConditions);
    file_put_contents(CURRENT_WEATHER_FILE, json_encode($currentConditions));

    // Parse the weather forecast and save it in a local file
    $weatherForecast = [];
    foreach ($weatherParsed->forecastGroup->forecast as $forecast) {
        if (stripos($forecast->period, 'night') === false) {
            $weatherForecast[] = new ForecastWeather($forecast);
        }
    }
    file_put_contents(FORECAST_WEATHER_FILE, json_encode($weatherForecast));

    echo '{
        "result": "Weather updated"
    }';

    // Don't send anything else back
    exit();
}

/**
 * Returns a JSON formatted string containing both the current and forecasted weather
 */
function getWeather()
{
    // Grab the weather from our local file
    list($current, $forecast) = pullWeather();

    // Did we get current and forecast information?
    if (!$current || !$forecast) {

        // Let's update the weather information and save it
        list($current, $forecast) = updateWeather();
    }

    // Print the weather data to the FE
    echo '{
        "current": ' . $current . ',
        "forecast": ' . $forecast . '
    }';

    // Don't send anything else back
    exit();
}

/**
 * Private function used to grab weather information from the local file
 */
function pullWeather()
{
    // Grab the weather information from our local file
    $current = @file_get_contents(CURRENT_WEATHER_FILE);
    $forecast = @file_get_contents(FORECAST_WEATHER_FILE);

    return [$current, $forecast];
}

?>

<html>

<head>
    <style>
        body {
            background-color: white;
        }

        button {
            display: inline-block;
            padding: 0.3em 1.2em;
            margin: 0 0.1em 0.1em 0;
            border: 0.16em solid rgba(255, 255, 255, 0);
            border-radius: 2em;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
            color: #FFFFFF;
            text-shadow: 0 0.04em 0.04em rgba(0, 0, 0, 0.35);
            text-align: center;
            transition: all 0.2s;
            width: 12em;
            height: 3em;
            font-size: 1.5em;
        }

        button:hover {
            border-color: rgba(255, 255, 255, 1);
        }

        button.updateWeather {
            background-color: #f1bb4e;
        }

        button.getWeather {
            background-color: #9a4ef1;
        }

        @media all and (max-width:30em) {
            button {
                display: block;
                margin: 0.2em auto;
            }
        }
    </style>
</head>
<script>
    function request(endpoint) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", '?endpoint=' + endpoint, false); // false for synchronous request
        xmlHttp.send(null);

        let response = xmlHttp.responseText;
        let obj = JSON.parse(response);
        let formatted = JSON.stringify(obj, undefined, 4);

        document.getElementById('output').value = formatted;
    }
</script>

<body>
    <button type="button" class="updateWeather" onclick="request('updateWeather')">Update Weather</button>
    </br>
    <button type="button" class="getWeather" onclick="request('getWeather')">Get Weather</button>
    </br>
    <textarea id="output" name="output" rows="50" cols="100"></textarea>

</body>

</html>
