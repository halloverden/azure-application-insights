# Azure Application Insights


This project was forked from and builds upon the official php sdk from windows, which is no longer mantained.
As of now, it only supports exception logging.

## About:
- [Azure Application Insights](https://azure.microsoft.com/services/application-insights/)
- [Official Github Repository From Microsoft](https://github.com/microsoft/ApplicationInsights-PHP)
- [Supported SDKs](https://docs.microsoft.com/en-us/azure/azure-monitor/app/platforms#unsupported-community-sdks)
- [GitHub Announcements](https://github.com/microsoft/ApplicationInsights-Announcements/issues)

## Status

This SDK is NOT maintained or supported by Microsoft even though they've contributed to it in the past. Note that Azure Monitor only provides support when using supported SDKs.

## Requirements

- PHP version ^7.1

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.


Open a command console, enter your project directory and execute:

```console
$ composer require halloverden/application-insights-sdk
```

## Usage

Once installed, you can send exception telemetry to Application Insights. Here are a few samples.

>**Note**: before you can send data to you will need an instrumentation key. Please see the [Getting an Application Insights Instrumentation Key](https://github.com/Microsoft/AppInsights-Home/wiki#getting-an-application-insights-instrumentation-key) section for more information.

### Initializing the client and setting the instrumentation key and other optional configurations

```php
$telemetryClient = new \ApplicationInsights\Telemetry_Client();
$context = $telemetryClient->getContext();

// Necessary
$context->setInstrumentationKey('YOUR INSTRUMENTATION KEY');

// Optional
$context->getSessionContext()->setId(session_id());
$context->getUserContext()->setId('YOUR USER ID');
$context->getApplicationContext()->setVer('YOUR VERSION');
$context->getLocationContext()->setIp('YOUR IP');

// Start tracking throwables
$telemetryClient->trackException($throwable);
$telemetryClient->flush();
```

### Setup Operation context

For correct Application Insights reporting you need to setup Operation Context,
reference to Request

```php
$telemetryClient->getContext()->getOperationContext()->setId('XX');
$telemetryClient->getContext()->getOperationContext()->setName('GET Index');
```

### Sending an exception telemetry, with custom properties and metrics

```php
try
{
    // Do something to throw an exception
}
catch (\Exception $ex)
{
    $telemetryClient->trackException($ex, ['InlineProperty' => 'test_value'], ['duration_inner' => 42.0]);
    $telemetryClient->flush();
}
```

### Set the Client to gzip the data before sending

```php
$telemetryClient->getChannel()->setSendGzipped(true);
```

### Registering an exception handler

```php
class Handle_Exceptions
{
    private $_telemetryClient;

    public function __construct()
    {
        $this->_telemetryClient = new \ApplicationInsights\Telemetry_Client();
        $this->_telemetryClient->getContext()->setInstrumentationKey('YOUR INSTRUMENTATION KEY');

        set_exception_handler(array($this, 'exceptionHandler'));
    }

    function exceptionHandler(\Exception $exception)
    {
        if ($exception != NULL)
        {
            $this->_telemetryClient->trackException($exception);
            $this->_telemetryClient->flush();
        }
    }
}
```
