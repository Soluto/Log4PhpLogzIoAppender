<?php

namespace Soluto\LoggerAppenders;

class LogzIoLogEventInfo {
    public $token;
    public $message;
    public $LogTimestamp;
    public $type;
    public $level;
    public $exception;
    public $details;

    public function __construct($token, $message, $logTimestamp, $level, $throwable, $type)
    {
        $this->token = $token;
        $this->message = $message;
        $this->LogTimestamp = $logTimestamp;
        $this->level = $level;
        $this->exception = (object)$throwable;
        $this->type = $type;
    }
}
?>