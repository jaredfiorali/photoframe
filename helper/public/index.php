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
		"options" => [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
		],
	]);
};

// Sets up a lister for the weather service
$app->get(
	'/listenWeather',
	function () use ($app) {
		$this->response->setHeader('Content-Type', 'text/event-stream');

		$counter = rand(1, 10);
		while (true) {
			$result = $app['db']->fetchOne("CALL getWeather()");

			echo $result;

			flush();
			sleep(1);
		}
	}
);

$app->handle($_SERVER['REQUEST_URI']);
