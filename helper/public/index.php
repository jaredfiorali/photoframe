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
		$this->response->setHeader('Content-Type', 'text/event-stream');

		$weather = null;

		while (true) {
			$result = $app['db']->fetchOne("CALL getWeather()");
			$weather_result = $result['weather'];

			if ($weather != $weather_result) {

				$weather = $weather_result;
				echo $weather_result;
			}

			flush();
			sleep(120);
		}
	}
);

$app->handle($_SERVER['REQUEST_URI']);
