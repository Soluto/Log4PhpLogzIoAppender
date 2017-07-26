<?php

namespace Soluto\LoggerAppenders;

include_once ('LogzIoLogEventInfo.php');

class AppenderLogzIo extends \LoggerAppender {

    protected $host = "";
    protected $port = "";
    protected $type = "";
    protected $logzAccountToken = "";

    public function append(\LoggerLoggingEvent $event) {
        try {
            $message = $this->filterMessage($event->getMessage());
            $logTimestamp = date("Y-m-d\TH:i:s");
            $throwable  = $event->getThrowableInformation() ? $this->parseThrowable($event->getThrowableInformation()->getThrowable()) : null;
            $level = strtolower($event->getLevel()->toString());

            $messageEventToSend = new LogzIoLogEventInfo($this->logzAccountToken, $message, $logTimestamp,$level,$throwable, $this->type);
            $this->writeEvent($messageEventToSend);
        }
        catch(\Exception $e){
            error_log("Error occured while tring to append log event to LogzIo appender");
        }
    }

    private function writeEvent($messageToSend){
        $address = $this->host;
        $port = $this->port;
        $fp = stream_socket_client("tcp://".$address.":".$port, $errno, $errstr, 40);
                
        if (!$fp) throw new \Exception("error occured while trying to send error to logz.io, the error is $errstr ($errno)");
        
        try {
            fwrite($fp, json_encode($messageToSend)."\n");
            error_log(json_encode($messageToSend));
        }
        finally{
            fclose($fp);
        }
    }

    /** The throwable's getStringRepresentation does not look good on Kibana **/
    private function parseThrowable($throwable){
        if (!$throwable) return array();

        return array(
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTrace(),
            'innerException' => $this->parseThrowable($throwable->getPrevious())
        );
    }

    /** Remove controll chars and \n **/
    private function filterMessage($message)
    {
        $charsToRemove = array(chr(127));
        for ($x = 0; $x <= 31; $x++) {
            array_push($charsToRemove, chr($x));
        }

        return str_replace($charsToRemove,'', str_replace(PHP_EOL, '', $message));
    }
    
    public function setHost($host) {
        $this->host = $host;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType($type) {
        if (empty($this->type)) return "json";
        return $this->type;
    }

    public function setLogzAccountToken($token) {
        $this->logzAccountToken = $token;
    } 
}
?>