<?php

use Phalcon\Mvc\Micro;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

$app = new Micro();

// Setup the database service
$app['db'] = function () {
	return new DbAdapter([
		'host'     => getenv('DB_HOST'),
		'username' => getenv('DB_USERNAME'),
		'password' => getenv('DB_PASSWORD'),
		'dbname'   => getenv('DB_DATABASE'),
	]);
};

// Sets up a lister for the weather service
$app->get(
	'/listenWeather',
	function () use ($app) {

		// SSE needs to be set to 'text/event-stream', and since we are accepting connections from photoframe.fiora.li...CORS
		$this->response->setHeader('Content-Type', 'text/event-stream');
		$this->response->setHeader('Access-Control-Allow-Origin', '*');
		$this->response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
		$this->response->sendHeaders();

		// Start knowing nothing
		$weather = '';

		// Loop...forever
		while (true) {

			error_log('Reading weather information from the database');

			// Get the weather from the database
			$result = $app['db']->fetchOne("CALL getWeather()");

			// Store the result from the database in a variable
			$weather_result = $result['weather'] ?? null;

			// Confirm that something has changed from what we knew before
			if ($weather != $weather_result) {

				error_log('----------Weather information has updated, posting to client via SSE----------');

				// Update the saved weather result with the new one
				$weather = $weather_result;

				// Echo it so the client will pick up the latest data
				echo $weather_result;
			}

			// Flush the output buffer
			flush();

			// Wait 10 seconds, and then do it all again
			sleep(10);
		}
	}
);

// Sets up a lister for the weather service
$app->get(
	'/listenTest',
	function () use ($app) {

		// SSE needs to be set to 'text/event-stream', and since we are accepting connections from photoframe.fiora.li...CORS
		$this->response->setHeader('Content-Type', 'text/event-stream');
		$this->response->setHeader('Cache-Control', 'no-cache');
		$this->response->setHeader('Access-Control-Allow-Origin', '*');
		$this->response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
		$this->response->sendHeaders();

		// A random counter
		$counter = rand(1, 10);

		// 1 is always true, so repeat the while loop forever (aka event-loop)
		while (1) {

			$curDate = date(DATE_ISO8601);
			echo "event: ping\n", 'data: {"time": "' . $curDate . '"}', "\n\n";

			// Continue to decrement the counter
			$counter--;

			// Check to see if the counter has finally reached 0
			if ($counter == 0) {

				echo 'data: This is a message at time ' . $curDate, "\n\n";

				// Reset random counter
				$counter = rand(1, 10);
			}

			// Flush the output buffer and send echoed messages to the browser
			while (ob_get_level() > 0) {
				ob_end_flush();
			}

			// Clear the output buffer
			flush();

			// Break the loop if the client aborted the connection (closed the page)
			if (connection_aborted()) {

				break;
			}

			// Sleep for 1 second before running the loop again
			sleep(1);
		}
	}
);



$app->handle($_SERVER['REQUEST_URI']);
