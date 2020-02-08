<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;

class FlightController extends BaseController {

	static $debug = true;
//	static $overhead_flights_json = '{"time":1559609850,"states":[["c06a8b","GGN7306 ","Canada",1559609849,1559609849,-79.7069,43.5883,1501.14,false,124.37,218.96,11.38,null,1501.14,"2201",false,0],["c05ebb","WJA1117 ","Canada",1559609849,1559609849,-79.7502,43.5956,2583.18,false,145.1,88.98,-6.18,null,2583.18,"4732",false,0]]}';
	static $overhead_flights_json = '{"time":1560388110,"states":[["c088fb","WJA2581 ","Canada",1560388109,1560388109,-79.7118,43.6,685.8,false,87.33,46.43,-4.55,null,662.94,"6105",false,0]]}';
	static $flight_info_json = '[{"icao24":"c06a8b","firstSeen":1559511493,"estDepartureAirport":"CYYZ","lastSeen":1559514683,"estArrivalAirport":"KCMH","callsign":"GGN7341 ","estDepartureAirportHorizDistance":706,"estDepartureAirportVertDistance":85,"estArrivalAirportHorizDistance":4722,"estArrivalAirportVertDistance":224,"departureAirportCandidatesCount":1,"arrivalAirportCandidatesCount":12}]';

	/**
	 * This method is called before the anything is served
	 */
	public function initialize() {

		parent::initialize();

		// Add some local CSS resources
		$this->assets->addCss('../css/flight.css', true);

		// And some local JavaScript resources
		$this->assets->addJs('../js/flight.js', true);
	}

	/**
	 * This method is called when the default page is being requested
	 */
	public function indexAction() {

		$departure_airport = null;
		$arrival_airport = null;

		// Get a list of the planes overhead
		if ($this::$debug) {

			$overhead_flights_json = $this::$overhead_flights_json;
		}
		else {

			 $overhead_flights_json = $this->curl_request("https://opensky-network.org/api/states/all?lamin=43.562426&lomin=-79.751627&lamax=43.622814&lomax=-79.668206");
		}

		// Decode the received flights list
		$overhead_flights = json_decode($overhead_flights_json, true);

		// Make sure we have states to look at
		if ($states = $overhead_flights['states'] ?? null) {

			// Extract the first flight
			if ($flight = $states[0] ?? null) {

				// We should also have a icao too...
				if ($icao24 = $flight[0] ?? null) {

					// Given all of that, let's see if we have a velocity and altitude
					if ($velocity = $flight[9] ?? null) {

						$this->view->setVar('velocity', $velocity);
					}

					if ($altitude = $flight[13] ?? null) {

						$this->view->setVar('altitude', $altitude);
					}

					if ($direction = $flight[10] ?? null) {

						if ($direction > 180) {

							$departure_airport = 'CYYZ';
						}
						else {

							$arrival_airport = 'CYYZ';
						}
					}

					$now = time();
					$before = $now - 24*60*60;

					// Get detailed information on the overhead flight
					if (!$this::$debug) {

						$flight_info_json = $this::$flight_info_json;
					}
					else {

						$flight_info_json = $this->curl_request("https://jaredfiorali:Garnet423@opensky-network.org/api/flights/aircraft?icao24=$icao24&begin=$before&end=$now");
					}

					// Extract the detailed flight info
					if ($flight_info_array = json_decode($flight_info_json, true) ?? []) {

						$departure_airports = array_column($flight_info_array, 'estDepartureAirport');
						$arrival_airports = array_column($flight_info_array, 'estArrivalAirport');

						$airports = array_merge($departure_airports, $arrival_airports);

						// TODO: jfiorali - This doesn't really work yet...still have to work out the logic
						foreach ($airports as $airport) {

							if ($airport !== 'CYYZ' and !is_null($airport)) {

								if ($departure_airport == 'CYYZ' or is_null($departure_airport)) {

									$arrival_airport = $airport;
								}

								if ($arrival_airport == 'CYYZ' or is_null($arrival_airport)) {

									$departure_airport = $airport;
								}
							}

							// Confirm that we have departure and arrival airport codes
							if ($departure_airport and $arrival_airport) {

								$this->view->setVar('departure_airport', $departure_airport);
								$this->view->setVar('arrival_airport', $arrival_airport);

								break;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This method is called when dashboard/main is being requested
	 */
	public function mainAction() {

	}
}
