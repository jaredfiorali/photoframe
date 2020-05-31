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
	public $development_mode = false;

	/**
	 * The database host
	 * @var string DB_HOST
	**/
	public $db_host = 'mysql.mysql';

	/**
	 * The username we will use to access the database
	 * @var string DB_USERNAME
	**/
	public $db_username = 'photoframe';

	/**
	 * The password we will use to access the database
	 * @var string DB_PASSWORD
	**/
	public $db_password = 'fb8bda50b9f86b229be154a8e32edfc7';

	/**
	 * The database name we will be accessing
	 * @var string DB_DATABASE
	**/
	public $db_database = 'Dashboard';

    /**
     * API key for Darksky access
     * @var string
     */
	public $darksky_api_key = 'ced23c715fea437145b3182bf1065f0c';

    /**
     * Latitude for the location where we intend to get weather information
     * @var string (kind of...I mean it's really a decimal, but in string format)
     */
	public $weather_latitude = '43.595844';

    /**
     * Longitude for the location where we intend to get weather information
     * @var string (kind of...I mean it's really a decimal, but in string format)
     */
	public $weather_longitude = '-79.708389';

    /**
     * Array of access tokens for sending data to Pocket
     * @var string[]
     */
	public $pocket_access_tokens = ['f1ee6595-6a11-198c-356c-0acf23', '6bdcd289-7c70-43bb-05c7-47252b'];

    /**
     * Consumer key for accessing Pocket
     * @var string
     */
	public $pocket_consumer_key = '69195-1cc1c210fb7e3db337ca2b78';

    /**
     * API key for accessing news from newsapi.org
     * @var string
     */
    public $news_api_key = 'fa9bfe5bd5274c81a2dc4579d0e74620';

    /**
     * Comma separated list of sources. Find more information below.
     * @link https://newsapi.org/docs/endpoints/sources
     * @var string
     */
	public $news_sources = 'cbc-news,the-new-york-times,bbc-news';

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
	 * Initialize the Config vars via environment variables
	**/
	public function __constructor() {

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
     * @param string $key The name of the configuration parameter we are trying to find
     */
    public function get_value($key = '') {

        return $this->{$key} ?? null;
    }
}
