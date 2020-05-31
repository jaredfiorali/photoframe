<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Services\ConfigService;

class AdminController extends BaseController {

	/**
	 * This method is called before the anything is served
	 */
	public function initialize() {

		parent::initialize();

		// Add some local CSS resources
		$this->assets->addCss('css/admin.css', true);

		// And some local JavaScript resources
		$this->assets->addJs('js/admin.min.js', true);
	}

	/**
	 * This method is called when the default page is being requested
	 */
	public function indexAction() {

		// TODO: Maybe do stuff here? For now it just sends back the entire index.phtml page
	}

	/**
	 * This method is called when dashboard/main is being requested
	 */
	public function mainAction() {

	}

	public function photoManagementAction($param = null) {

		// Check to see if we received some parameters
		if ($param) {

			if ($param == 'upload_preview' or $param == 'upload_final') {

				$image = file_get_contents($_FILES['file']['tmp_name']);
				$image_base64 = base64_encode($image);

				$json_array = [
					'photo' => $image_base64,
				];

				if ($param === 'upload_preview') {

					$this->response->setContent(json_encode($json_array));

					return $this->response;
				}
				else {

					// Prepare and execute the MySQL statement
					$db_results = $this->db->execute("INSERT into photos (photo, location, date_taken) VALUES ('$image_base64', '$location', '$date_taken')");
				}
			}
			else if ($param == 'update') {

				// Set our header to json
				$this->response->setHeader("Content-Type", "application/json");
			}
		}
		// Default action please
		else {

			// Grab all the photos we have in the DB (not the photos themselves...we aren't crazy)
			$results = $this->db->fetchAll('SELECT id, location, date_taken FROM Dashboard.photos ORDER BY location');

			// Send the list of photos to the view
			$this->view->setVar('db_photos', $results);
			$this->view->setVar('now', date('Y-m-d'));
			$this->view->setVar('image_width', ConfigService->page_width);
			$this->view->setVar('image_height', ConfigService->page_height);

			// Select the view we intend to display
			$this->view->pick('admin/photoManagement');
		}
	}

	/** @inheritdoc	 */
	public function getAction($param_one = null, $param_two = null) {

		// Start by assuming we won't get anything
		$data = null;

		// Set our header to json
		$this->response->setHeader("Content-Type", "application/json");

		// Photo route
		if ($param_one == 'photo') {

			// Confirm we have a photo ID
			if ($param_two) {

				// Grab the photo from the DB
				$result = $this->db->fetchOne("SELECT photo, location, date_taken FROM Dashboard.photos WHERE id = $param_two");

				$date_taken = $result['date_taken'] ?? null;

				$data = [
					'photo' => $result['photo'] ?? null,
					'location' => $result['location'] ?? null,
					'date_taken' => date('Y-m-d', strtotime($date_taken)),
				];

				// Set this as a success
				$this->response->setStatusCode(200, "OK");
			}
		}

		// Respond to the FE
		$this->response->setContent(json_encode($data));

		return $this->response;
	}
}
