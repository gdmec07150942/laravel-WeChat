<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 日志记录
     */
    public function logs($name, $data = null)
    {
        $log = new Logger($name);
        $logDir = storage_path('logs/operateData');
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $log->pushHandler(new StreamHandler($logDir . '/' . date('Y-m-d') . '.log', Logger::DEBUG));
        $log->addDebug($data);
    }
}
