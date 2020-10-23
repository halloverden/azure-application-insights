<?php


namespace HalloVerden\ApplicationInsights\Channel;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use HalloVerden\ApplicationInsights\Channel\Contracts\DataInterface;
use HalloVerden\ApplicationInsights\TelemetryContext;
use Psr\Http\Message\ResponseInterface;

/**
 * Responsible for managing a queue of telemetry items to send and sending them.
 */
class TelemetryChannel {
  /**
   * The endpoint URL to send data to.
   * @var string
   */
  protected $_endpointUrl;

  /**
   * The queue of already serialized JSON objects to send.
   * @var array
   */
  protected $_queue;

  /**
   * Client that is used to call out to the endpoint.
   * @var Client
   */
  protected $_client;

  /**
   * If true, then the data will be gzipped before sending to application insights.
   * @var bool
   */
  protected $_sendGzipped;

  /**
   * Initializes a new TelemetryChannel.
   * @param string $endpointUrl Optional. Allows caller to override which endpoint to send data to.
   * @param Client|null $client - guzzle client if it exists
   */
  function __construct($endpointUrl = 'https://dc.services.visualstudio.com/v2/track', $client = null) {
    $this->_endpointUrl = $endpointUrl;
    $this->_queue = array();
    $this->_client = $client;
    $this->_sendGzipped = false;

    if ($client === null && class_exists('\GuzzleHttp\Client') == true) {
      // Standard case if properly pulled in composer dependencies
      $this->_client = new Client();
    }
  }

  /**
   * Returns the current URL this TelemetrySender will send to.
   * @return string
   */
  public function getEndpointUrl()
  {
    return $this->_endpointUrl;
  }

  /**
   * Sets the current URL this TelemetrySender will send to.
   * @param string $endpointUrl
   */
  public function setEndpointUrl(string $endpointUrl) {
    $this->_endpointUrl = $endpointUrl;
  }

  /**
   * Returns the current queue.
   * @return array
   */
  public function getQueue() {
    return $this->_queue;
  }

  /**
   * Sets the current queue.
   * @param array $queue
   */
  public function setQueue(array $queue) {
    $this->_queue = $queue;
  }

  /**
   * @return Client
   */
  public function getClient() {
    return $this->_client;
  }

  /**
   * @param Client $client
   */
  public function SetClient(Client $client) {
    $this->_client = $client;
  }

  /**
   * Summary of getSerializedQueue
   * @return string JSON representation of queue.
   */
  public function getSerializedQueue() {
    $queueToEncode = array();
    foreach ($this->_queue as $dataItem) {
      array_push($queueToEncode, Contracts\Utils::getUnderlyingData($dataItem->jsonSerialize()));
    }

    return json_encode($queueToEncode);
  }

  /**
   * @return bool
   */
  public function getSendGzipped() {
    return $this->_sendGzipped;
  }

  /**
   * @param bool $sendGzipped
   */
  public function setSendGzipped($sendGzipped) {
    $this->_sendGzipped = $sendGzipped;
  }


  /**
   * Writes the item into the sending queue for subsequent processing.
   * @param DataInterface $data The telemetry item to send.
   * @param TelemetryContext $telemetryContext The context to use.
   * @param null $startTime
   */
  public function addToQueue( DataInterface $data, TelemetryContext $telemetryContext, $startTime = null) {
    // If no data or context provided, we just return to not cause upstream issues as a result of telemetry
    if ($data == NULL || $telemetryContext == NULL) {
      return;
    }

    $envelope = new Contracts\Envelope();

    // Main envelope properties
    $envelope->setName($data->getEnvelopeTypeName());
    if ($startTime == NULL) {
      $startTime = $data->getTime();
    }

    $envelope->setTime(Contracts\Utils::returnISOStringForTime($startTime));

    // The instrumentation key to use
    $envelope->setInstrumentationKey($telemetryContext->getInstrumentationKey());

    // Copy all context into the Tags array
    $envelope->setTags(array_merge($telemetryContext->getApplicationContext()->jsonSerialize(),
      $telemetryContext->getDeviceContext()->jsonSerialize(),
      $telemetryContext->getCloudContext()->jsonSerialize(),
      $telemetryContext->getLocationContext()->jsonSerialize(),
      $telemetryContext->getOperationContext()->jsonSerialize(),
      $telemetryContext->getSessionContext()->jsonSerialize(),
      $telemetryContext->getUserContext()->jsonSerialize(),
      $telemetryContext->getInternalContext()->jsonSerialize()));

    // Merge properties from global context to local context
    $contextProperties = $telemetryContext->getProperties();
    if (method_exists($data, 'getProperties') == true && $contextProperties != NULL && count($contextProperties) > 0) {
      $dataProperties = $data->getProperties();
      if ($dataProperties == NULL) {
        $dataProperties = array();
      }
      foreach ($contextProperties as $key => $value) {
        if (array_key_exists($key, $dataProperties) == false) {
          $dataProperties[$key] = $value;
        }
      }
      $data->setProperties($dataProperties);
    }

    // Embed the main data object
    $envelope->setData(new Contracts\Data());
    $envelope->getData()->setBaseType($data->getDataTypeName());
    $envelope->getData()->setBaseData($data);

    array_push($this->_queue, $envelope);
  }

  /**
   * Summary of send
   * @param array $options
   * @param bool  $sendAsync
   * @return PromiseInterface|ResponseInterface|null|void
   */
  public function send($options = array(), $sendAsync = false) {
    $response = null;
    $useGuzzle = $this->_client !== null;
    if (count($this->_queue) == 0) {
      return;
    }

    $serializedTelemetryItem = $this->getSerializedQueue();

    if($this->_sendGzipped && $useGuzzle) {
      $headersArray = array(
        'Content-Encoding' => 'gzip',
      );
      $body = gzencode($serializedTelemetryItem);
    } else {
      $headersArray = array(
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
      );
      $body = utf8_encode($serializedTelemetryItem);
    }

    if ($useGuzzle) {
      $options = array_merge(
        array(
          'headers' => $headersArray,
          'body' => $body,
          'verify' => false
        ),
        $options
      );

      if ($sendAsync && method_exists($this->_client, 'sendAsync')) {
        $response = $this->_client->postAsync($this->_endpointUrl, $options);
      } else {
        $response = $this->_client->post($this->_endpointUrl, $options);
      }
    }
    return $response;
  }
}
