<?php

namespace App\Controllers\Base;

use Phalcon\Mvc\Controller;
use Phalcon\Di;
use App\Filters\UglifyJS;
use App\Services\ConfigService;

abstract class BaseController extends Controller {

	/**
	 * The model we will be using in order to access the database and execute our stored procedures
	 */
	protected $db;

	/**
	 * This method is called before the anything is served
	 */
	public function initialize() {

		// Check to see if we already have a database object
		if (!$this->db) {

			// Get a reference to our Dependancy Injector
			$di = Di::getDefault();

			// Create a reference object in order to interact with our DB
			$this->db = $di['db'];
		}

		// Add some local CSS resources
		$this->assets->addCss('vendor/fontawesome/css/fontawesome-all.min.css', false, false);
		$this->assets->addCss('vendor/bootstrap.min.css', false, false);

		// And some local JavaScript libraries
		$librariesCollection = $this->assets->collection('librariesCollection');
		$librariesCollection->setPrefix('');
		$librariesCollection->addJs('js/vendor/jquery-3.3.1.min.js', false, false);
		$librariesCollection->addJs('js/vendor/bootstrap.min.js', false, false);

		$baseCollection = $this->assets->collection('baseCollection');
		$baseCollection->join(true);
		$baseCollection->setPrefix('');
		$baseCollection->addJs('js/utility.js', false, true);

		// Now for the custom javascript
		if (!ConfigService::get_value('development_mode')) {
			$baseCollection->setTargetPath('js/base.min.js');
			$baseCollection->setTargetUri('js/base.min.js');
			$baseCollection->addFilter(new UglifyJS());
		}
	}

	/**
	 * A method used to retrieve data pertaining to the child controller
	 * @param string $param The incoming url parameter
	 */
	public function getAction($param = null) {

	}

	/**
	 * Contacts the third party provider using the Config variables, and updates the appropriate information in our DB cache
	 */
	public function updateAction() {

	}

	/**
	 * Standard cURL request
	 * TODO: jfiorali - There's probably a better place for this...
	 * @param string $url The URL we want to issue a cURL request to
	 * @return string
	 */
	public function curl_request($url) {

		// Create curl resource
		$ch = curl_init();

		// Set url
		curl_setopt($ch, CURLOPT_URL, $url);

		// Return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// Retrieve the results
		$result = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch);

		return $result;
	}

	/**
	 * Always executes after a controller action
	 */
	public function afterExecuteRoute($dispatcher) {

		// Save our json in the response object
		$this->response->setJsonContent($dispatcher->getReturnedValue());

		// Send the version to the FE
		return $this->response;
	}
}
