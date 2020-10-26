<?php

/*
 * This file is part of the microsoft/application-insights package.
 *
 * (c) Microsoft Corporation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HalloVerden\ApplicationInsights\Tests;

use GuzzleHttp\Client;
use HalloVerden\ApplicationInsights\Channel\TelemetryChannel;
use HalloVerden\ApplicationInsights\TelemetryClient;
use PHPUnit\Framework\TestCase;

/**
 * Contains tests for Telemetry_Client class
 */
class TelemetryClientTest extends TestCase {
  private $_telemetryClient;

  protected function setUp(): void {
    $this->_telemetryClient = new TelemetryClient();

    $context = $this->_telemetryClient->getContext();

    $context->setInstrumentationKey(TestUtils::getTestInstrumentationKey());
    $context->setApplicationContext(TestUtils::getSampleApplicationContext());
    $context->setDeviceContext(TestUtils::getSampleDeviceContext());
    $context->setCloudContext(TestUtils::getSampleCloudContext());
    $context->setLocationContext(TestUtils::getSampleLocationContext());
    $context->setOperationContext(TestUtils::getSampleOperationContext());
    $context->setSessionContext(TestUtils::getSampleSessionContext());
    $context->setUserContext(TestUtils::getSampleUserContext());

    $context->setProperties(TestUtils::getSampleCustomProperties());
  }

  /**
   * Tests a completely filled exception.
   *
   * Ensure this method doesn't move in the source, if it does, the test will fail and needs to have a line number adjusted.
   */
  public function testCompleteException() {
    try {
      TestUtils::throwNestedException(3);
    }
    catch (\Exception $ex) {
      $this->_telemetryClient->trackException($ex, ['InlineProperty' => 'test_value'], ['duration_inner' => 42.0]);
    }

    $queue = json_decode($this->_telemetryClient->getChannel()->getSerializedQueue(), true);
    $queue = $this->adjustDataInQueue($queue);

    $searchStrings = array("\\");
    $replaceStrings = array("\\\\");

    $expectedString = str_replace($searchStrings, $replaceStrings, '[{"ver":1,"name":"Microsoft.ApplicationInsights.Exception","time":"TIME_PLACEHOLDER","sampleRate":100,"iKey":"11111111-1111-1111-1111-111111111111","tags":{"ai.application.ver":"1.0.0.0","ai.device.id":"my_device_id","ai.device.locale":"EN","ai.device.model":"my_device_model","ai.device.oemName":"my_device_oem_name","ai.device.osVersion":"Windows 8","ai.device.type":"PC","ai.cloud.role":"my_role_name","ai.cloud.roleInstance":"my_role_instance","ai.location.ip":"127.0.0.0","ai.operation.id":"my_operation_id","ai.operation.name":"my_operation_name","ai.operation.parentId":"my_operation_parent_id","ai.session.id":"my_session_id","ai.session.isFirst":"false","ai.user.id":"my_user_id","ai.user.accountId":"my_account_id","ai.internal.sdkVersion":"SDK_VERSION_STRING"},"data":{"baseData":{"ver":2,"exceptions":[{"typeName":"Exception","message":"testException in \/Users\/sergeykanzhelev\/src\/HalloVerden\/ApplicationInsights\/php\/Tests\/TestUtils.php on line 130","hasFullStack":true,"id":1,"parsedStack":[{"level":"13","method":"main","assembly":"PHPUnit\\TextUI\\Command","fileName":"\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit","line":588},{"level":"12","method":"run","assembly":"PHPUnit\\TextUI\\Command","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/TextUI\/Command.php","line":151},{"level":"11","method":"doRun","assembly":"PHPUnit\\TextUI\\TestRunner","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/TextUI\/Command.php","line":198},{"level":"10","method":"run","assembly":"PHPUnit\\Framework\\TestSuite","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/TextUI\/TestRunner.php","line":529},{"level":"9","method":"run","assembly":"PHPUnit\\Framework\\TestSuite","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestSuite.php","line":776},{"level":"8","method":"run","assembly":"PHPUnit\\Framework\\TestCase","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestSuite.php","line":776},{"level":"7","method":"run","assembly":"PHPUnit\\Framework\\TestResult","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestCase.php","line":798},{"level":"6","method":"runBare","assembly":"PHPUnit\\Framework\\TestCase","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestResult.php","line":645},{"level":"5","method":"runTest","assembly":"PHPUnit\\Framework\\TestCase","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestCase.php","line":840},{"level":"4","method":"testCompleteException","assembly":"HalloVerden\\ApplicationInsights\\Tests\\TelemetryClientTest","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestCase.php","line":1145},{"level":"3","method":"throwNestedException","assembly":"HalloVerden\\ApplicationInsights\\Tests\\TestUtils","fileName":"\/Users\/sergeykanzhelev\/src\/HalloVerden\/ApplicationInsights\/php\/Tests\/TelemetryClientTest.php","line":41},{"level":"2","method":"throwNestedException","assembly":"HalloVerden\\ApplicationInsights\\Tests\\TestUtils","fileName":"\/Users\/sergeykanzhelev\/src\/HalloVerden\/ApplicationInsights\/php\/Tests\/TestUtils.php","line":129},{"level":"1","method":"throwNestedException","assembly":"HalloVerden\\ApplicationInsights\\Tests\\TestUtils","fileName":"\/Users\/sergeykanzhelev\/src\/HalloVerden\/ApplicationInsights\/php\/Tests\/TestUtils.php","line":129},{"level":"0","method":"throwNestedException","assembly":"HalloVerden\\ApplicationInsights\\Tests\\TestUtils","fileName":"\/Users\/sergeykanzhelev\/src\/HalloVerden\/ApplicationInsights\/php\/Tests\/TestUtils.php","line":129}]}],"properties":{"InlineProperty":"test_value","MyCustomProperty":42,"MyCustomProperty2":"test"},"measurements":{"duration_inner":42}},"baseType":"ExceptionData"}}]');
    $expectedValue = json_decode($expectedString, true);

    $this->assertEquals($this->removeMachineSpecificExceptionData($expectedValue, 4), $this->removeMachineSpecificExceptionData($queue, 4));

    if (TestUtils::sendDataToServer()) {
      $this->_telemetryClient->flush();
    }
  }

  /**
   * Tests a completely filled error.
   *
   * Ensure this method doesn't move in the source, if it does, the test will fail and needs to have a line number adjusted.
   */
  public function testCompleteError() {
    $errorsSupported = false;

    try {
      TestUtils::throwError();
    }
    catch (\Error $err) {
      $errorsSupported = true;
      $this->_telemetryClient->trackException($err, ['InlineProperty' => 'test_value'], ['duration_inner' => 42.0]);
    }

    if (!$errorsSupported) {
      return;
    }

    $queue = json_decode($this->_telemetryClient->getChannel()->getSerializedQueue(), true);
    $queue = $this->adjustDataInQueue($queue);

    $searchStrings = array("\\");
    $replaceStrings = array("\\\\");

    $expectedString = str_replace($searchStrings, $replaceStrings, '[{"ver":1,"name":"Microsoft.ApplicationInsights.Exception","time":"TIME_PLACEHOLDER","sampleRate":100,"iKey":"11111111-1111-1111-1111-111111111111","tags":{"ai.application.ver":"1.0.0.0","ai.device.id":"my_device_id","ai.device.locale":"EN","ai.device.model":"my_device_model","ai.device.oemName":"my_device_oem_name","ai.device.osVersion":"Windows 8","ai.device.type":"PC","ai.cloud.role":"my_role_name","ai.cloud.roleInstance":"my_role_instance","ai.location.ip":"127.0.0.0","ai.operation.id":"my_operation_id","ai.operation.name":"my_operation_name","ai.operation.parentId":"my_operation_parent_id","ai.session.id":"my_session_id","ai.session.isFirst":"false","ai.user.id":"my_user_id","ai.user.accountId":"my_account_id","ai.internal.sdkVersion":"SDK_VERSION_STRING"},"data":{"baseData":{"ver":2,"exceptions":[{"typeName":"ParseError","message":"syntax error, unexpected \'asdlkja\' (T_STRING) in \/Users\/sergeykanzhelev\/src\/ApplicationInsights\/php\/Tests\/TestUtils.php(141) : eval()\'d code on line 1","hasFullStack":true,"id":1,"parsedStack":[{"level":"10","method":"main","assembly":"PHPUnit\\TextUI\\Command","fileName":"\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit","line":588},{"level":"9","method":"run","assembly":"PHPUnit\\TextUI\\Command","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/TextUI\/Command.php","line":151},{"level":"8","method":"doRun","assembly":"PHPUnit\\TextUI\\TestRunner","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/TextUI\/Command.php","line":198},{"level":"7","method":"run","assembly":"PHPUnit\\Framework\\TestSuite","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/TextUI\/TestRunner.php","line":529},{"level":"6","method":"run","assembly":"PHPUnit\\Framework\\TestSuite","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestSuite.php","line":776},{"level":"5","method":"run","assembly":"PHPUnit\\Framework\\TestCase","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestSuite.php","line":776},{"level":"4","method":"run","assembly":"PHPUnit\\Framework\\TestResult","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestCase.php","line":798},{"level":"3","method":"runBare","assembly":"PHPUnit\\Framework\\TestCase","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestResult.php","line":645},{"level":"2","method":"runTest","assembly":"PHPUnit\\Framework\\TestCase","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestCase.php","line":840},{"level":"1","method":"testCompleteError","assembly":"ApplicationInsights\\Tests\\Telemetry_Client_Test","fileName":"phar:\/\/\/usr\/local\/Cellar\/phpunit\/7.1.5\/bin\/phpunit\/phpunit\/Framework\/TestCase.php","line":1145},{"level":"0","method":"throwError","assembly":"HalloVerden\\ApplicationInsights\\Tests\\TestUtils","fileName":"\/Users\/sergeykanzhelev\/src\/Halloverden\/ApplicationInsights\/php\/Tests\/TelemetryClientTest.php","line":72}]}],"properties":{"InlineProperty":"test_value","MyCustomProperty":42,"MyCustomProperty2":"test"},"measurements":{"duration_inner":42}},"baseType":"ExceptionData"}}]');
    $expectedValue = json_decode($expectedString, true);

    $this->assertEquals($this->removeMachineSpecificExceptionData($expectedValue, 1), $this->removeMachineSpecificExceptionData($queue, 1));

    if (TestUtils::sendDataToServer()) {
      $this->_telemetryClient->flush();
    }
  }

  /**
   * Verifies the object is constructed properly.
   */
  public function testConstructor() {
    $telemetryClient = new TelemetryClient();
    $this->assertNotNull($telemetryClient->getContext());
    $this->assertEquals($telemetryClient->getChannel(), new TelemetryChannel());
  }


  /**
   * Verifies the guzzle client is properly overridden.
   */
  public function testGuzzleClientOverrideConstructor() {
    if (class_exists('\GuzzleHttp\Client') == true) {
      $baseUrl = "http://www.foo2.com";
      $client = new Client(['base_uri' => $baseUrl]);
      $telemetryChannel = new TelemetryChannel('/what', $client);
      $telemetryClient = new TelemetryClient(null, $telemetryChannel);
      $this->assertEquals($telemetryClient->getChannel()->GetClient(), new Client(['base_uri' => $baseUrl]));
    }
    else {
      $this->markTestSkipped("Client does not exist");
    }
  }



  /**
   * Tests that sdk version can be overridden
   */
  public function testPluginCanOverrideSdkVersion() {
    $telemetryClient = new TelemetryClient();

    $context = $telemetryClient->getContext()->getInternalContext();

    $this->assertNotNull($context);
    $this->assertNotNull($context->getSdkVersion());
    $context->setSdkVersion("version");
    $this->assertEquals("version", $context->getSdkVersion());
  }


  /**
   * Removes machine specific data from exceptions.
   * @param array $queue The queue of items
   * @return array
   */
  private function removeMachineSpecificExceptionData($queue, $maxLevel) {
    foreach ($queue as &$queueItem) {
      foreach ($queueItem['data']['baseData']['exceptions'] as &$exception) {
        if (preg_match('([A-Za-z]+\.php)', $exception['message'], $matches) == 1) {
          $exception['message'] = $matches[0];
        }
        else {
          $exception['message'] = NULL;
        }

        $exception['parsedStack'] =
          array_filter($exception['parsedStack'], function($e) use($maxLevel){
            return $e['level'] < $maxLevel;
          });

        $exception['parsedStack'] = array_combine(range(0, count($exception['parsedStack'])-1), $exception['parsedStack']);

        foreach ($exception['parsedStack'] as &$stackFrame) {
          if (array_key_exists('fileName', $stackFrame)) {
            if (preg_match('([A-Za-z]+\.php)', $stackFrame['fileName'], $matches) == 1) {
              $stackFrame['fileName'] = $matches[0];
            }
            else {
              $stackFrame['fileName'] = NULL;
            }
          }
        }
      }
    }
    return $queue;
  }

  /**
   * Remotes transient data from validation queues
   * @param array $queue The queue of items
   * @return array
   */
  private function adjustDataInQueue($queue) {
    foreach ($queue as &$queueItem) {
      $queueItem['time'] = 'TIME_PLACEHOLDER';
      $queueItem['tags']['ai.internal.sdkVersion'] = 'SDK_VERSION_STRING';
      if (array_key_exists('id', $queueItem['data']['baseData']) == true) {
        $queueItem['data']['baseData']['id'] = 'ID_PLACEHOLDER';
      }
    }
    return $queue;
  }
}
