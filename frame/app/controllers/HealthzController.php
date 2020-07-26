<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Status;

class HealthzController extends BaseController {

	/**
	 * This method is called when the default page is being requested
	 */
	public function indexAction() {

		// Update our response with the status we received
		$this->generateResponse($this->getStatus());
	}

	/**
	 * Checks various services and returns a failed status code if any service is unavailable
	 */
	private function getStatus() {

		// Start with nothing
		$status = null;

		// Run a query to determine if our DB connection is working
		$result = $this->db->fetchOne("SELECT * FROM cache");

		// Check the count of the results
		if (count($result) == 0) {

			// We don't have any results, so something is wrong
			$status - new Status(['success' => false]);
		}

		// Return our status object (or create a success if we don't have one already)
		return $status ?? new Status(['success' => true]);
	}

	/**
	 * Sets required parameters of the response object
	 * @param Status $status A status object containing details we need
	 */
	private function generateResponse($status) {

		// Set the status code
		$this->response->setStatusCode($status->code, $status->code_message);

		// It's always JSON
		$this->response->setHeader("Content-Type", "application/json");

		// Save our json in the response object
		$this->response->setContent($status->to_json());
	}
}
