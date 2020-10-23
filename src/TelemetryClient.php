<?php


namespace HalloVerden\ApplicationInsights;


use GuzzleHttp\Promise\PromiseInterface;
use HalloVerden\ApplicationInsights\Channel\Contracts\ExceptionData;
use HalloVerden\ApplicationInsights\Channel\Contracts\ExceptionDetails;
use HalloVerden\ApplicationInsights\Channel\Contracts\StackFrame;
use HalloVerden\ApplicationInsights\Channel\TelemetryChannel;

/**
 * Main object used for interacting with the Application Insights service.
 */
class TelemetryClient {
  /**
   * The telemetry context this client will use
   * @var TelemetryContext
   */
  private $_context;

  /**
   * The telemetry channel this client will use
   * @var TelemetryChannel
   */
  private $_channel;

  /**
   * Initializes a new Telemetry_Client.
   * @param TelemetryContext|null $context
   * @param TelemetryChannel|null $channel
   */
  public function __construct(TelemetryContext $context = NULL, TelemetryChannel $channel = NULL) {
    $this->_context = ($context == NULL) ?  new TelemetryContext() : $context;
    $this->_channel = ($channel == NULL) ?  new Channel\TelemetryChannel() : $channel;
  }

  /**
   * Returns the TelemetryContext this Telemetry_Client is using.
   * @return TelemetryContext
   */
  public function getContext() {
    return $this->_context;
  }

  /**
   * Returns the TelemetryChannel this Telemetry_Client is using.
   * @return TelemetryChannel
   */
  public function getChannel() {
    return $this->_channel;
  }

  /**
   * Sends an Exception_Data to the Application Insights service.
   * @param \Throwable $ex The exception/throwable to send
   * @param array|null $properties An array of name to value pairs. Use the name as the index and any string as the value.
   * @param array|null $measurements An array of name to double pairs. Use the name as the index and any double as the value.
   */
  public function trackException(\Throwable $ex, $properties = NULL, $measurements = NULL) {
    $details = new ExceptionDetails();
    $details->setId(1);
    $details->setOuterId(0);
    $details->setTypeName(get_class($ex));
    $details->setMessage($ex->getMessage().' in '.$ex->getFile().' on line '.$ex->getLine());
    $details->setHasFullStack(true);

    $stackFrames = array();

    // First stack frame is in the root exception
    $frameCounter = 0;
    foreach ($ex->getTrace() as $currentFrame) {
      $stackFrame = new StackFrame();
      if (array_key_exists('class', $currentFrame) == true) {
        $stackFrame->setAssembly($currentFrame['class']);
      }
      if (array_key_exists('file', $currentFrame) == true) {
        $stackFrame->setFileName($currentFrame['file']);
      }
      if (array_key_exists('line', $currentFrame) == true) {
        $stackFrame->setLine($currentFrame['line']);
      }
      if (array_key_exists('function', $currentFrame) == true) {
        $stackFrame->setMethod($currentFrame['function']);
      }

      // Make it a string to force serialization of zero
      $stackFrame->setLevel(''.$frameCounter);

      array_unshift($stackFrames, $stackFrame);
      $frameCounter++;
    }

    $details->setParsedStack($stackFrames);

    $data = new ExceptionData();
    $data->setExceptions(array($details));

    if ($properties != NULL) {
      $data->setProperties($properties);
    }

    if ($measurements != NULL) {
      $data->setMeasurements($measurements);
    }

    $this->_channel->addToQueue($data, $this->_context);
  }

  /**
   * Flushes the underlying TelemetryChannel queue.
   * @param array $options - Guzzle client option overrides
   * @param bool  $sendAsync - Send the request asynchronously
   * @return PromiseInterface
   */
  public function flush($options = array(), $sendAsync = false) {
    $response = $this->_channel->send($options, $sendAsync);
    $this->_channel->setQueue([]);
    return $response;
  }
}
