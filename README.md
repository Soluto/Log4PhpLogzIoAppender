# Log4Php LogzIo Appender

[Logz.Io](https://logz.io/) appender for [log4php](https://logging.apache.org/log4php/).
The appender uses tcp socket to send the event to logz.io

## How to configure the appender

example of log4php configuration file using the appender:
```
    <configuration xmlns="http://logging.apache.org/log4php/">
        <appender name="logzIoAppender" class="\Soluto\LoggerAppenders\AppenderLogzIo">
            <param name="host" value="listener.logz.io" />
            <param name="port" value="5050" />
            <param name="type" value="MyLogType" />
            <param name="logzAccountToken" value="MyToken" />
        </appender>
        <root>
            <level value="WARN" />
            <appender_ref ref="logzIoAppender" />
        </root>
    </configuration>
```

you can get more information about the type parameter in [Logz.Io](https://support.logz.io/hc/en-us/sections/202356425-Supported-Log-Types)

## How to consume the appender using Composer
To import the package using [Composer](https://getcomposer.org/) you need to define the repository in your composer.json file.

example composer.json file:
```
    {
        "config": {
            "vendor-dir": "my-theme/composer-vendor"
        },
        "require": {
            "apache/log4php": "^2.3",
            "soluto/log4phpLogzIoAppender":  "^0.1.0"
        },
        "repositories": [
            {
                "type": "vcs",
                "url":  "https://github.com/Soluto/Log4PhpLogzIoAppender.git"
            }
        ]
    }
```

## How to send extra data
Log4Php doesn't give us the option to send extra data as part of the entry so in order to overcome this issue we can use the ExtendedLoggerLoggingEvent class that extends LoggerLoggingEvent.


example of using the ExtendedLoggerLoggingEvent:
```
    $logger = Logger::getLogger('soluto-logger');
    $logEvent = new ExtendedLoggerLoggingEvent("Logger", $logger, LoggerLevel::getLevelInfo(), "MyMessage", null, null, array("Location"=>"TelAviv"));
    $logger->logEvent($logEvent);
```

This code will add entry to LogzIo with the message "MyMessage" and a field called "Location" with the value of "TelAviv"