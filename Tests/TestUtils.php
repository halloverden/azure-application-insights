<?php
namespace HalloVerden\ApplicationInsights\Tests;

use HalloVerden\ApplicationInsights\Channel\Contracts\Application;
use HalloVerden\ApplicationInsights\Channel\Contracts\Cloud;
use HalloVerden\ApplicationInsights\Channel\Contracts\Device;
use HalloVerden\ApplicationInsights\Channel\Contracts\Location;
use HalloVerden\ApplicationInsights\Channel\Contracts\Operation;
use HalloVerden\ApplicationInsights\Channel\Contracts\Session;
use HalloVerden\ApplicationInsights\Channel\Contracts\User;
use HalloVerden\ApplicationInsights\Channel\Contracts\Utils;

/**
 * Contains utilities for tests
 */
class TestUtils {
  /**
   * A single place for managing the instrumentation key used in the tests.
   * @return string (Guid)
   */
  public static function getTestInstrumentationKey() {
    return '11111111-1111-1111-1111-111111111111';
  }

  /**
   * Controls whether the tests should send data to the server.
   * @return bool
   */
  public static function sendDataToServer() {
    return false;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\Device
   * @return Device
   */
  public static function getSampleDeviceContext() {
    $context = new Device();
    $context->setId('my_device_id');
    $context->setLocale('EN');
    $context->setModel('my_device_model');
    $context->setOemName('my_device_oem_name');
    $context->setOsVersion('Windows 8');
    $context->setType('PC');
    return $context;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\Cloud
   * @return Cloud
   */
  public static function getSampleCloudContext() {
    $context = new Cloud();
    $context->setRole('my_role_name');
    $context->setRoleInstance('my_role_instance');
    return $context;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\Application
   * @return Application
   */
  public static function getSampleApplicationContext() {
    $context = new Application();
    $context->setVer('1.0.0.0');
    return $context;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\User
   * @return User
   */
  public static function getSampleUserContext() {
    $context = new User();
    $context->setId('my_user_id');
    $context->setAccountId('my_account_id');
    return $context;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\Location
   * @return Location
   */
  public static function getSampleLocationContext() {
    $context = new Location();
    $context->setIp("127.0.0.0");
    return $context;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\Operation
   * @return Operation
   */
  public static function getSampleOperationContext() {
    $context = new Operation();
    $context->setId('my_operation_id');
    $context->setName('my_operation_name');
    $context->setParentId('my_operation_parent_id');
    return $context;
  }

  /**
   * Gets a sample ApplicationInsights\Channel\Contracts\Session
   * @return Session
   */
  public static function getSampleSessionContext() {
    $context = new Session();
    $context->setId('my_session_id');
    $context->setIsFirst(false);
    return $context;
  }

  /**
   * Gets a sample custom property array.
   * @return array
   */
  public static function getSampleCustomProperties() {
    return ['MyCustomProperty' => 42, 'MyCustomProperty2' => 'test'];
  }

  /**
   * Used for testing exception related code
   */
  public static function throwNestedException($depth = 0) {
    if ($depth <= 0) {
      throw new \Exception("testException");
    }

    TestUtils::throwNestedException($depth - 1);
  }

  /**
   * Used for testing error related code
   */
  public static function throwError() {
    eval('sdklafjha asdlkja asdaksd al');
  }

  /**
   * Creates user cookie for testing.
   */
  public static function setUserCookie($userId = NULL) {
    $_COOKIE['ai_user'] = $userId == NULL ? Utils::returnGuid() : $userId;
  }

  /**
   * Clears the user cookie.
   */
  public static function clearUserCookie() {
    $_COOKIE['ai_user'] = NULL;
  }

  /**
   * Creates session cookie for testing.
   */
  public static function setSessionCookie($sessionId = NULL, $sessionCreatedDate = NULL, $lastRenewedDate = NULL) {
    $sessionId =  $sessionId == NULL ? Utils::returnGuid() : $sessionId;

    $sessionCreatedDate == NULL ? $sessionCreatedDate = time() : $sessionCreatedDate;
    $lastRenewedDate == NULL ? $lastRenewedDate = time() : $lastRenewedDate;

    $_COOKIE['ai_session'] = $sessionId.'|'. Utils::returnISOStringForTime($sessionCreatedDate).'|'. Utils::returnISOStringForTime($lastRenewedDate);
  }

  /**
   * Clears the user cookie.
   */
  public static function clearSessionCookie() {
    $_COOKIE['ai_session'] = NULL;
  }
}
