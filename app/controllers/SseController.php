<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Entities\Git;

class SseController extends BaseController
{
    /** @inheritdoc	 */
    public function indexAction($param = null)
    {

        $time = date('r');
        echo "data: The server time is: {$time}\n\n";
        flush();
    }
}
