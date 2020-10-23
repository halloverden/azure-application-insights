# Application Insights Sdk


This project was forked from and builds upon the official php sdk from windows, which is no longer mantained.

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

Once installed, you can send telemetry to Application Insights. Here are a few samples.

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

// Start tracking
$telemetryClient->trackEvent('name of your event');
$telemetryClient->flush();
```

### Setup Operation context

For correct Application Insights reporting you need to setup Operation Context,
reference to Request

```php
$telemetryClient->getContext()->getOperationContext()->setId('XX');
$telemetryClient->getContext()->getOperationContext()->setName('GET Index');
```

### Sending a simple event telemetry item with event name

```php
$telemetryClient->trackEvent('name of your event');
$telemetryClient->flush();
```

### Sending an event telemetry item with custom properties and measurements

```php
$telemetryClient->trackEvent('name of your event', ['MyCustomProperty' => 42, 'MyCustomProperty2' => 'test'], ['duration', 42]);
$telemetryClient->flush();
```

**Sending more than one telemetry item before sending to the service is also
supported; the API will batch everything until you call flush()**

```php
$telemetryClient->trackEvent('name of your event');
$telemetryClient->trackEvent('name of your second event');
$telemetryClient->flush();
```

### Sending a simple page view telemetry item with page name and url

```php
$telemetryClient->trackPageView('myPageView', 'http://www.foo.com');
$telemetryClient->flush();
```

### Sending a page view telemetry item with duration, custom properties and measurements

```php
$telemetryClient->trackPageView('myPageView', 'http://www.foo.com', 256, ['InlineProperty' => 'test_value'], ['duration' => 42.0]);
$telemetryClient->flush();
```

### Sending a simple metric telemetry item with metric name and value

```php
$telemetryClient->trackMetric('myMetric', 42.0);
$telemetryClient->flush();
```

### Sending a metric telemetry item with point type, count, min, max, standard deviation and measurements

```php
$telemetryClient->trackMetric('myMetric', 42.0, \ApplicationInsights\Channel\Contracts\Data_Point_Type::Aggregation, 5, 0, 1, 0.2, ['InlineProperty' => 'test_value']);
$telemetryClient->flush();
```

### Sending a simple message telemetry item with message

```php
$telemetryClient->trackMessage('myMessage', \ApplicationInsights\Channel\Contracts\Message_Severity_Level::INFORMATION, ['InlineProperty' => 'test_value']);
$telemetryClient->flush();
```

**Sending a simple request telemetry item with request name, url and start
time**

```php
$telemetryClient->trackRequest('myRequest', 'http://foo.bar', time());
$telemetryClient->flush();
```

### Sending a request telemetry item with duration, http status code, whether or not the request succeeded, custom properties and measurements

```php
$telemetryClient->trackRequest('myRequest', 'http://foo.bar', time(), 3754, 200, true, ['InlineProperty' => 'test_value'], ['duration_inner' => 42.0]);
$telemetryClient->flush();
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

### Sending a successful SQL dependency telemetry item

```php
$telemetryClient->trackDependency('Query table', "SQL", 'SELECT * FROM table;', time(), 122, true);
$telemetryClient->flush();
```

### Sending a failed HTTP dependency telemetry item

```php
$telemetryClient->trackDependency('method', "HTTP", "http://example.com/api/method", time(), 324, false, 503);
$telemetryClient->flush();
```

### Sending any other kind dependency telemetry item

```php
$telemetryClient->trackDependency('Name of operation', "service", 'Arguments', time(), 23, true);
$telemetryClient->flush();
```

### Changing the operation id (which links actions together)

```php
$telemetryClient->trackMetric('interestingMetric', 10);
$telemetryClient->getContext()->getOperationContext()->setId(\ApplicationInsights\Channel\Contracts\Utils::returnGuid())
$telemetryClient->trackMetric('differentOperationMetric', 11);
$telemetryClient->flush();
```
