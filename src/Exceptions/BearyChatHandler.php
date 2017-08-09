<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 17/8/9
 * Time: 上午10:30
 */
namespace Loopeer\QuickCms\Exceptions;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class BearyChatHandler extends AbstractProcessingHandler {

    public function __construct($level = Logger::NOTICE, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $postData = [
            'text' => $record['datetime']->format('Y-m-d H:i:s') . '-' . env('DB_DATABASE') . '-' . $record["level"] . '-' . $record["level_name"],
            'channel' => 'Server-Log-Report',
            'attachments' => [
                [
                    'title' => current(preg_split("/([.\n\r]+)/i", $record['message'])),
                    'text' => $record['message'],
                    'color' => '#ffa500'
                ]
            ]
        ];
        $postString = json_encode($postData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('quickApi.log_report_hook'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        curl_exec($ch);
        curl_close($ch);
    }
}