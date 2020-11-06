<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Git;

class ConfigController extends BaseController {

	/** @inheritdoc	 */
	public function getAction($param = null) {

		// Create a new Git object to retrieve the current commit ID
		$git = new Git();

		return json_encode($git->data);
	}
}
