<?php

class Git {

	private $commit = [];

	/**
	 * Constructor for git object
	 * @param string $branch The branch name we want the commit id for
	 */
	public function __construct($branch='master') {

		if (!$this->commit[0] = rtrim(file_get_contents(sprintf( '../.git/refs/heads/%s', $branch)))) {

			$this->commit[0] = false;
		}
	}

	/**
	* Retrieves the latest git commit
	*/
	public function get_latest_git_commit() {

		return $this->commit;
	}
}
