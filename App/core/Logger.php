<?php

namespace App\core;

class Logger
{
    public static function error(string $message): void
    {
        $logFile = __DIR__ . '/../../logs/error.log';
        $date = date('Y-m-d H:i:s');
        $formattedMessage = "[{$date}] ERROR: {$message}\n";

        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}
