<?php

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
function healthz() {
    echo 'Working';
}

/**
 * Grabs up to date weather information from the Government of Canada
 */
function updateWeather() {

    // Pull the weather information from the Government of Canada
    $weather = @file_get_contents('sample_weather.xml');

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
        $weatherForecast[] = new ForecastWeather($forecast);
    }
    file_put_contents(FORECAST_WEATHER_FILE, json_encode($weatherForecast));

    return [json_encode($currentConditions), json_encode($weatherForecast)];
}

/**
 * Returns a JSON formatted string containing both the current and forecasted weather
 */
function getWeather() {

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
}

/**
 * Private function used to grab weather information from the local file
 */
function pullWeather() {
    // Grab the weather information from our local file
    $current = @file_get_contents(CURRENT_WEATHER_FILE);
    $forecast = @file_get_contents(FORECAST_WEATHER_FILE);

    return [$current, $forecast];
}
