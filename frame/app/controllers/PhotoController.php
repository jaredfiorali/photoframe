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

		// Return our data
		return json_encode($photo->data);
	}
}
