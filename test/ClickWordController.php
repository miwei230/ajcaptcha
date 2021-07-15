<?php
declare(strict_types=1);

use Fastknife\Utils\RandomUtils;
use Fastknife\Service\ClickWordCaptchaService;

class ClickWordController
{
    public function get()
    {
        $service = new ClickWordCaptchaService();
        $config = require '../src/config.php';
        $data = $service->setConfig($config)->get();
        echo json_encode([
            'error' => false,
            'repCode' => '0000',
            'repData' => $data,
            'repMsg' => null,
            'success' => true,
        ]);
    }

    public function check()
    {
        $service = new ClickWordCaptchaService();
        $config = require '../src/config.php';
        $data = $_REQUEST;
        $msg = null;
        $error = false;
        $repCode = '0000';
        try {
            $service->setConfig($config)->check($data['token'], $data['pointJson']);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $error = true;
            $repCode = '6111';
        }
        echo json_encode([
            'error' => $error,
            'repCode' => $repCode,
            'repData' => null,
            'repMsg' => $msg,
            'success' => ! $error,
        ]);
    }
}








