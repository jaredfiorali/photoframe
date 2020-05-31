<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\FactoryDefault;
use Phalcon\Url as UrlProvider;
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
	'App\Enums' => '../app/enums/',
	'App\Entities' => '../app/entities/',
	'App\Entities\Base' => '../app/entities/base',
	'App\Filters' => '../app/filters/',
	'App\Models' => '../app/models/',
	'App\Services' => '../app/services/',
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
		$dispatcher = new Dispatcher();
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
	function ($view) {
		$volt = new Volt($view);

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

// Register our Config service for access to configuration parameters
$di->setShared('Config', function() {
	$configService = new ConfigService();

	return $configService->initialize();
});

$application = new Application($di);

try {
	var_dump($di['Config']->get_value('DEVELOPMENT_MODE'));

	// // Handle the request
	// $response = $application->handle($_SERVER['REQUEST_URI']);

	// $response->send();
} catch (\Exception $e) {
	$message = $e->getMessage();
	$trace = $e->getTrace();

	echo "Exception: $message\n";
	var_dump($trace);
}