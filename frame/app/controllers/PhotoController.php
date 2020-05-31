<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Photo;

class PhotoController extends BaseController {

	/**
	 * This method is called when the default page is being requested
	 */
	public function indexAction()	{

		// TODO: Maybe do stuff here? For now it just sends back the entire index.phtml page
	}

	/** @inheritdoc	 */
	public function getAction($param = null) {

		// Prepare and execute the MySQL statement
		$db_results = $this->db->fetchOne("CALL randomPhoto()");

		// Create a new photo object from our DB data
		$photo = new Photo($db_results);

		// Save our json in the response object
		$this->response->setContent($photo->to_json());

		// Send the version to the FE
		return $this->response;
	}
}
