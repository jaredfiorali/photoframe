<?php

class CurrentWeather {

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
		$this->condition = $weather->condition ?? '';
		$this->iconCode = $weather->iconCode ?? '';
		$this->temperature = $weather->temperature ?? '';
		$this->dewpoint = $weather->dewpoint ?? '';
		$this->humidex = $weather->humidex ?? '';
		$this->pressure = $weather->pressure ?? '';
		$this->visibility = $weather->visibility ?? '';
		$this->relativeHumidity = $weather->relativeHumidity ?? '';
		$this->wind = new Wind($weather->wind ?? null);
	}
}

class ForecastWeather {

	/** @var string $period */
	public $period;
	/** @var string $textSummary */
	public $abbreviatedForecast;
	/** @var string $temperature */
	public $temperature;
	/** @var string $humidex */
	public $humidex;
	/** @var string $precipitationStart */
	public $precipitationStart;
	/** @var string $precipitationEnd */
	public $precipitationEnd;
	/** @var string $uv */
	public $uv;

	public function __construct($weather) {
		$this->period = $weather->period ?? '';
		$this->abbreviatedForecast = $weather->abbreviatedForecast->textSummary ?? '';
		$this->temperature = $weather->temperatures->temperature ?? '';
		$this->humidex = $weather->humidex->calculated ?? '';
		$this->precipitationStart = $weather->precipitation->precipType->{'@attributes'}->start ?? '';
		$this->precipitationEnd = $weather->precipitation->precipType->{'@attributes'}->end ?? '';
		$this->uv = $weather->uv->index ?? '';
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
		$this->speed = $wind->speed ?? '';
		$this->direction = $wind->direction ?? '';
		$this->bearing = $wind->bearing ?? '';
	}
}
