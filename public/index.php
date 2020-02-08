<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use App\Services\ConfigService;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

//Register our namespaces
$loader->registerNamespaces([
	'App\Controllers' => '../app/controllers/',
	'App\Controllers\Base' => '../app/controllers/base',
	'App\Models' => '../app/models/',
	'App\Services' => '../app/services/',
	'App\Enums' => '../app/enums/',
	'App\Entities' => '../app/entities/',
	'App\Entities\Base' => '../app/entities/base',
])->register();

// Register common directories
$loader->registerDirs([
	APP_PATH . '/controllers/',
	APP_PATH . '/models/',
]);

// Wrap it up
$loader->register();

// Create a DI
$di = new FactoryDefault();

$di->set(
	'dispatcher',
	function() {
		$dispatcher = new \Phalcon\Mvc\Dispatcher();
		$dispatcher->setDefaultNamespace('App\Controllers');
		return $dispatcher;
});

// Setup the view component
$di->set(
	'view',
	function () {
		$view = new View();
		$view->setViewsDir(APP_PATH . '/views/');
		return $view;
	}
);

// Setup a base URI
$di->set(
	'url',
	function () {
		$url = new UrlProvider();
		$url->setBaseUri('/');
		return $url;
	}
);

// Setup the database service
$di->set(
	'db',
	function () {
		return new DbAdapter([
			'host'     => ConfigService::DB_HOST,
			'username' => ConfigService::DB_USERNAME,
			'password' => ConfigService::DB_PASSWORD,
			'dbname'   => ConfigService::DB_DATABASE,
			"options" => [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			],
		]);
	}
);

// Register Volt as a service
$di->set(
	'voltService',
	function ($view, $di) {
		$volt = new Volt($view, $di);

		$volt->setOptions(
			[
				'compiledPath'      => '../app/templates/',
				'compiledExtension' => '.compiled',
			]
		);

		return $volt;
	}
);

// Register Volt as template engine
$di->set(
	'view',
	function () {
		$view = new View();

		$view->setViewsDir('../app/views/');

		$view->registerEngines(
			[
				'.phtml' => 'voltService',
			]
		);

		return $view;
	}
);

// Your json service...
$di->setShared('AuthService', function() {
	return new ConfigService();
});

$application = new Application($di);

try {
	// Handle the request
	$response = $application->handle();

	$response->send();
} catch (\Exception $e) {
	echo 'Exception: ', $e->getMessage();
}