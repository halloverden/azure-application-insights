<?php


namespace HalloVerden\ApplicationInsights\Tests;

use HalloVerden\ApplicationInsights\Channel\Contracts\Application;
use HalloVerden\ApplicationInsights\Channel\Contracts\Cloud;
use HalloVerden\ApplicationInsights\Channel\Contracts\Device;
use HalloVerden\ApplicationInsights\Channel\Contracts\Location;
use HalloVerden\ApplicationInsights\Channel\Contracts\Session;
use HalloVerden\ApplicationInsights\Channel\Contracts\User;
use HalloVerden\ApplicationInsights\CurrentSession;
use HalloVerden\ApplicationInsights\CurrentUser;
use HalloVerden\ApplicationInsights\TelemetryContext;
use PHPUnit\Framework\TestCase;

/**
 * Contains tests for TelemetryContext class
 */
class TelemetryContextTest extends TestCase {
  public function testInstrumentationKey() {
    $telemetryContext = new TelemetryContext();
    $instrumentationKey = TestUtils::getTestInstrumentationKey();
    $telemetryContext->setInstrumentationKey($instrumentationKey);
    $this->assertEquals($instrumentationKey, $telemetryContext->getInstrumentationKey());
  }

  public function testDeviceContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getDeviceContext();
    $this->assertEquals($context, new Device());
    $telemetryContext->setDeviceContext(TestUtils::getSampleDeviceContext());
    $context = $telemetryContext->getDeviceContext();
    $this->assertEquals($context, TestUtils::getSampleDeviceContext());
  }

  public function testCloudContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getCloudContext();
    $this->assertEquals($context, new Cloud());
    $telemetryContext->setCloudContext(TestUtils::getSampleCloudContext());
    $context = $telemetryContext->getCloudContext();
    $this->assertEquals($context, TestUtils::getSampleCloudContext());
  }

  public function testApplicationContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getApplicationContext();
    $this->assertEquals($context, new Application());
    $telemetryContext->setApplicationContext(TestUtils::getSampleApplicationContext());
    $context = $telemetryContext->getApplicationContext();
    $this->assertEquals($context, TestUtils::getSampleApplicationContext());
  }

  public function testUserContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getUserContext();

    $defaultUserContext = new User();
    $currentUser = new CurrentUser();
    $defaultUserContext->setId($currentUser->id);
    $this->assertEquals($context, $defaultUserContext);

    $telemetryContext->setUserContext(TestUtils::getSampleUserContext());
    $context = $telemetryContext->getUserContext();
    $this->assertEquals($context, TestUtils::getSampleUserContext());
  }

  public function testLocationContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getLocationContext();
    $this->assertEquals($context, new Location());
    $telemetryContext->setLocationContext(TestUtils::getSampleLocationContext());
    $context = $telemetryContext->getLocationContext();
    $this->assertEquals($context, TestUtils::getSampleLocationContext());
  }

  public function testOperationContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getOperationContext();
    $this->assertNotEmpty($context->getId());
    $telemetryContext->setOperationContext(TestUtils::getSampleOperationContext());
    $context = $telemetryContext->getOperationContext();
    $this->assertEquals($context, TestUtils::getSampleOperationContext());
  }

  public function testSessionContext() {
    $telemetryContext = new TelemetryContext();
    $context = $telemetryContext->getSessionContext();

    $defaultSessionContext = new Session();
    $currentSession = new CurrentSession();
    $defaultSessionContext->setId($currentSession->id);
    $this->assertEquals($context, $defaultSessionContext);

    $telemetryContext->setSessionContext(TestUtils::getSampleSessionContext());
    $context = $telemetryContext->getSessionContext();
    $this->assertEquals($context, TestUtils::getSampleSessionContext());
  }

  public function testProperties() {
    $telemetryContext = new TelemetryContext();
    $properties = $telemetryContext->getProperties();
    $this->assertEquals($properties, []);
    $telemetryContext->setProperties(TestUtils::getSampleCustomProperties());
    $properties = $telemetryContext->getProperties();
    $this->assertEquals($properties, TestUtils::getSampleCustomProperties());
  }
}
