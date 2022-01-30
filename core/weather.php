<?php

class CurrentWeather {

	/** @var float $feelsLike */
	public $feelsLike;
	/** @var string $condition */
	public $condition;
	/** @var int $iconCode */
	public $iconCode;
	/** @var float $temperature */
	public $temperature;
	/** @var float $dewpoint */
	public $dewpoint;
	/** @var int $humidex */
	public $humidex;
	/** @var float $pressure */
	public $pressure;
	/** @var float $visibility */
	public $visibility;
	/** @var int $relativeHumidity */
	public $relativeHumidity;
	/** @var Wind $wind */
	public $wind;

	public function __construct($weather) {
		if ($weather->windChill) {
			$this->feelsLike = $weather->windChill;
		} else if ($weather->humidex) {
			$this->feelsLike = $weather->humidex;
		} else {
			$this->feelsLike = $this->temperature;
		}

		$this->condition = $weather->condition ?? null;
		$this->iconCode = $weather->iconCode ?? null;
		$this->temperature = $weather->temperature ?? null;
		$this->dewpoint = $weather->dewpoint ?? null;
		$this->humidex = $weather->humidex ?? null;
		$this->pressure = $weather->pressure ?? null;
		$this->visibility = $weather->visibility ?? null;
		$this->relativeHumidity = $weather->relativeHumidity ?? null;
		$this->wind = new Wind($weather->wind ?? null);
	}
}

class ForecastWeather {

	/** @var string $period */
	public $period;
	/** @var string $abbreviatedForecast */
	public $abbreviatedForecast;
	/** @var string $iconCode */
	public $iconCode;
	/** @var string $temperature */
	public $temperature;
	/** @var string $windChill */
	public $windChill;
	/** @var string $humidex */
	public $humidex;
	/** @var string $precipitationType */
	public $precipitationType;
	/** @var string $precipitationChance */
	public $precipitationChance;
	/** @var string $precipitationStart */
	public $precipitationStart;
	/** @var string $precipitationEnd */
	public $precipitationEnd;
	/** @var string $uv */
	public $uv;

	public function __construct($weather) {
		if ($this->humidex) {
			$this->feelsLike = $weather->humidex;
		} else if (isset($weather->windChill->calculated[1])) {
			$this->feelsLike = $weather->windChill->calculated[1];
		} else {
			$this->feelsLike = $weather->temperatures->temperature;
		}

		$this->period = $weather->period ?? null;
		$this->abbreviatedForecast = $weather->abbreviatedForecast->textSummary ?? null;
		$this->iconCode = $weather->abbreviatedForecast->iconCode ?? null;
		$this->temperature = $weather->temperatures->temperature ?? null;
		$this->humidex = $weather->humidex->calculated ?? null;
		$this->windChill = $weather->windChill ?? null;
		$this->precipitationType = gettype($weather->precipitation->precipType) === 'string' ? $weather->precipitation->precipType : null;
		$this->precipitationChance = gettype($weather->abbreviatedForecast->pop) === 'string' ? $weather->abbreviatedForecast->pop : 0;
		$this->precipitationStart = $weather->precipitation->precipType->{'@attributes'}->start ?? null;
		$this->precipitationEnd = $weather->precipitation->precipType->{'@attributes'}->end ?? null;
		$this->uv = $weather->uv->index ?? 1;
	}
}

class Wind {
	/** @var int $speed */
	public $speed;
	/** @var string $direction */
	public $direction;
	/** @var float $bearing */
	public $bearing;

	public function __construct($wind) {
		$this->speed = $wind->speed ?? null;
		$this->direction = $wind->direction ?? null;
		$this->bearing = $wind->bearing ?? null;
	}
}
