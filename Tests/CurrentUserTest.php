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
use HalloVerden\ApplicationInsights\CurrentUser;
use PHPUnit\Framework\TestCase;

/**
 * Contains tests for Current_User class
 */
class CurrentUserTest extends TestCase {
  private $userId;

  protected function setUp(): void {
    $this->userId = Utils::returnGuid();
    TestUtils::setUserCookie($this->userId);
  }

  protected function tearDown(): void {
    TestUtils::clearUserCookie();
  }

  /**
   * Verifies the object is constructed properly.
   */
  public function testConstructor() {
    $currentUser = new CurrentUser();

    $this->assertEquals($currentUser->id, $this->userId);
  }

  /**
   * Verifies the object is constructed properly.
   */
  public function testConstructorWithNoCookie() {
    TestUtils::clearUserCookie();
    $currentUser = new CurrentUser();

    $this->assertTrue($currentUser->id != NULL && $currentUser->id != $this->userId);
  }

}
