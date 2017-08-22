<?php

namespace Soluto\LoggerAppenders;

class ExtendedLoggerLoggingEvent extends \LoggerLoggingEvent {
    public $extraData;
    
    public function __construct($fqcn, $logger, \LoggerLevel $level, $message, $timeStamp = null, $throwable = null, $extraData) {
        $this->extraData = $this->encodeExtraData($extraData);
        parent::__construct($fqcn, $logger, $level, $message, $timeStamp, $throwable);
    }

    private function encodeExtraData($extraData){
        if (!is_array($extraData)) return null;
        foreach ($extraData as $key => $value){
            if (!is_string($value)){
                if (method_exists($value , '__toString')) $extraData[$key] = $value->__toString();
            }
        }

        return $extraData;
    }
}

?>