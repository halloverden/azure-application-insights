<?php

/*
 * This file is part of the microsoft/application-insights package.
 *
 * (c) Microsoft Corporation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HalloVerden\ApplicationInsights\Tests\Channel;

use HalloVerden\ApplicationInsights\Channel\TelemetryChannel;
use PHPUnit\Framework\TestCase;

/**
 * Contains tests for TelemetrySender class
 */
class TelemetryChannelTest extends TestCase {
  public function testConstructor() {
    $telemetryChannel = new TelemetryChannel();
    $this->assertEquals($telemetryChannel->getEndpointUrl(), 'https://dc.services.visualstudio.com/v2/track', 'Default Endpoint URL is incorrect.');
    $this->assertEquals($telemetryChannel->getQueue(), [], 'Queue should be empty by default.');
  }

  public function testEndpointUrl() {
    $telemetryChannel = new TelemetryChannel();
    $telemetryChannel->setEndpointUrl('http://foo.com');
    $this->assertEquals($telemetryChannel->getEndpointUrl(), 'http://foo.com');
  }

  public function testQueue() {
    $telemetryChannel = new TelemetryChannel();
    $telemetryChannel->setQueue([42, 42, 42]);
    $this->assertEquals($telemetryChannel->getQueue(), [42, 42, 42]);
  }
}
