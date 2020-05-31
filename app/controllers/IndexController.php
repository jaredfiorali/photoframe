<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Filters\UglifyJS;
use App\Services\ConfigService;
use Phalcon\Assets\Filters;

class IndexController extends BaseController {

	/**
	 * This method is called when the default page is being requested
	 */
	public function indexAction() {

		$auroraCollection = $this->assets->collection('auroraJS');
		$auroraCollection->join(true);
		$auroraCollection->setPrefix('');
		$auroraCollection->addJs('js/aurora.js', false, true);
		$auroraCollection->addJs('js/div-generator.js', false, true);

		// Check to see if we are debugging
		if (!ConfigService::DEVELOPMENT_MODE) {
			$auroraCollection->setTargetPath('js/aurora-complete.js');
			$auroraCollection->setTargetUri('js/aurora-complete.js');
            $auroraCollection->addFilter(new UglifyJS());
		}
	}

	/**
	 * This method is called when dashboard/main is being requested
	 */
	public function mainAction() {
	}
}
