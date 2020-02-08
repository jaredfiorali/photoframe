<?php

namespace App\Services;

use Phalcon\DI\Injectable;

/**
 * A collection of constants which are used all over this application
 * One-stop shop!
**/
class ConfigService extends Injectable
{
	/**
	 * If we should have certain development features enabled (minification for instance)
	 * @var boolean DEVELOPMENT_MODE
	**/
	const DEVELOPMENT_MODE = true;

	/**
	 * The database host
	 * @var string DB_HOST
	**/
	const DB_HOST = 'mysql.mysql.svc.cluster.local';

	/**
	 * The username we will use to access the database
	 * @var string DB_USERNAME
	**/
	const DB_USERNAME = 'photoframe';

	/**
	 * The password we will use to access the database
	 * @var string DB_PASSWORD
	**/
	const DB_PASSWORD = 'fb8bda50b9f86b229be154a8e32edfc7';

	/**
	 * The database name we will be accessing
	 * @var string DB_DATABASE
	**/
	const DB_DATABASE = 'Dashboard';

	const DARKSKY_API_KEY = 'ced23c715fea437145b3182bf1065f0c';

	const WEATHER_LATITUDE = '43.595844';

	const WEATHER_LONGITUDE = '-79.708389';

	const POCKET_ACCESS_TOKENS = ['f1ee6595-6a11-198c-356c-0acf23', '6bdcd289-7c70-43bb-05c7-47252b'];

	const POCKET_CONSUMER_KEY = '69195-1cc1c210fb7e3db337ca2b78';

	const NEWS_SOURCES = 'cbc-news,the-new-york-times,bbc-news';

	const NEWS_API_KEY = 'fa9bfe5bd5274c81a2dc4579d0e74620';

	const PHILIPS_HUE_TOKEN = 'sOoZ7udF4PHgrQukaDM3LpnF5BW9v9DBs5frKQyG';

	const PAGE_WIDTH = 1024;

	const PAGE_HEIGHT = 569;

	/**
	 * A constructor. Not really needed here...
	**/
	public function __constructor() {
		// Silence is golden
	}
}
