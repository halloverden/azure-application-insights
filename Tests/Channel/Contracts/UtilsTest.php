<?php

/*
 * This file is part of the microsoft/application-insights package.
 *
 * (c) Microsoft Corporation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HalloVerden\ApplicationInsights\Tests\Channel\Contracts;

use HalloVerden\ApplicationInsights\Channel\Contracts\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Contains tests for Utils class
 */
class UtilsTest extends TestCase {
  public function testConvertMillisecondsToTimeSpan() {

    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(0), "00:00:00.000");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(1), "00:00:00.001", "milliseconds digit 1");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(10), "00:00:00.010", "milliseconds digit 2");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(100), "00:00:00.100", "milliseconds digit 3");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(1 * 1000), "00:00:01.000", "seconds digit 1");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(10 * 1000), "00:00:10.000", "seconds digit 2");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(1 * 60 * 1000), "00:01:00.000", "minutes digit 1");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(10 * 60 * 1000), "00:10:00.000", "minutes digit 2");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(1 * 60 * 60 * 1000), "01:00:00.000", "hours digit 1");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(10 * 60 * 60 * 1000), "10:00:00.000", "hours digit 2");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(24 * 60 * 60 * 1000), "00:00:00.000", "hours overflow");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(11 * 3600000 + 11 * 60000 + 11111), "11:11:11.111", "all digits");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(""), "00:00:00.000", "invalid input");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan("'"), "00:00:00.000", "invalid input");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(NULL), "00:00:00.000", "invalid input");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan([]), "00:00:00.000", "invalid input");
    $this->assertEquals(Utils::convertMillisecondsToTimeSpan(-1), "00:00:00.000", "invalid input");

  }
}
