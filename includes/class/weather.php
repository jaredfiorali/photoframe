<?php

class Weather {

    public $clock;
    public $summary;
    public $icon;
    public $precip_probability;
    public $temperature_min;
    public $temperature_max;
    public $apparent_temperature_min;
    public $apparent_temperature_max;
    public $wind_speed;
    public $sunrise;
    public $sunset;

	/**
	* @param string[] $weather_data Array of strings received from the weather api
	*/
    public function __construct(array $weather_data) {

        $this->clock = $weather_data["time"];
        $this->summary = strtolower($weather_data["summary"]);
        $this->icon = self::setIcon($weather_data["icon"], 1);
        $this->precip_probability = ($weather_data["precipProbability"])*100;
        $this->temperature_min = round($weather_data["temperatureMin"]);
        $this->temperature_max = round($weather_data["temperatureMax"]);
        $this->apparent_temperature_min = round($weather_data["apparentTemperatureMin"]);
        $this->apparent_temperature_max = round($weather_data["apparentTemperatureMax"]);
        $this->wind_speed = round($weather_data["windSpeed"]);
        $this->sunrise = date('g:iA', $weather_data["sunriseTime"]);
        $this->sunset = date('g:iA', $weather_data["sunsetTime"]);
    }

	/**
	* @param string $icon_text String received from the weather api
	* @param bool $is_night Boolean indicating if this should return a night-time icon
	* @return string
	*/
	public static function setIcon($icon_text, $is_night) {

		// Initialize the return variable
		$icon_returned = 'wi-na';

		// Logic to determine the Weather Icon
	    switch($icon_text) {
	        case 'clear-day':
	            $icon_returned = 'wi-day-sunny';
	            break;
	        case 'clear-night':
	            $icon_returned = 'wi-night-clear';
	            break;
	        case 'rain':
	            $icon_returned = 'wi-day-rain';
	            break;
	        case 'snow':
	            $icon_returned = 'wi-day-snow';
	            break;
	        case 'sleet':
	            $icon_returned = 'wi-day-sleet';
	            break;
	        case 'wind':
	            $icon_returned = 'wi-day-windy';
	            break;
	        case 'fog':
	            $icon_returned = 'wi-day-fog';
	            break;
	        case 'cloudy':
	            $icon_returned = 'wi-cloudy';
	            break;
	        case 'partly-cloudy-day':
	            $icon_returned = 'wi-day-cloudy';
	            break;
	        case 'partly-cloudy-night':
	            $icon_returned = 'wi-night-alt-cloudy';
	            break;
	    }

	    return $icon_returned;
	}

}
?>
