<?php

/*
 * This file is part of the microsoft/application-insights package.
 *
 * (c) Microsoft Corporation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HalloVerden\ApplicationInsights;

use HalloVerden\ApplicationInsights\Channel\Contracts\Application;
use HalloVerden\ApplicationInsights\Channel\Contracts\Cloud;
use HalloVerden\ApplicationInsights\Channel\Contracts\Device;
use HalloVerden\ApplicationInsights\Channel\Contracts\Internal;
use HalloVerden\ApplicationInsights\Channel\Contracts\Location;
use HalloVerden\ApplicationInsights\Channel\Contracts\Operation;
use HalloVerden\ApplicationInsights\Channel\Contracts\Session;
use HalloVerden\ApplicationInsights\Channel\Contracts\User;
use HalloVerden\ApplicationInsights\Channel\Contracts\Utils;

/**
 * Responsible for managing the context to send along with all telemetry items.
 */
class TelemetryContext
{
  /**
   * The instrumentation key
   * @var string (Guid)
   */
  private $_instrumentationKey;

  /**
   * The device context
   * @var Device
   */
  private $_deviceContext;

  /**
   * The cloud context
   * @var Cloud
   */
  private $_cloudContext;

  /**
   * The application context
   * @var Application
   */
  private $_applicationContext;

  /**
   * The user context
   * @var User
   */
  private $_userContext;

  /**
   * The location context
   * @var Location
   */
  private $_locationContext;

  /**
   * The operation context
   * @var Operation
   */
  private $_operationContext;

  /**
   * The session context
   * @var Session
   */
  private $_sessionContext;

  /**
   * The internal context
   * @var Internal
   */
  private $_internalContext;

  /**
   * Additional custom properties array.
   * @var array Additional properties (name/value pairs) to append as custom properties to all telemetry.
   */
  private $_properties;

  /**
   * Initializes a new TelemetryContext.
   */
  function __construct() {
    $this->_deviceContext = new Device();
    $this->_cloudContext = new Cloud();
    $this->_applicationContext = new Application();
    $this->_userContext = new User();
    $this->_locationContext = new Location();
    $this->_operationContext = new Operation();
    $this->_sessionContext = new Session();
    $this->_internalContext = new Internal();
    $this->_properties = array();

    // Initialize user id
    $currentUser = new CurrentUser();
    $this->_userContext->setId($currentUser->id);

    // Initialize session id
    $currentSession = new CurrentSession();
    $this->_sessionContext->setId($currentSession->id);

    // Initialize the operation id
    $operationId = Utils::returnGuid();
    $this->_operationContext->setId($operationId);

    // Initialize client ip
    if (array_key_exists('REMOTE_ADDR', $_SERVER) && sizeof(explode('.', $_SERVER['REMOTE_ADDR'])) >= 4)
    {
      $this->_locationContext->setIp($_SERVER['REMOTE_ADDR']);
    }

    $this->_internalContext->setSdkVersion('php:0.4.6');
  }

  /**
   * The instrumentation key for your Application Insights application.
   * @return string (Guid)
   */
  public function getInstrumentationKey() {
    return $this->_instrumentationKey;
  }

  /**
   * Sets the instrumentation key on the context. This is the key for you application in Application Insights.
   * @param string $instrumentationKey (Guid)
   */
  public function setInstrumentationKey(string $instrumentationKey) {
    $this->_instrumentationKey = $instrumentationKey;
  }

  /**
   * The device context object. Allows you to set properties that will be attached to all telemetry about the device.
   * @return Device
   */
  public function getDeviceContext() {
    return $this->_deviceContext;
  }

  /**
   * Sets device context object. Allows you to set properties that will be attached to all telemetry about the device.
   * @param Device $deviceContext
   */
  public function setDeviceContext(Device $deviceContext) {
    $this->_deviceContext = $deviceContext;
  }

  /**
   * The cloud context object. Allows you to set properties that will be attached to all telemetry about the cloud placement of an application.
   * @return Cloud
   */
  public function getCloudContext() {
    return $this->_cloudContext;
  }

  /**
   * Sets cloud context object. Allows you to set properties that will be attached to all telemetry about the cloud placement of an application.
   * @param Cloud $cloudContext
   */
  public function setCloudContext(Cloud $cloudContext) {
    $this->_cloudContext = $cloudContext;
  }

  /**
   * The application context object. Allows you to set properties that will be attached to all telemetry about the application.
   * @return Application
   */
  public function getApplicationContext() {
    return $this->_applicationContext;
  }

  /**
   * Sets application context object. Allows you to set properties that will be attached to all telemetry about the application.
   * @param Application $applicationContext
   */
  public function setApplicationContext(Application $applicationContext) {
    $this->_applicationContext = $applicationContext;
  }

  /**
   * The user context object. Allows you to set properties that will be attached to all telemetry about the user.
   * @return User
   */
  public function getUserContext() {
    return $this->_userContext;
  }

  /**
   * Set user context object. Allows you to set properties that will be attached to all telemetry about the user.
   * @param User $userContext
   */
  public function setUserContext(User $userContext) {
    $this->_userContext = $userContext;
  }

  /**
   * The location context object. Allows you to set properties that will be attached to all telemetry about the location.
   * @return Location
   */
  public function getLocationContext() {
    return $this->_locationContext;
  }

  /**
   * Set location context object. Allows you to set properties that will be attached to all telemetry about the location.
   * @param Location $locationContext
   */
  public function setLocationContext(Location $locationContext) {
    $this->_locationContext = $locationContext;
  }

  /**
   * The operation context object. Allows you to set properties that will be attached to all telemetry about the operation.
   * @return Operation
   */
  public function getOperationContext() {
    return $this->_operationContext;
  }

  /**
   * Set operation context object. Allows you to set properties that will be attached to all telemetry about the operation.
   * @param Operation $operationContext
   */
  public function setOperationContext(Operation $operationContext) {
    $this->_operationContext = $operationContext;
  }

  /**
   * The session context object. Allows you to set properties that will be attached to all telemetry about the session.
   * @return Session
   */
  public function getSessionContext() {
    return $this->_sessionContext;
  }

  /**
   * Set session context object. Allows you to set properties that will be attached to all telemetry about the session.
   * @param Session $sessionContext
   */
  public function setSessionContext(Session $sessionContext) {
    $this->_sessionContext = $sessionContext;
  }

  /**
   * The session context object. Allows you to set internal details for troubleshooting.
   * @return Internal
   */
  public function getInternalContext() {
    return $this->_internalContext;
  }

  /**
   * Set session context object. Allows you to set internal details for troubleshooting.
   * @param Internal $internalContext
   */
  public function setInternalContext(Internal $internalContext) {
    $this->_internalContext = $internalContext;
  }

  /**
   * Get the additional custom properties array.
   * @return array Additional properties (name/value pairs) to append as custom properties to all telemetry.
   */
  public function getProperties() {
    return $this->_properties;
  }

  /**
   * Set the additional custom properties array.
   * @param array $properties Additional properties (name/value pairs) to append as custom properties to all telemetry.
   */
  public function setProperties(array $properties) {
    $this->_properties = $properties;
  }
}
