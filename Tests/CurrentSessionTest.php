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

use HalloVerden\ApplicationInsights\Channel\Contracts\Utils;
use HalloVerden\ApplicationInsights\CurrentSession;
use PHPUnit\Framework\TestCase;

/**
 * Contains tests for Current_Session class
 */
class CurrentSessionTest extends TestCase {
  private $sessionId;
  private $sessionCreatedTime;
  private $sessionLastRenewedTime;

  protected function setUp(): void {
    $this->sessionId = Utils::returnGuid();
    $this->sessionCreatedTime = time();
    $this->sessionLastRenewedTime = time() - 10000;
    TestUtils::setSessionCookie($this->sessionId, $this->sessionCreatedTime, $this->sessionLastRenewedTime);
  }

  protected function tearDown(): void {
    TestUtils::clearSessionCookie();
  }

  /**
   * Verifies the object is constructed properly.
   */
  public function testConstructor() {
    $currentSession = new CurrentSession();

    $this->assertEquals($this->sessionId, $currentSession->id);
    $this->assertEquals($this->sessionCreatedTime, $currentSession->sessionCreated);
    $this->assertEquals($this->sessionLastRenewedTime, $currentSession->sessionLastRenewed);
  }

  /**
   * Verifies the object is constructed properly.
   */
  public function testConstructorWithNoCookie() {
    TestUtils::clearSessionCookie();
    $currentSession = new CurrentSession();

    $this->assertEquals(NULL, $currentSession->id);
    $this->assertEquals(NULL, $currentSession->sessionCreated);
    $this->assertEquals(NULL, $currentSession->sessionLastRenewed);
  }
}
