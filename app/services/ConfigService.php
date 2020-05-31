<?php

namespace App\Services;

use Phalcon\DI;
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
	public $development_mode = false;

	/**
	 * The database host
	 * @var string DB_HOST
	**/
	public $db_host;

	/**
	 * The username we will use to access the database
	 * @var string DB_USERNAME
	**/
	public $db_username;

	/**
	 * The password we will use to access the database
	 * @var string DB_PASSWORD
	**/
	public $db_password;

	/**
	 * The database name we will be accessing
	 * @var string DB_DATABASE
	**/
	public $db_database;

	/**
	 * API key for Darksky access
	 * @var string
	 */
	public $darksky_api_key;

	/**
	 * Latitude for the location where we intend to get weather information
	 * @var string (kind of...I mean it's really a decimal, but in string format)
	 */
	public $weather_latitude;

	/**
	 * Longitude for the location where we intend to get weather information
	 * @var string (kind of...I mean it's really a decimal, but in string format)
	 */
	public $weather_longitude;

	/**
	 * Array of access tokens for sending data to Pocket
	 * @var string[]
	 */
	public $pocket_access_tokens;

	/**
	 * Consumer key for accessing Pocket
	 * @var string
	 */
	public $pocket_consumer_key;

	/**
	 * API key for accessing news from newsapi.org
	 * @var string
	 */
	public $news_api_key;

	/**
	 * Comma separated list of sources. Find more information below.
	 * @link https://newsapi.org/docs/endpoints/sources
	 * @var string
	 */
	public $news_sources;

	/**
	 * Width of the page we intend to present (typically tablet screen size)
	 * @var integer
	 */
	public $page_width = 1024;

	/**
	 * Height of the page we intend to present (typically tablet screen size)
	 * @var integer
	 */
	public $page_height = 569;

	/**
	 * Empty constructor...not used
	**/
	public function __constructor() {
		// Silence is golden...
	}

	/**
	 * Initialize the Config vars via environment variables
	 */
	public function initialize() {
		$this->development_mode = getenv('DEVELOPMENT_MODE');
		$this->db_host = getenv('DB_HOST');
		$this->db_username = getenv('DB_USERNAME');
		$this->db_password = getenv('DB_PASSWORD');
		$this->db_database = getenv('DB_DATABASE');
		$this->darksky_api_key = getenv('DARKSKY_API_KEY');
		$this->weather_latitude = getenv('WEATHER_LATITUDE');
		$this->weather_longitude = getenv('WEATHER_LONGITUDE');
		$this->pocket_access_tokens = getenv('POCKET_ACCESS_TOKENS');
		$this->pocket_consumer_key = getenv('POCKET_CONSUMER_KEY');
		$this->news_sources = getenv('NEWS_SOURCES');
		$this->news_api_key = getenv('NEWS_API_KEY');
		$this->page_width = getenv('PAGE_WIDTH');
		$this->page_height = getenv('PAGE_HEIGHT');
	}

	/**
	 * Returns the value of the configuration (if exists)
	 * Accessed statically so it's easier to look at externally :P
	 * @param string $key The name of the configuration parameter we are trying to find
	 */
	public static function get_value($key = '') {

		return Di::getDefault()['Config']->value($key);
	}

	/**
	 * Returns the value of the configuration (if exists)
	 * @param string $key The name of the configuration parameter we are trying to find
	 */
	private function value($key = '') {

		return $this->{$key} ?? null;
	}
}
