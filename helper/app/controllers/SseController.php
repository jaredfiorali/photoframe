<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Git;

class SseController extends BaseController
{
	/** @inheritdoc	 */
	public function getAction($param = null)
	{
        $this->response->setHeader('Content-Type', 'text/event-stream');

        $counter = rand(1, 10);
        while (true) {
            // Every second, send a "ping" event.

            echo "event: ping\n";
            $curDate = date(DATE_ISO8601);
            echo 'data: {"time": "' . $curDate . '"}';
            echo "\n\n";

            // Send a simple message at random intervals.

            $counter--;

            if (!$counter) {
                echo 'data: This is a message at time ' . $curDate . "\n\n";
                $counter = rand(1, 10);
            }

            ob_end_flush();
            flush();
            sleep(1);
        }
	}
}
