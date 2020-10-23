<?php


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
