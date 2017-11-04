<?php
class DB {

	private $mysqli;
	private $stmt;
	private $stmt_meta;
	private $results;

	/**
	* Creates a mysql object to connect to the database
	*/
	public function __construct() {

		// Create the mysql object we will use to connect to the database
		$this->mysqli = new mysqli("localhost", "apache", "fb8bda50b9f86b229be154a8e32edfc7", "Dashboard");

		// Check connection worked
		if ($this->mysqli->connect_error) {
			die("Connection failed: " . $this->mysqli->connect_error);
		}
	}

	/**
	* Executes a query against the database
	* @param string $prep_stmt String that will be executed on the database
	* @return array
	*/
	public function execute(string $prep_stmt) {

		// Create the prepared statement and store in in a variable
        $stmt = $this->mysqli->prepare($prep_stmt);

        // Execute the prepared query
        if ($stmt->execute()) {

			// Check to make sure the query was a success
			if (!$stmt->error) {

				// Save the results to an array
				$query_results = $stmt->get_result()->fetch_array();
			}
		}

		// Close the database connection after saving the variables
        $stmt->close();

		// Return the result from the query
		return $query_results;
	}
}
?>
