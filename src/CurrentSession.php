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

/**
 * Main object used for managing sessions for other telemetry items.
 */
class CurrentSession {
  /**
   * The current session id.
   */
  public $id;

  /**
   * When the session was created
   */
  public $sessionCreated;

  /**
   * When the session was last renewed
   */
  public $sessionLastRenewed;

  /**
   * Initializes a new Current_Session.
   */
  function __construct() {
    if (array_key_exists('ai_session', $_COOKIE)) {
      $parts = explode('|', $_COOKIE['ai_session']);
      $len = sizeof($parts);
      if ($len > 0) {
        $this->id = $parts[0];
      }

      if ($len > 1) {
        $this->sessionCreated = strtotime($parts[1]);
      }

      if ($len > 2) {
        $this->sessionLastRenewed = strtotime($parts[2]);
      }
    }
  }
}
