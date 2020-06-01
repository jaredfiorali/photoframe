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

$app->handle($_SERVER['REQUEST_URI']);
