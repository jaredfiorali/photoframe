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

		// We know nothing
		$weather = '';

		// Loop...forever
		while (true) {

			// Stops PHP from checking for user disconnect
			ignore_user_abort(true);

			// Check if the client has disconnected from this session
			if (connection_aborted()) {

				print "---Client SSE session is closed---\n\n";

				exit();
			}

			// Get the weather from the database
			$weather_result = $app['db']->fetchOne("CALL getWeather()")['weather'] ?? null;

			// Confirm that something has changed from what we knew before
			if ($weather != $weather_result) {

				// Update the saved weather result with the new one
				$weather = $weather_result;

				// Echo it so the client will pick up the latest data
				echo 'data: ' . $weather, "\n\n";
			}
			else {

				// Echo it so the client will pick up the latest data
				$curDate = date(DATE_ISO8601);
				echo "event: ping\n", 'data: {"time": "' . $curDate . '"}', "\n\n";
			}

			// Flush the output buffer
			@ob_end_flush();
			@flush();

			// Wait 10 seconds, and then do it all again
			sleep(10);
		}
	}
);


$app->handle($_SERVER['REQUEST_URI']);
