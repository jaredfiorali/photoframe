<?php

include_once 'weather.php';

// Pull the weather information from the Government of Canada
$weather = file_get_contents('sample_weather.xml');

// Format the incoming weather
$weatherXML = simplexml_load_string($weather);
$weatherJSON = json_encode($weatherXML);
$weatherParsed = json_decode($weatherJSON);

$currentConditions = new CurrentWeather($weatherParsed->currentConditions);

$weatherForecast = [];
foreach ($weatherParsed->forecastGroup->forecast as $forecast) {
	$weatherForecast[] = new ForecastWeather($forecast);
}

var_dump($weatherForecast[0]);
